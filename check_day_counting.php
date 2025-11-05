<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Carbon;

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  DAY COUNTING VERIFICATION - Your Specific Scenario\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Your scenario
$registrationDate = Carbon::parse('2025-11-02');
$expiryDate = Carbon::parse('2025-11-29');
$today = Carbon::today();

echo "📅 SCENARIO:\n";
echo "   Registration Date: {$registrationDate->format('M d, Y')} (November 2, 2025)\n";
echo "   Card Expiry Date:  {$expiryDate->format('M d, Y')} (November 29, 2025)\n";
echo "   Today's Date:      {$today->format('M d, Y')}\n";
echo "   Total Card Days:   " . $registrationDate->diffInDays($expiryDate) . " days\n\n";

echo "═══════════════════════════════════════════════════════════════════\n";
echo "  WHEN WILL REMINDERS BE SENT?\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$reminders = [
    ['days' => 30, 'label' => '30 Days Before'],
    ['days' => 15, 'label' => '15 Days Before'],
    ['days' => 7, 'label' => '7 Days Before'],
    ['days' => 1, 'label' => '1 Day Before'],
    ['days' => 0, 'label' => 'Expiry Day'],
];

foreach ($reminders as $reminder) {
    $days = $reminder['days'];
    $label = $reminder['label'];
    $reminderDate = $expiryDate->copy()->subDays($days);
    
    // Check if reminder date is after registration
    if ($reminderDate->gte($registrationDate)) {
        // Calculate days remaining when checking from reminder date
        $daysFromReminderToExpiry = (int) $reminderDate->copy()->startOfDay()->diffInDays($expiryDate->copy()->startOfDay(), false);
        
        echo "✅ {$reminderDate->format('M d, Y')} ({$reminderDate->format('l')})\n";
        echo "   → {$label} reminder\n";
        echo "   → System will calculate: {$daysFromReminderToExpiry} days remaining\n";
        echo "   → Reminder WILL BE SENT ✉️\n\n";
    } else {
        echo "❌ {$reminderDate->format('M d, Y')} ({$reminderDate->format('l')})\n";
        echo "   → {$label} reminder\n";
        echo "   → This date is BEFORE registration date!\n";
        echo "   → Reminder will be SKIPPED (member not yet registered)\n\n";
    }
}

echo "✉️  {$expiryDate->copy()->addDay()->format('M d, Y')} onwards\n";
echo "   → EXPIRED reminders (sent daily after expiry)\n\n";

echo "═══════════════════════════════════════════════════════════════════\n";
echo "  DAY COUNTING ACCURACY TEST\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Test the exact logic from SendRenewalReminders.php
$testCases = [
    ['date' => '2025-11-14', 'expected' => 15],
    ['date' => '2025-11-22', 'expected' => 7],
    ['date' => '2025-11-28', 'expected' => 1],
    ['date' => '2025-11-29', 'expected' => 0],
    ['date' => '2025-11-30', 'expected' => -1],
];

echo "Testing the EXACT calculation used in SendRenewalReminders.php:\n\n";

$allCorrect = true;
foreach ($testCases as $test) {
    $checkDate = Carbon::parse($test['date'])->startOfDay();
    $validUntil = $expiryDate->copy()->startOfDay();
    
    // THIS IS THE EXACT CALCULATION FROM YOUR CODE
    $calculated = (int) $checkDate->diffInDays($validUntil, false);
    $expected = $test['expected'];
    
    $isCorrect = ($calculated === $expected);
    $status = $isCorrect ? '✅' : '❌';
    
    echo "{$status} Date: {$checkDate->format('M d, Y')}\n";
    echo "   Expected: {$expected} days\n";
    echo "   Calculated: {$calculated} days\n";
    echo "   Result: " . ($isCorrect ? 'CORRECT ✅' : 'WRONG ❌') . "\n\n";
    
    if (!$isCorrect) $allCorrect = false;
}

echo "═══════════════════════════════════════════════════════════════════\n";
echo "  CONCLUSION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

if ($allCorrect) {
    echo "✅ DAY COUNTING IS 100% ACCURATE!\n\n";
    echo "The system correctly calculates:\n";
    echo "  • 15 days before expiry = November 14\n";
    echo "  • 7 days before expiry = November 22\n";
    echo "  • 1 day before expiry = November 28\n";
    echo "  • Expiry day = November 29\n";
    echo "  • Expired = November 30 onwards\n\n";
} else {
    echo "❌ ISSUE FOUND IN DAY COUNTING!\n\n";
}

// Important note about 30-day reminder
$thirtyDaysBefore = $expiryDate->copy()->subDays(30);
if ($thirtyDaysBefore->lt($registrationDate)) {
    echo "⚠️  IMPORTANT NOTE:\n";
    echo "   The 30-day reminder would be on {$thirtyDaysBefore->format('M d, Y')},\n";
    echo "   which is BEFORE the registration date ({$registrationDate->format('M d, Y')}).\n\n";
    echo "   This means:\n";
    echo "   • The 30-day reminder will NOT be sent (correct behavior)\n";
    echo "   • Only 15, 7, 1, 0, and -1 day reminders will be sent\n";
    echo "   • This is EXPECTED when card validity is less than 30 days\n\n";
}

// Check current system behavior
$totalDays = $registrationDate->diffInDays($expiryDate);
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  YOUR SYSTEM BEHAVIOR\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "Card Validity Period: {$totalDays} days\n\n";

if ($totalDays < 30) {
    echo "⚠️  Your scenario has LESS than 30 days validity.\n\n";
    echo "This means:\n";
    echo "  • 30-day reminder: ❌ Will NOT be sent\n";
    echo "  • 15-day reminder: ✅ Will be sent (Nov 14)\n";
    echo "  • 7-day reminder:  ✅ Will be sent (Nov 22)\n";
    echo "  • 1-day reminder:  ✅ Will be sent (Nov 28)\n";
    echo "  • Expiry reminder: ✅ Will be sent (Nov 29)\n";
    echo "  • Expired reminder: ✅ Will be sent (Nov 30+)\n\n";
    
    echo "📌 RECOMMENDATION:\n";
    echo "   Normally, membership cards should be valid for 1 year.\n";
    echo "   Your current system sets expiry to December 31 of current year.\n\n";
    
    echo "   For member registering on November 2, 2025:\n";
    $normalExpiry = Carbon::parse('2025-11-02')->endOfYear();
    echo "   • Expiry should be: {$normalExpiry->format('M d, Y')} (Dec 31, 2025)\n";
    echo "   • That would be: " . Carbon::parse('2025-11-02')->diffInDays($normalExpiry) . " days of validity\n";
    echo "   • Then ALL reminders (30, 15, 7, 1, 0) can be sent\n";
} else {
    echo "✅ Card has sufficient validity ({$totalDays} days).\n";
    echo "   All reminders will be sent correctly.\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

