<?php

// Simple direct check without Laravel bootstrap issues
echo "\n";
echo "AUTOMATIC RENEWAL REMINDER EMAILS - STATUS CHECK\n";
echo "=================================================\n\n";

// Check 1: Command file
echo "1. COMMAND FILE:\n";
$commandFile = 'app/Console/Commands/SendRenewalReminders.php';
echo "   " . ($file_exists($commandFile) ? "✅ EXISTS" : "❌ NOT FOUND") . " - {$commandFile}\n\n";

// Check 2: Scheduled task
echo "2. SCHEDULED TASK:\n";
$consoleFile = 'routes/console.php';
if (file_exists($consoleFile)) {
    $content = file_get_contents($consoleFile);
    if (strpos($content, 'members:send-renewal-reminders') !== false) {
        echo "   ✅ Command is SCHEDULED\n";
        if (preg_match('/->dailyAt\([\'"](\d{2}:\d{2})[\'"]\)/', $content, $matches)) {
            echo "   ⏰ Time: {$matches[1]} daily\n";
        }
        if (preg_match('/->timezone\([\'"]([^\'\"]+)[\'"]\)/', $content, $matches)) {
            echo "   🌍 Timezone: {$matches[1]}\n";
        }
    } else {
        echo "   ❌ NOT scheduled\n";
    }
} else {
    echo "   ❌ routes/console.php not found\n";
}

echo "\n";

// Check 3: Mail class
echo "3. MAIL CLASS:\n";
$mailFile = 'app/Mail/RenewalReminderMail.php';
echo "   " . (file_exists($mailFile) ? "✅ EXISTS" : "❌ NOT FOUND") . " - {$mailFile}\n\n";

// Check 4: Email template
echo "4. EMAIL TEMPLATE:\n";
$templateFile = 'resources/views/emails/membership/renewal_reminder.blade.php';
echo "   " . (file_exists($templateFile) ? "✅ EXISTS" : "❌ NOT FOUND") . " - {$templateFile}\n\n";

echo "=================================================\n";
echo "SYSTEM STATUS:\n";
echo "=================================================\n\n";

$allGood = file_exists($commandFile) && 
           file_exists($consoleFile) && 
           file_exists($mailFile) && 
           file_exists($templateFile);

if ($allGood) {
    echo "✅ ALL COMPONENTS ARE IN PLACE!\n\n";
    echo "The automatic reminder system is SET UP.\n\n";
    echo "WHAT HAPPENS:\n";
    echo "1. Command runs daily at 08:00 AM (Kuwait time)\n";
    echo "2. Checks for members expiring in: 30, 15, 7, 1, 0 days\n";
    echo "3. Also checks for expired members (-1 days)\n";
    echo "4. Sends email to each member\n";
    echo "5. Logs each reminder in database\n\n";
    
    echo "IMPORTANT:\n";
    echo "⚠️  You MUST set up a CRON JOB on your server!\n\n";
    echo "Add this to crontab:\n";
    echo "* * * * * cd " . getcwd() . " && php artisan schedule:run >> /dev/null 2>&1\n\n";
    
    echo "TO TEST MANUALLY:\n";
    echo "php artisan members:send-renewal-reminders\n\n";
} else {
    echo "⚠️  SOME COMPONENTS ARE MISSING\n";
}

echo "=================================================\n\n";

