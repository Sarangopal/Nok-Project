<?php

/**
 * Comprehensive Check: Automatic Renewal Reminder Email System
 * 
 * This script verifies if automatic reminder emails are working properly
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;
use App\Models\RenewalReminder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║  AUTOMATIC RENEWAL REMINDER EMAILS - SYSTEM CHECK               ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$today = Carbon::today();
echo "📅 Check Date: {$today->toDateString()}\n";
echo "🕐 Check Time: " . now()->format('H:i:s') . "\n\n";

// ========================================================================
// 1. CHECK COMMAND EXISTS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "1. CHECKING COMMAND EXISTENCE\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$commandFile = app_path('Console/Commands/SendRenewalReminders.php');
if (file_exists($commandFile)) {
    echo "✅ SendRenewalReminders command EXISTS\n";
    echo "   Location: {$commandFile}\n";
} else {
    echo "❌ SendRenewalReminders command NOT FOUND\n";
    echo "   Expected: {$commandFile}\n";
}

echo "\n";

// ========================================================================
// 2. CHECK SCHEDULED TASK
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "2. CHECKING SCHEDULED TASK CONFIGURATION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$consoleFile = base_path('routes/console.php');
if (file_exists($consoleFile)) {
    $content = file_get_contents($consoleFile);
    
    if (strpos($content, 'members:send-renewal-reminders') !== false) {
        echo "✅ Command is SCHEDULED in routes/console.php\n";
        
        // Check schedule time
        if (preg_match('/->dailyAt\([\'"](\d{2}:\d{2})[\'"]\)/', $content, $matches)) {
            echo "✅ Schedule Time: {$matches[1]} daily\n";
        }
        
        // Check timezone
        if (preg_match('/->timezone\([\'"]([^\'\"]+)[\'"]\)/', $content, $matches)) {
            echo "✅ Timezone: {$matches[1]}\n";
        }
    } else {
        echo "⚠️  Command NOT found in schedule\n";
        echo "   Check: routes/console.php\n";
    }
} else {
    echo "❌ routes/console.php NOT FOUND\n";
}

echo "\n";

// ========================================================================
// 3. CHECK DATABASE TABLE
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "3. CHECKING DATABASE TABLES\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

try {
    // Check if renewal_reminders table exists
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('renewal_reminders');
    
    if ($tableExists) {
        echo "✅ renewal_reminders table EXISTS\n";
        
        $totalReminders = RenewalReminder::count();
        $todayReminders = RenewalReminder::whereDate('created_at', $today)->count();
        $sentReminders = RenewalReminder::where('status', 'sent')->count();
        $failedReminders = RenewalReminder::where('status', 'failed')->count();
        
        echo "   Total reminders logged: {$totalReminders}\n";
        echo "   Sent today: {$todayReminders}\n";
        echo "   Total sent: {$sentReminders}\n";
        echo "   Total failed: {$failedReminders}\n";
    } else {
        echo "❌ renewal_reminders table NOT FOUND\n";
        echo "   Run: php artisan migrate\n";
    }
    
    echo "\n";
    
    // Check registrations table
    $registrationsExist = \Illuminate\Support\Facades\Schema::hasTable('registrations');
    if ($registrationsExist) {
        echo "✅ registrations table EXISTS\n";
        
        $totalMembers = Registration::where(function($q) {
            $q->where('login_status', 'approved')
              ->orWhere('renewal_status', 'approved');
        })->count();
        
        $withExpiry = Registration::where(function($q) {
            $q->where('login_status', 'approved')
              ->orWhere('renewal_status', 'approved');
        })->whereNotNull('card_valid_until')->count();
        
        echo "   Total approved members: {$totalMembers}\n";
        echo "   Members with expiry dates: {$withExpiry}\n";
    } else {
        echo "❌ registrations table NOT FOUND\n";
    }
} catch (\Exception $e) {
    echo "❌ Database error: {$e->getMessage()}\n";
}

echo "\n";

// ========================================================================
// 4. CHECK MEMBERS DUE FOR REMINDERS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "4. CHECKING MEMBERS DUE FOR REMINDERS TODAY\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$intervals = [30, 15, 7, 1, 0, -1];
$totalDue = 0;

foreach ($intervals as $days) {
    if ($days === 0) {
        $members = Registration::query()
            ->where(function($query) {
                $query->where('login_status', 'approved')
                      ->orWhere('renewal_status', 'approved');
            })
            ->whereDate('card_valid_until', '=', $today->toDateString())
            ->get();
        $label = "Expiring TODAY";
    } elseif ($days === -1) {
        $members = Registration::query()
            ->where(function($query) {
                $query->where('login_status', 'approved')
                      ->orWhere('renewal_status', 'approved');
            })
            ->whereDate('card_valid_until', '<', $today->toDateString())
            ->get();
        $label = "EXPIRED";
    } else {
        $members = Registration::query()
            ->where(function($query) {
                $query->where('login_status', 'approved')
                      ->orWhere('renewal_status', 'approved');
            })
            ->whereNotNull('card_valid_until')
            ->whereDate('card_valid_until', '>=', $today->toDateString())
            ->get()
            ->filter(function($member) use ($today, $days) {
                $validUntil = Carbon::parse($member->card_valid_until)->startOfDay();
                $todayStart = $today->copy()->startOfDay();
                $daysRemaining = (int) $todayStart->diffInDays($validUntil, false);
                return $daysRemaining === $days;
            });
        $label = "{$days} days before expiry";
    }
    
    $count = $members->count();
    $totalDue += $count;
    
    if ($count > 0) {
        echo "🔔 {$label}: {$count} member(s)\n";
        foreach ($members as $member) {
            $expiryDate = optional($member->card_valid_until)->toDateString() ?? 'N/A';
            echo "   - {$member->memberName} ({$member->email}) - Expires: {$expiryDate}\n";
        }
    } else {
        echo "⚪ {$label}: 0 members\n";
    }
}

echo "\n";
echo "📊 Total members due for reminder today: {$totalDue}\n";

echo "\n";

// ========================================================================
// 5. CHECK RECENT REMINDER LOGS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "5. RECENT REMINDER LOGS (Last 10)\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

try {
    $recentReminders = RenewalReminder::orderBy('created_at', 'desc')->take(10)->get();
    
    if ($recentReminders->count() > 0) {
        foreach ($recentReminders as $reminder) {
            $status = $reminder->status === 'sent' ? '✅' : '❌';
            $daysLabel = match($reminder->days_before_expiry) {
                -1 => 'EXPIRED',
                0 => 'TODAY',
                default => "{$reminder->days_before_expiry} days"
            };
            
            echo "{$status} {$reminder->member_name} ({$reminder->email})\n";
            echo "   Expiry: {$reminder->card_valid_until->toDateString()} | ";
            echo "Days: {$daysLabel} | ";
            echo "Sent: {$reminder->created_at->format('M d, Y H:i:s')}\n";
            
            if ($reminder->status === 'failed' && $reminder->error_message) {
                echo "   ⚠️  Error: {$reminder->error_message}\n";
            }
            echo "\n";
        }
    } else {
        echo "⚪ No reminders have been sent yet\n";
        echo "   This is normal if:\n";
        echo "   - System just set up\n";
        echo "   - No members due for reminders\n";
        echo "   - Cron job not running\n";
    }
} catch (\Exception $e) {
    echo "❌ Error reading logs: {$e->getMessage()}\n";
}

echo "\n";

// ========================================================================
// 6. CHECK MAIL CONFIGURATION
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "6. CHECKING MAIL CONFIGURATION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$mailDriver = config('mail.default');
$mailHost = config('mail.mailers.smtp.host');
$mailPort = config('mail.mailers.smtp.port');
$mailFrom = config('mail.from.address');

echo "Mail Driver: {$mailDriver}\n";
echo "SMTP Host: {$mailHost}\n";
echo "SMTP Port: {$mailPort}\n";
echo "From Address: {$mailFrom}\n";

if ($mailDriver === 'log') {
    echo "\n⚠️  WARNING: Mail driver is set to 'log'\n";
    echo "   Emails will be saved to: storage/logs/laravel.log\n";
    echo "   Change MAIL_MAILER in .env to 'smtp' for actual sending\n";
} else {
    echo "\n✅ Mail driver is configured for actual sending\n";
}

echo "\n";

// ========================================================================
// 7. TEST RUN THE COMMAND
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "7. TEST RUNNING THE COMMAND\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "Attempting to run: php artisan members:send-renewal-reminders\n\n";

try {
    $exitCode = Artisan::call('members:send-renewal-reminders');
    $output = Artisan::output();
    
    if ($exitCode === 0) {
        echo "✅ Command executed SUCCESSFULLY!\n\n";
        echo "Command Output:\n";
        echo "─────────────────────────────────────────────────────────────\n";
        echo $output;
        echo "─────────────────────────────────────────────────────────────\n";
    } else {
        echo "⚠️  Command executed with exit code: {$exitCode}\n";
        echo $output;
    }
} catch (\Exception $e) {
    echo "❌ Error running command: {$e->getMessage()}\n";
}

echo "\n";

// ========================================================================
// 8. CHECK CRON JOB STATUS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "8. CRON JOB STATUS CHECK\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "⚠️  IMPORTANT: For automatic emails to work, you need a cron job!\n\n";

echo "Required cron job entry:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "* * * * * cd " . base_path() . " && php artisan schedule:run >> /dev/null 2>&1\n";
echo "─────────────────────────────────────────────────────────────────\n\n";

echo "This cron job should run every minute and will trigger the\n";
echo "scheduled reminder command daily at 08:00 AM (Kuwait time).\n\n";

echo "To check if cron is running (on Linux/Mac):\n";
echo "  crontab -l\n\n";

echo "On Windows with Task Scheduler:\n";
echo "  Create a scheduled task to run the command daily\n";

echo "\n";

// ========================================================================
// 9. FINAL SUMMARY
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "FINAL SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$checks = [
    'Command exists' => file_exists($commandFile),
    'Scheduled in console.php' => strpos(file_get_contents($consoleFile), 'members:send-renewal-reminders') !== false,
    'Database table exists' => \Illuminate\Support\Facades\Schema::hasTable('renewal_reminders'),
    'Mail configured' => !empty($mailDriver) && !empty($mailFrom),
];

$allGood = true;
foreach ($checks as $check => $status) {
    echo ($status ? '✅' : '❌') . " {$check}\n";
    if (!$status) $allGood = false;
}

echo "\n";

if ($allGood && $totalDue > 0) {
    echo "🎉 SYSTEM IS WORKING! Reminders should be sent automatically!\n\n";
    echo "Next Steps:\n";
    echo "1. Ensure cron job is set up on your server\n";
    echo "2. Wait for scheduled time (08:00 AM daily)\n";
    echo "3. Check renewal_reminders table for new entries\n";
    echo "4. Monitor member emails\n";
} elseif ($allGood && $totalDue === 0) {
    echo "✅ SYSTEM IS READY! No members due for reminders today.\n\n";
    echo "The system is configured correctly and will automatically send\n";
    echo "reminders when members are due (30, 15, 7, 1, 0, -1 days).\n";
} else {
    echo "⚠️  SOME ISSUES FOUND - Review the checks above.\n\n";
    echo "Common fixes:\n";
    echo "- Run migrations: php artisan migrate\n";
    echo "- Configure mail in .env file\n";
    echo "- Set up cron job on server\n";
}

echo "\n═══════════════════════════════════════════════════════════════════\n\n";

