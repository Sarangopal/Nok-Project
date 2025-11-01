<?php
/**
 * Renewal Logic Verification Script
 * 
 * This script demonstrates and verifies the renewal logic for NOK Kuwait membership cards.
 * 
 * Rule: When a member renews their card, the next card validity extends to
 *       December 31 of the year AFTER their current expiry year.
 * 
 * Usage: php verify_renewal_examples.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Registration;
use Carbon\Carbon;

echo "\n✅ RENEWAL LOGIC VERIFICATION - Real-World Examples\n";
echo str_repeat("=", 80) . "\n\n";

$examples = [
    [
        'description' => 'Member renews in 2024 (card expires Dec 31, 2024)',
        'current_expiry' => '2024-12-31',
        'renewal_year' => 2024,
        'expected' => '2025-12-31'
    ],
    [
        'description' => 'Member renews in 2025 (card expires Dec 31, 2025)',
        'current_expiry' => '2025-12-31',
        'renewal_year' => 2025,
        'expected' => '2026-12-31'
    ],
    [
        'description' => 'Member renews in 2026 (card expires Dec 31, 2026)',
        'current_expiry' => '2026-12-31',
        'renewal_year' => 2026,
        'expected' => '2027-12-31'
    ],
    [
        'description' => 'Member renewed early in 2025 (current expiry still 2026)',
        'current_expiry' => '2026-12-31',
        'renewal_year' => 2025,
        'expected' => '2027-12-31'
    ],
];

$allPassed = true;

foreach ($examples as $i => $example) {
    echo "EXAMPLE " . ($i + 1) . ": {$example['description']}\n";
    echo str_repeat("-", 80) . "\n";
    
    $member = new Registration();
    $member->card_valid_until = Carbon::parse($example['current_expiry']);
    
    $newValidity = $member->computeCalendarYearValidity(null, true);
    $actual = $newValidity->format('Y-m-d');
    $expected = $example['expected'];
    $passed = ($actual === $expected);
    $status = $passed ? '✅ PASS' : '❌ FAIL';
    
    if (!$passed) {
        $allPassed = false;
    }
    
    echo "  Current Expiry: {$example['current_expiry']}\n";
    echo "  Renewal Year: {$example['renewal_year']}\n";
    echo "  Expected New Expiry: {$expected}\n";
    echo "  Actual New Expiry: {$actual}\n";
    echo "  Result: {$status}\n\n";
}

echo str_repeat("=", 80) . "\n";
echo "📋 RENEWAL RULE SUMMARY:\n";
echo str_repeat("=", 80) . "\n\n";
echo "When a member renews their card, the next card validity extends to:\n";
echo "  → December 31 of the year AFTER their current expiry year\n\n";
echo "Implementation:\n";
echo "  → Current expiry date + 1 year → End of year\n";
echo "  → This automatically handles both regular and early renewals\n\n";

if ($allPassed) {
    echo "✅ All tests PASSED! Renewal logic is working correctly.\n\n";
    exit(0);
} else {
    echo "❌ Some tests FAILED! Please check the implementation.\n\n";
    exit(1);
}
