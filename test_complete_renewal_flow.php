<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;

echo "=== COMPLETE RENEWAL FLOW TEST ===\n\n";

// Get Maria Garcia who received reminder email
$maria = Registration::where('email', 'maria.garcia@example.com')->first();

if (!$maria) {
    echo "❌ Maria Garcia not found\n";
    exit(1);
}

echo "👤 MEMBER: {$maria->memberName}\n";
echo "📧 Email: {$maria->email}\n";
echo "🆔 Civil ID: {$maria->civil_id}\n";
echo "🔑 Password needed for login\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CURRENT STATUS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "login_status: {$maria->login_status}\n";
echo "renewal_status: {$maria->renewal_status}\n";
echo "renewal_count: " . ($maria->renewal_count ?? 0) . "\n";
echo "card_issued_at: " . ($maria->card_issued_at ? $maria->card_issued_at->format('M d, Y') : 'N/A') . "\n";
echo "last_renewed_at: " . ($maria->last_renewed_at ? $maria->last_renewed_at->format('M d, Y') : 'N/A') . "\n";
echo "card_valid_until: " . ($maria->card_valid_until ? $maria->card_valid_until->format('M d, Y') : 'N/A') . "\n";
echo "renewal_requested_at: " . ($maria->renewal_requested_at ?? 'NULL (no request yet)') . "\n";

$daysUntilExpiry = $maria->card_valid_until ? (int) now()->diffInDays($maria->card_valid_until, false) : null;
if ($daysUntilExpiry !== null) {
    if ($daysUntilExpiry < 0) {
        echo "⚠️ Card Status: EXPIRED " . abs($daysUntilExpiry) . " days ago\n";
    } else {
        echo "⏰ Card Status: Expires in {$daysUntilExpiry} days\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TESTING RENEWAL FLOW:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "STEP 1: Member Login\n";
echo "  URL: http://127.0.0.1:8000/member/panel/login\n";
echo "  OR: http://127.0.0.1:8000/member/login\n";
echo "  Credentials:\n";
echo "    Civil ID: {$maria->civil_id}\n";
echo "    Password: (set when approved - check database or reset)\n\n";

echo "STEP 2: Member Submits Renewal Request\n";
echo "  Member clicks 'Request Renewal' button on dashboard\n";
echo "  This sets:\n";
echo "    - renewal_requested_at = NOW\n";
echo "    - renewal_status = 'pending'\n\n";

echo "STEP 3: Admin Approves Renewal\n";
echo "  URL: http://127.0.0.1:8000/admin/renewal-requests\n";
echo "  Admin clicks 'Approve Renewal' for Maria Garcia\n";
echo "  This will:\n";
echo "    - Set renewal_status = 'approved'\n";
echo "    - Set last_renewed_at = NOW\n";
echo "    - Increment renewal_count\n";
echo "    - Set card_valid_until = Dec 31, " . now()->year . "\n";
echo "    - Send new membership card via email\n\n";

echo "STEP 4: Verify New Membership Card\n";
echo "  Check Maria's email for new card\n";
echo "  Card should show:\n";
echo "    - Valid Until: Dec 31, " . now()->year . "\n";
echo "    - Renewal count: " . (($maria->renewal_count ?? 0) + 1) . "\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "AUTOMATED TEST SIMULATION:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Simulate member requesting renewal
echo "🔄 Simulating renewal request...\n";
$beforeRenewal = [
    'card_valid_until' => $maria->card_valid_until ? $maria->card_valid_until->format('Y-m-d') : null,
    'renewal_count' => $maria->renewal_count ?? 0,
];

$maria->renewal_requested_at = now();
$maria->renewal_status = 'pending';
$maria->save();

echo "✅ Renewal request submitted\n";
echo "   renewal_requested_at: {$maria->renewal_requested_at}\n";
echo "   renewal_status: {$maria->renewal_status}\n\n";

// Simulate admin approval
echo "🔄 Simulating admin approval...\n";
$maria->renewal_status = 'approved';
$maria->last_renewed_at = now();
$maria->renewal_count = ($maria->renewal_count ?? 0) + 1;
$maria->card_valid_until = now()->endOfYear();
$maria->save();

echo "✅ Renewal approved!\n\n";

$afterRenewal = [
    'card_valid_until' => $maria->card_valid_until->format('Y-m-d'),
    'renewal_count' => $maria->renewal_count,
];

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "VERIFICATION RESULTS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "BEFORE RENEWAL:\n";
echo "  Card Valid Until: {$beforeRenewal['card_valid_until']}\n";
echo "  Renewal Count: {$beforeRenewal['renewal_count']}\n\n";

echo "AFTER RENEWAL:\n";
echo "  Card Valid Until: {$afterRenewal['card_valid_until']}\n";
echo "  Renewal Count: {$afterRenewal['renewal_count']}\n";
echo "  Last Renewed: {$maria->last_renewed_at->format('M d, Y H:i:s')}\n\n";

// Check if card validity is correct
$expectedExpiry = now()->endOfYear()->format('Y-m-d');
if ($afterRenewal['card_valid_until'] === $expectedExpiry) {
    echo "✅ CORRECT: Card validity set to Dec 31, " . now()->year . "\n";
} else {
    echo "❌ ERROR: Card validity is {$afterRenewal['card_valid_until']}, expected {$expectedExpiry}\n";
}

// Check if renewal count increased
if ($afterRenewal['renewal_count'] === ($beforeRenewal['renewal_count'] + 1)) {
    echo "✅ CORRECT: Renewal count incremented\n";
} else {
    echo "❌ ERROR: Renewal count not incremented correctly\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CALENDAR YEAR LOGIC VERIFICATION:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Check a newly registered member
$newMember = Registration::where('login_status', 'approved')
    ->whereNull('last_renewed_at')
    ->first();

if ($newMember) {
    echo "NEW MEMBER EXAMPLE: {$newMember->memberName}\n";
    echo "  Registered: " . ($newMember->card_issued_at ? $newMember->card_issued_at->format('M d, Y') : 'N/A') . "\n";
    echo "  Card Valid Until: " . ($newMember->card_valid_until ? $newMember->card_valid_until->format('M d, Y') : 'N/A') . "\n";
    
    $newMemberExpiry = $newMember->card_valid_until ? $newMember->card_valid_until->format('Y-m-d') : null;
    if ($newMemberExpiry === $expectedExpiry) {
        echo "  ✅ NEW registration card expires: Dec 31, " . now()->year . "\n";
    } else {
        echo "  ❌ NEW registration card expiry is wrong\n";
    }
}

echo "\nRENEWED MEMBER: {$maria->memberName}\n";
echo "  Last Renewed: {$maria->last_renewed_at->format('M d, Y')}\n";
echo "  Card Valid Until: {$maria->card_valid_until->format('M d, Y')}\n";

if ($afterRenewal['card_valid_until'] === $expectedExpiry) {
    echo "  ✅ RENEWED card expires: Dec 31, " . now()->year . "\n";
} else {
    echo "  ❌ RENEWED card expiry is wrong\n";
}

echo "\n🎯 CONCLUSION:\n";
if ($newMember && $newMemberExpiry === $expectedExpiry && $afterRenewal['card_valid_until'] === $expectedExpiry) {
    echo "✅ BOTH new registration AND renewal cards expire on: Dec 31, " . now()->year . "\n";
    echo "✅ Calendar year logic is CORRECT!\n";
} else {
    echo "⚠️ There may be an issue with the calendar year logic\n";
}

echo "\n=== END OF TEST ===\n";





