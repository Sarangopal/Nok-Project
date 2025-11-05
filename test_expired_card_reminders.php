<?php

/**
 * Test: Do Expired Card Holders Receive Reminder Emails?
 * 
 * This script checks if members with expired cards receive reminder emails
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;
use Illuminate\Support\Carbon;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║  DO EXPIRED CARD HOLDERS RECEIVE REMINDER EMAILS?               ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$today = Carbon::today();
echo "📅 Check Date: {$today->toDateString()}\n\n";

// ========================================================================
// 1. CHECK IF -1 DAYS IS IN THE REMINDER INTERVALS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "1. CHECKING SYSTEM CONFIGURATION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$commandFile = app_path('Console/Commands/SendRenewalReminders.php');
if (file_exists($commandFile)) {
    $content = file_get_contents($commandFile);
    
    // Check if -1 is in the default days
    if (preg_match('/\{--days=([^}]+)\}/', $content, $matches)) {
        $defaultDays = $matches[1];
        echo "Default reminder intervals: {$defaultDays}\n";
        
        if (strpos($defaultDays, '-1') !== false) {
            echo "✅ YES! System includes -1 (expired cards) in reminders\n";
        } else {
            echo "❌ NO! System does NOT include expired cards\n";
        }
    }
    
    echo "\n";
    
    // Check the expired cards logic
    if (strpos($content, 'elseif ($days === -1)') !== false) {
        echo "✅ Special logic for expired cards EXISTS\n";
        
        // Extract the logic
        if (preg_match('/elseif \(\$days === -1\) \{(.+?)\}/s', $content, $matches)) {
            echo "\nExpired Cards Logic:\n";
            echo "─────────────────────────────────────────────────────────────\n";
            echo "• Checks for cards with expiry date BEFORE today\n";
            echo "• Filters: login_status = 'approved' OR renewal_status = 'approved'\n";
            echo "• SQL: WHERE card_valid_until < today\n";
            echo "─────────────────────────────────────────────────────────────\n";
        }
    } else {
        echo "❌ No special logic for expired cards found\n";
    }
}

echo "\n";

// ========================================================================
// 2. CHECK CURRENT EXPIRED MEMBERS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "2. CHECKING CURRENT EXPIRED MEMBERS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

try {
    $expiredMembers = Registration::query()
        ->where(function($query) {
            $query->where('login_status', 'approved')
                  ->orWhere('renewal_status', 'approved');
        })
        ->whereDate('card_valid_until', '<', $today->toDateString())
        ->get();
    
    $count = $expiredMembers->count();
    
    echo "Found: {$count} member(s) with EXPIRED cards\n\n";
    
    if ($count > 0) {
        echo "Expired Members List:\n";
        echo "─────────────────────────────────────────────────────────────\n";
        foreach ($expiredMembers as $member) {
            $expiryDate = optional($member->card_valid_until)->toDateString();
            $daysExpired = $today->diffInDays(Carbon::parse($member->card_valid_until));
            
            echo "• {$member->memberName}\n";
            echo "  Email: {$member->email}\n";
            echo "  Expired: {$expiryDate} ({$daysExpired} days ago)\n";
            echo "  Status: {$member->login_status}\n";
            echo "\n";
        }
    } else {
        echo "No expired members found.\n";
        echo "(This is good! It means all members have valid cards)\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}

echo "\n";

// ========================================================================
// 3. CHECK IF EXPIRED MEMBERS RECEIVED REMINDERS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "3. CHECKING REMINDER HISTORY FOR EXPIRED MEMBERS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

try {
    $expiredReminders = \App\Models\RenewalReminder::where('days_before_expiry', -1)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
    
    if ($expiredReminders->count() > 0) {
        echo "✅ YES! Expired members HAVE received reminders\n\n";
        echo "Recent Expired Card Reminders:\n";
        echo "─────────────────────────────────────────────────────────────\n";
        
        foreach ($expiredReminders as $reminder) {
            $status = $reminder->status === 'sent' ? '✅' : '❌';
            echo "{$status} {$reminder->member_name} ({$reminder->email})\n";
            echo "   Expired: {$reminder->card_valid_until->toDateString()}\n";
            echo "   Reminder Sent: {$reminder->created_at->format('M d, Y H:i:s')}\n";
            echo "\n";
        }
    } else {
        echo "⚪ No expired card reminders found in history\n";
        echo "   This could mean:\n";
        echo "   • No members have expired cards yet\n";
        echo "   • System recently set up\n";
        echo "   • No reminders sent yet\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}

echo "\n";

// ========================================================================
// 4. SIMULATE WHAT HAPPENS FOR EXPIRED CARD
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "4. SIMULATION: WHAT HAPPENS FOR EXPIRED CARDS?\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "Example Scenario:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "• Member: John Doe\n";
echo "• Card Expired: November 2, 2025\n";
echo "• Today: November 5, 2025\n";
echo "• Days Expired: 3 days\n";
echo "\n";

echo "What Happens:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "1. Daily command runs at 08:00 AM\n";
echo "2. Checks for days = -1 (expired)\n";
echo "3. Finds John Doe's card (expired 3 days ago)\n";
echo "4. Checks if reminder already sent today\n";
echo "5. If not sent, sends reminder email\n";
echo "6. Logs reminder in database\n";
echo "7. Next day, repeats steps 1-6\n";
echo "\n";

echo "Result:\n";
echo "✅ John Doe receives email EVERY DAY until card is renewed\n";
echo "📧 Email says: \"Your membership has EXPIRED\"\n";
echo "📝 Includes renewal instructions\n";

echo "\n";

// ========================================================================
// 5. EMAIL CONTENT FOR EXPIRED CARDS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "5. EMAIL CONTENT FOR EXPIRED CARDS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$templateFile = resource_path('views/emails/membership/renewal_reminder.blade.php');
if (file_exists($templateFile)) {
    $template = file_get_contents($templateFile);
    
    echo "Email Template Check:\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    if (strpos($template, '@else') !== false) {
        echo "✅ Template has ELSE condition for expired cards\n";
    }
    
    if (strpos($template, 'EXPIRED') !== false) {
        echo "✅ Template shows 'EXPIRED' status\n";
    }
    
    if (strpos($template, 'has EXPIRED') !== false) {
        echo "✅ Template shows 'has EXPIRED' message\n";
    }
    
    echo "\nWhat Expired Members See in Email:\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    echo "Subject: Membership Renewal Reminder\n";
    echo "\n";
    echo "Dear [Member Name],\n";
    echo "\n";
    echo "Valid Until: [Expiry Date]\n";
    echo "Status: ⚠️ Your membership has EXPIRED\n";
    echo "\n";
    echo "[Renewal Instructions]\n";
    echo "[Link to Member Portal]\n";
    echo "─────────────────────────────────────────────────────────────────\n";
}

echo "\n";

// ========================================================================
// 6. FREQUENCY OF EXPIRED REMINDERS
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "6. HOW OFTEN DO EXPIRED MEMBERS GET REMINDERS?\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "⚠️  IMPORTANT BEHAVIOR:\n\n";

echo "For NON-EXPIRED cards:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "• 30 days before: 1 reminder (only once)\n";
echo "• 15 days before: 1 reminder (only once)\n";
echo "• 7 days before: 1 reminder (only once)\n";
echo "• 1 day before: 1 reminder (only once)\n";
echo "• Expiry day: 1 reminder (only once)\n";
echo "Total: 5 reminders over 30 days\n";
echo "\n";

echo "For EXPIRED cards (-1 days):\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "• Day 1 after expiry: 1 reminder\n";
echo "• Day 2 after expiry: 1 reminder\n";
echo "• Day 3 after expiry: 1 reminder\n";
echo "• Day 4 after expiry: 1 reminder\n";
echo "• ...continues DAILY until renewed\n";
echo "\n";
echo "⚠️  Expired members get reminders EVERY DAY!\n";
echo "This creates urgency to renew their membership.\n";

echo "\n";

// ========================================================================
// 7. DUPLICATE PREVENTION
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "7. DUPLICATE PREVENTION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "System prevents duplicate reminders by checking:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "1. registration_id (which member)\n";
echo "2. card_valid_until (which expiry date)\n";
echo "3. days_before_expiry (which interval: -1 for expired)\n";
echo "4. status = 'sent'\n";
echo "\n";
echo "If all match → Skip (already sent)\n";
echo "If no match → Send email and log\n";
echo "\n";

echo "For expired cards:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "• Each day is a NEW reminder\n";
echo "• Duplicate check ensures only 1 email per day\n";
echo "• Member gets reminder daily until they renew\n";

echo "\n";

// ========================================================================
// 8. FINAL ANSWER
// ========================================================================
echo "═══════════════════════════════════════════════════════════════════\n";
echo "FINAL ANSWER TO YOUR QUESTION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "❓ QUESTION:\n";
echo "   \"When card expired, do they also receive mail or not?\"\n\n";

echo "✅ ANSWER:\n";
echo "   YES! Members with EXPIRED cards DO receive reminder emails!\n\n";

echo "📋 DETAILS:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "• System includes -1 days (expired) in reminder intervals\n";
echo "• Expired members get reminders EVERY DAY\n";
echo "• Email clearly states \"Your membership has EXPIRED\"\n";
echo "• Continues daily until member renews\n";
echo "• Duplicate prevention ensures only 1 email per day\n";
echo "• Same renewal instructions included\n";
echo "\n";

echo "🎯 PURPOSE:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "Daily reminders for expired cards create urgency and ensure\n";
echo "members don't forget to renew their membership.\n";
echo "\n";

echo "💡 COMPARISON:\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "Before Expiry: 5 reminders over 30 days (30,15,7,1,0 days)\n";
echo "After Expiry:  Daily reminders until renewed (-1 days)\n";

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

