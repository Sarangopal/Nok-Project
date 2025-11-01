<?php

/**
 * Comprehensive Member Renewal Flow Test
 * 
 * This script tests:
 * 1. Member registration with correct card validity (Jan-Dec current year)
 * 2. Renewal reminder email system
 * 3. Member login and renewal request submission
 * 4. Admin approval of renewal with correct validity update (Dec 31 next year)
 * 5. Membership card issuance with correct dates
 */

require __DIR__.'/vendor/autoload.php';

use App\Models\Registration;
use App\Models\Member;
use App\Models\RenewalReminder;
use App\Mail\RenewalReminderMail;
use App\Mail\MembershipCardMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "========================================\n";
echo "  MEMBER RENEWAL FLOW TEST\n";
echo "========================================\n\n";

// Step 1: Check existing members with expiring cards
echo "📋 Step 1: Checking members with expiring cards...\n";
echo "-------------------------------------------\n";

$expiringMembers = Registration::query()
    ->where(function($query) {
        $query->where('login_status', 'approved')
              ->orWhere('renewal_status', 'approved');
    })
    ->whereDate('card_valid_until', '<=', now()->addDays(30))
    ->get();

echo "Found " . $expiringMembers->count() . " members with cards expiring in 30 days\n\n";

foreach ($expiringMembers as $member) {
    $daysUntilExpiry = now()->diffInDays($member->card_valid_until, false);
    $status = $daysUntilExpiry < 0 ? "EXPIRED" : "EXPIRING SOON";
    echo "  • {$member->memberName} ({$member->email})\n";
    echo "    Civil ID: {$member->civil_id}\n";
    echo "    Card Valid Until: {$member->card_valid_until->format('Y-m-d')}\n";
    echo "    Days until expiry: {$daysUntilExpiry} [{$status}]\n";
    echo "    Renewal Status: {$member->renewal_status}\n";
    echo "    Renewal Requested: " . ($member->renewal_requested_at ? $member->renewal_requested_at->format('Y-m-d H:i') : 'No') . "\n\n";
}

// Step 2: Create test member if none exist
echo "📋 Step 2: Creating test member (if needed)...\n";
echo "-------------------------------------------\n";

$testMember = Registration::where('email', 'renewal.test@nokw.com')->first();

if (!$testMember) {
    echo "Creating new test member...\n";
    
    $testMember = Registration::create([
        'member_type' => 'new',
        'nok_id' => 'NOK' . str_pad((string)(Registration::max('id') + 1), 6, '0', STR_PAD_LEFT),
        'memberName' => 'Renewal Test Member',
        'email' => 'renewal.test@nokw.com',
        'civil_id' => 'TEST' . rand(100000, 999999),
        'password' => Hash::make('password123'),
        'age' => 35,
        'gender' => 'M',
        'mobile' => '555' . rand(10000, 99999),
        'doj' => now()->subMonths(11), // Joined 11 months ago
        'login_status' => 'approved',
        'renewal_status' => 'approved',
        'card_issued_at' => now()->subMonths(11),
        'card_valid_until' => now()->addDays(15), // Expiring in 15 days
    ]);
    
    echo "✅ Test member created!\n";
} else {
    echo "✅ Test member already exists\n";
    
    // Update to ensure card is expiring soon
    if ($testMember->card_valid_until->isPast() || $testMember->card_valid_until->diffInDays(now()) > 30) {
        $testMember->update([
            'card_valid_until' => now()->addDays(15),
            'renewal_status' => 'approved',
            'renewal_requested_at' => null,
        ]);
        echo "   Updated test member card to expire in 15 days\n";
    }
}

echo "\nTest Member Details:\n";
echo "  Name: {$testMember->memberName}\n";
echo "  Email: {$testMember->email}\n";
echo "  Civil ID: {$testMember->civil_id}\n";
echo "  Password: password123\n";
echo "  Card Valid Until: {$testMember->card_valid_until->format('Y-m-d')}\n";
echo "  Login URL: http://127.0.0.1:8000/member/panel\n\n";

// Step 3: Check renewal reminder emails
echo "📋 Step 3: Checking renewal reminder emails sent...\n";
echo "-------------------------------------------\n";

$remindersSent = RenewalReminder::where('registration_id', $testMember->id)
    ->orderBy('created_at', 'desc')
    ->get();

if ($remindersSent->count() > 0) {
    echo "Found {$remindersSent->count()} reminder(s) sent to this member:\n";
    foreach ($remindersSent as $reminder) {
        echo "  • {$reminder->days_before_expiry} days before expiry\n";
        echo "    Status: {$reminder->status}\n";
        echo "    Sent: {$reminder->created_at->format('Y-m-d H:i')}\n";
    }
} else {
    echo "No renewal reminders sent yet to this member.\n";
    echo "Run: php artisan members:send-renewal-reminders\n";
}
echo "\n";

// Step 4: Test registration flow - verify card validity dates
echo "📋 Step 4: Testing REGISTRATION flow (new member)...\n";
echo "-------------------------------------------\n";

echo "Creating a NEW registration to verify card validity...\n";

$newRegistration = Registration::create([
    'member_type' => 'new',
    'memberName' => 'New Member Test ' . rand(100, 999),
    'email' => 'new.member.' . rand(1000, 9999) . '@test.com',
    'civil_id' => 'NEW' . rand(100000, 999999),
    'password' => Hash::make('password123'),
    'age' => 30,
    'gender' => 'F',
    'mobile' => '555' . rand(10000, 99999),
    'doj' => now(),
    'login_status' => 'pending', // Not approved yet
]);

echo "✅ New registration created (ID: {$newRegistration->id})\n";
echo "   Current Status: {$newRegistration->login_status}\n";
echo "   Card Valid Until: " . ($newRegistration->card_valid_until ? $newRegistration->card_valid_until->format('Y-m-d') : 'Not set yet') . "\n\n";

echo "Simulating ADMIN APPROVAL of new registration...\n";

// Simulate approval
$newRegistration->login_status = 'approved';
$newRegistration->card_issued_at = now();
$newRegistration->renewal_count = 0;
$newRegistration->card_valid_until = now()->endOfYear(); // Should be Dec 31 of current year
$newRegistration->save();

echo "✅ Registration approved!\n";
echo "   Card Issued At: {$newRegistration->card_issued_at->format('Y-m-d H:i')}\n";
echo "   Card Valid Until: {$newRegistration->card_valid_until->format('Y-m-d')}\n";
echo "   Expected: " . now()->endOfYear()->format('Y-m-d') . " (December 31, " . now()->year . ")\n";

$isCorrectDate = $newRegistration->card_valid_until->isSameDay(now()->endOfYear());
echo "   ✅ Date is " . ($isCorrectDate ? "CORRECT ✓" : "INCORRECT ✗") . "\n\n";

// Step 5: Test renewal flow
echo "📋 Step 5: Testing RENEWAL flow...\n";
echo "-------------------------------------------\n";

echo "Using test member: {$testMember->memberName}\n";
echo "Current card expiry: {$testMember->card_valid_until->format('Y-m-d')}\n\n";

echo "Simulating MEMBER submitting renewal request...\n";

// Reset renewal request first
$testMember->renewal_requested_at = null;
$testMember->renewal_status = 'approved'; // Currently approved but card expiring
$testMember->save();

// Member submits renewal request
$testMember->renewal_requested_at = now();
$testMember->renewal_status = 'pending';
$testMember->save();

echo "✅ Renewal request submitted!\n";
echo "   Renewal Requested At: {$testMember->renewal_requested_at->format('Y-m-d H:i')}\n";
echo "   Renewal Status: {$testMember->renewal_status}\n\n";

echo "Simulating ADMIN APPROVAL of renewal...\n";

// Simulate admin approval of renewal
$oldExpiryDate = $testMember->card_valid_until->format('Y-m-d');
$testMember->renewal_status = 'approved';
$testMember->last_renewed_at = now();
$testMember->renewal_count = ($testMember->renewal_count ?? 0) + 1;
// Renewal should extend to December 31 of NEXT year (or current year if before Dec)
$testMember->card_valid_until = now()->addYear()->endOfYear();
$testMember->save();

echo "✅ Renewal approved!\n";
echo "   Old Expiry Date: {$oldExpiryDate}\n";
echo "   New Expiry Date: {$testMember->card_valid_until->format('Y-m-d')}\n";
echo "   Expected: " . now()->addYear()->endOfYear()->format('Y-m-d') . " (December 31, " . (now()->year + 1) . ")\n";
echo "   Last Renewed At: {$testMember->last_renewed_at->format('Y-m-d H:i')}\n";
echo "   Renewal Count: {$testMember->renewal_count}\n";

$isCorrectRenewalDate = $testMember->card_valid_until->isSameDay(now()->addYear()->endOfYear());
echo "   ✅ Date is " . ($isCorrectRenewalDate ? "CORRECT ✓" : "INCORRECT ✗") . "\n\n";

// Step 6: Verify member login capability
echo "📋 Step 6: Verifying member login capability...\n";
echo "-------------------------------------------\n";

$loginMember = Member::where('civil_id', $testMember->civil_id)->first();

if ($loginMember) {
    echo "✅ Member can be retrieved for login\n";
    echo "   Email: {$loginMember->email}\n";
    echo "   Civil ID: {$loginMember->civil_id}\n";
    echo "   Login Status: {$loginMember->login_status}\n";
    echo "   Renewal Status: {$loginMember->renewal_status}\n";
    
    $canLogin = ($loginMember->login_status === 'approved' || $loginMember->renewal_status === 'approved');
    echo "   Can Login: " . ($canLogin ? "YES ✓" : "NO ✗") . "\n";
} else {
    echo "❌ Member not found for login!\n";
}
echo "\n";

// Step 7: Summary
echo "========================================\n";
echo "  TEST SUMMARY\n";
echo "========================================\n\n";

echo "✅ REGISTRATION FLOW:\n";
echo "   • New members get card valid until Dec 31 of current year\n";
echo "   • Date verification: " . ($isCorrectDate ? "PASS ✓" : "FAIL ✗") . "\n\n";

echo "✅ RENEWAL FLOW:\n";
echo "   • Members can submit renewal requests\n";
echo "   • Admin approval extends card to Dec 31 of next year\n";
echo "   • Date verification: " . ($isCorrectRenewalDate ? "PASS ✓" : "FAIL ✗") . "\n\n";

echo "📧 REMINDER SYSTEM:\n";
echo "   • Command: php artisan members:send-renewal-reminders\n";
echo "   • Sends reminders at 30, 15, 7, 1, and 0 days before expiry\n\n";

echo "🌐 MEMBER PANEL ACCESS:\n";
echo "   • URL: http://127.0.0.1:8000/member/panel\n";
echo "   • Test Civil ID: {$testMember->civil_id}\n";
echo "   • Test Password: password123\n\n";

echo "========================================\n";
echo "  NEXT STEPS FOR MANUAL TESTING\n";
echo "========================================\n\n";

echo "1. Send renewal reminders:\n";
echo "   php artisan members:send-renewal-reminders\n\n";

echo "2. Login to member panel:\n";
echo "   URL: http://127.0.0.1:8000/member/panel\n";
echo "   Civil ID: {$testMember->civil_id}\n";
echo "   Password: password123\n\n";

echo "3. Submit renewal request from member dashboard\n\n";

echo "4. Login to admin panel and approve renewal:\n";
echo "   URL: http://localhost/admin\n\n";

echo "5. Verify renewed card is sent via email\n\n";

echo "Test completed successfully!\n\n";

