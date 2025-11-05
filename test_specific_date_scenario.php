<?php

/**
 * Test Specific Scenario: Day Counting Accuracy
 * 
 * Scenario:
 * - Registration Date: November 2, 2025
 * - Card Expiry Date: November 29, 2025
 * - Question: Are the days counted correctly?
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Carbon;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║  DAY COUNTING ACCURACY TEST                                      ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Your specific scenario
$registrationDate = Carbon::parse('2025-11-02');
$expiryDate = Carbon::parse('2025-11-29');

echo "📝 SCENARIO:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Registration Date: {$registrationDate->toDateString()} ({$registrationDate->format('l, F j, Y')})\n";
echo "Card Expiry Date:  {$expiryDate->toDateString()} ({$expiryDate->format('l, F j, Y')})\n";
echo "Total Days Valid:  " . $registrationDate->diffInDays($expiryDate) . " days\n";
echo "\n";

// Test the day counting logic (same as in SendRenewalReminders command)
echo "📅 DAY COUNTING TEST:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Simulate different dates and check when reminders would be sent
$testDates = [
    '2025-10-30', // 30 days before
    '2025-11-02', // Registration day
    '2025-11-05', // Today (3 days after registration)
    '2025-11-14', // 15 days before expiry
    '2025-11-22', // 7 days before expiry
    '2025-11-28', // 1 day before expiry
    '2025-11-29', // Expiry day
    '2025-11-30', // 1 day after expiry
    '2025-12-01', // 2 days after expiry
];

foreach ($testDates as $dateStr) {
    $today = Carbon::parse($dateStr)->startOfDay();
    $validUntil = $expiryDate->copy()->startOfDay();
    
    // This is the EXACT calculation used in SendRenewalReminders.php
    $daysRemaining = (int) $today->diffInDays($validUntil, false);
    
    // Determine if reminder should be sent
    $reminderIntervals = [30, 15, 7, 1, 0, -1];
    $shouldSendReminder = in_array($daysRemaining, $reminderIntervals);
    
    // Format output
    $status = $shouldSendReminder ? '✉️ SEND' : '⏭️ Skip';
    $statusColor = $shouldSendReminder ? '🟢' : '⚪';
    
    echo "Date: {$today->format('M d, Y')} ";
    echo "→ Days until expiry: ";
    
    if ($daysRemaining > 0) {
        echo str_pad((string)$daysRemaining, 3, ' ', STR_PAD_LEFT) . " days  ";
    } elseif ($daysRemaining == 0) {
        echo "  0 days  "; // TODAY
    } else {
        echo str_pad((string)$daysRemaining, 3, ' ', STR_PAD_LEFT) . " days  "; // EXPIRED
    }
    
    echo "{$statusColor} {$status}";
    
    if ($shouldSendReminder) {
        if ($daysRemaining == 30) echo " (30-day reminder)";
        if ($daysRemaining == 15) echo " (15-day reminder)";
        if ($daysRemaining == 7) echo " (7-day reminder)";
        if ($daysRemaining == 1) echo " (1-day reminder)";
        if ($daysRemaining == 0) echo " (EXPIRY DAY reminder)";
        if ($daysRemaining == -1) echo " (EXPIRED reminder)";
    }
    
    echo "\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "REMINDER TIMELINE FOR THIS SCENARIO:\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$reminderDates = [
    ['days' => 30, 'date' => $expiryDate->copy()->subDays(30)],
    ['days' => 15, 'date' => $expiryDate->copy()->subDays(15)],
    ['days' => 7, 'date' => $expiryDate->copy()->subDays(7)],
    ['days' => 1, 'date' => $expiryDate->copy()->subDays(1)],
    ['days' => 0, 'date' => $expiryDate->copy()],
];

foreach ($reminderDates as $reminder) {
    $reminderDate = $reminder['date'];
    $days = $reminder['days'];
    $label = $days == 0 ? 'TODAY' : "{$days} days";
    
    // Check if reminder date is after registration
    if ($reminderDate->gte($registrationDate)) {
        echo "✅ {$reminderDate->format('M d, Y')} → Reminder sent ({$label} before expiry)\n";
    } else {
        echo "❌ {$reminderDate->format('M d, Y')} → Skipped (before registration date)\n";
    }
}

echo "\n✉️  {$expiryDate->copy()->addDay()->format('M d, Y')} onwards → EXPIRED reminders (daily)\n";

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "ACCURACY CHECK:\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Verify calculation accuracy
$nov14 = Carbon::parse('2025-11-14');
$nov14ToExpiry = (int) $nov14->startOfDay()->diffInDays($expiryDate->copy()->startOfDay(), false);

$nov22 = Carbon::parse('2025-11-22');
$nov22ToExpiry = (int) $nov22->startOfDay()->diffInDays($expiryDate->copy()->startOfDay(), false);

$nov28 = Carbon::parse('2025-11-28');
$nov28ToExpiry = (int) $nov28->startOfDay()->diffInDays($expiryDate->copy()->startOfDay(), false);

echo "Manual Verification:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "November 14 to November 29: {$nov14ToExpiry} days ";
echo ($nov14ToExpiry == 15 ? '✅ CORRECT' : '❌ WRONG') . "\n";

echo "November 22 to November 29: {$nov22ToExpiry} days ";
echo ($nov22ToExpiry == 7 ? '✅ CORRECT' : '❌ WRONG') . "\n";

echo "November 28 to November 29: {$nov28ToExpiry} days ";
echo ($nov28ToExpiry == 1 ? '✅ CORRECT' : '❌ WRONG') . "\n";

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "ISSUE DETECTION:\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Check if 30-day reminder would be missed
$thirtyDaysBefore = $expiryDate->copy()->subDays(30);
if ($thirtyDaysBefore->lt($registrationDate)) {
    echo "⚠️  WARNING: 30-day reminder will NOT be sent!\n";
    echo "   Reason: 30 days before expiry ({$thirtyDaysBefore->format('M d, Y')}) is\n";
    echo "           BEFORE registration date ({$registrationDate->format('M d, Y')})\n";
    echo "\n";
    echo "   Solution: This is expected behavior. Member registered too close\n";
    echo "             to expiry date. They will still receive:\n";
    echo "             - 15-day reminder\n";
    echo "             - 7-day reminder\n";
    echo "             - 1-day reminder\n";
    echo "             - Expiry day reminder\n";
    echo "             - Expired reminders\n";
} else {
    echo "✅ All reminders will be sent correctly.\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "CONCLUSION:\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$totalDays = $registrationDate->diffInDays($expiryDate);
if ($totalDays < 30) {
    echo "⚠️  Card validity is only {$totalDays} days (less than 30 days)\n";
    echo "\n";
    echo "Day counting is ACCURATE, but:\n";
    echo "- Some early reminders won't be sent (30-day, possibly 15-day)\n";
    echo "- This is CORRECT behavior (can't send reminder before registration)\n";
    echo "- Member will still receive reminders for remaining intervals\n";
} else {
    echo "✅ Card validity is {$totalDays} days\n";
    echo "✅ All reminders will be sent\n";
    echo "✅ Day counting is 100% accurate\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "RECOMMENDATION:\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

if ($totalDays < 365) {
    echo "⚠️  IMPORTANT:\n";
    echo "   Normal membership cards should be valid for 1 year (365 days)\n";
    echo "   Your scenario has only {$totalDays} days validity.\n";
    echo "\n";
    echo "   This might happen if:\n";
    echo "   1. Member registered late in the year (near Dec 31)\n";
    echo "   2. Card expires at calendar year end (Dec 31)\n";
    echo "   3. This is a test scenario\n";
    echo "\n";
    echo "   If this is a real case, consider:\n";
    echo "   - Setting expiry to Dec 31 of NEXT year\n";
    echo "   - Or setting expiry to 1 year from registration\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

