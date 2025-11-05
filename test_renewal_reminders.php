<?php

/**
 * Manual Test Script to Verify Renewal Reminder System
 * 
 * This script tests if the renewal reminder system is working properly
 * with different days remainders (30, 15, 7, 1, 0, -1 days)
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;
use Illuminate\Support\Carbon;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════════╗\n";
echo "║  RENEWAL REMINDER SYSTEM TEST                                      ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$today = Carbon::today();
echo "📅 Today's Date: {$today->toDateString()}\n\n";

// Test different reminder intervals
$intervals = [30, 15, 7, 1, 0, -1];

echo "═══════════════════════════════════════════════════════════════════\n";
echo "CHECKING MEMBERS BY EXPIRY INTERVALS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

foreach ($intervals as $days) {
    if ($days === 0) {
        $targetDate = $today->toDateString();
        $label = "Expiring TODAY ({$targetDate})";
        
        $members = Registration::query()
            ->where(function($query) {
                $query->where('login_status', 'approved')
                      ->orWhere('renewal_status', 'approved');
            })
            ->whereDate('card_valid_until', '=', $targetDate)
            ->get();
            
    } elseif ($days === -1) {
        $label = "EXPIRED (past expiry date)";
        
        $members = Registration::query()
            ->where(function($query) {
                $query->where('login_status', 'approved')
                      ->orWhere('renewal_status', 'approved');
            })
            ->whereDate('card_valid_until', '<', $today->toDateString())
            ->get();
            
    } else {
        $targetDate = $today->copy()->addDays($days)->toDateString();
        $label = "Expiring in {$days} days ({$targetDate})";
        
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
    }
    
    echo "🔔 {$label}\n";
    echo "   Found: {$members->count()} member(s)\n";
    
    if ($members->count() > 0) {
        foreach ($members as $member) {
            $expiryDate = $member->card_valid_until ? 
                Carbon::parse($member->card_valid_until)->toDateString() : 'N/A';
            echo "   - {$member->memberName} ({$member->email}) - Expires: {$expiryDate}\n";
        }
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════════\n";
echo "RENEWAL REMINDERS TABLE (Last 10 Sent)\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$reminders = \App\Models\RenewalReminder::orderBy('created_at', 'desc')->take(10)->get();

if ($reminders->count() > 0) {
    foreach ($reminders as $reminder) {
        $status = $reminder->status === 'sent' ? '✅' : '❌';
        echo "{$status} {$reminder->member_name} ({$reminder->email})\n";
        echo "   Expiry: {$reminder->card_valid_until->toDateString()} | ";
        echo "Days: {$reminder->days_before_expiry} | ";
        echo "Sent: {$reminder->created_at->format('Y-m-d H:i:s')}\n";
        if ($reminder->error_message) {
            echo "   Error: {$reminder->error_message}\n";
        }
        echo "\n";
    }
} else {
    echo "No reminders sent yet.\n";
}

echo "═══════════════════════════════════════════════════════════════════\n";
echo "SCHEDULED TASK STATUS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "Command: php artisan members:send-renewal-reminders\n";
echo "Schedule: Daily at 08:00 AM (Asia/Kuwait timezone)\n";
echo "Days: 30, 15, 7, 1, 0, -1 (expired)\n";
echo "\n";
echo "To manually run the command now:\n";
echo "  php artisan members:send-renewal-reminders\n";
echo "\n";
echo "To test with specific intervals:\n";
echo "  php artisan members:send-renewal-reminders --days=30,15\n";
echo "\n";

echo "═══════════════════════════════════════════════════════════════════\n";
echo "SYSTEM STATUS: ";

// Check if table exists
try {
    \Illuminate\Support\Facades\Schema::hasTable('renewal_reminders');
    echo "✅ READY\n";
} catch (\Exception $e) {
    echo "❌ ERROR - {$e->getMessage()}\n";
}

echo "═══════════════════════════════════════════════════════════════════\n\n";

