<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;
use Illuminate\Support\Carbon;

echo "🔍 Testing Renewal Workflow...\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Step 1: Checking members with expiring cards\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Find members whose cards are expiring within 30 days
$expiringMembers = Registration::where('login_status', 'approved')
    ->where('renewal_status', 'approved')
    ->whereNotNull('card_valid_until')
    ->whereDate('card_valid_until', '<=', now()->addDays(30))
    ->get();

echo "Found {$expiringMembers->count()} members with cards expiring within 30 days:\n\n";

foreach ($expiringMembers as $member) {
    $daysLeft = now()->diffInDays(Carbon::parse($member->card_valid_until), false);
    $status = $daysLeft < 0 ? '❌ EXPIRED' : "⚠️  EXPIRING ({$daysLeft} days)";
    
    echo "  • {$member->memberName} ({$member->email})\n";
    echo "    NOK ID: {$member->nok_id}\n";
    echo "    Card Expiry: {$member->card_valid_until}\n";
    echo "    Status: {$status}\n";
    echo "    Renewal Request: " . ($member->renewal_requested_at ? "✅ Submitted ({$member->renewal_requested_at})" : "❌ Not submitted") . "\n";
    echo "    Renewal Status: {$member->renewal_status}\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📝 Step 2: Checking Renewal Requests page\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Find members who submitted renewal requests
$renewalRequests = Registration::whereNotNull('renewal_requested_at')
    ->orderBy('renewal_requested_at', 'desc')
    ->get();

echo "Found {$renewalRequests->count()} renewal requests:\n\n";

foreach ($renewalRequests as $request) {
    $isPending = $request->renewal_status === 'pending';
    $statusIcon = $isPending ? '🟡 PENDING' : '🟢 APPROVED';
    
    echo "  • {$request->memberName} ({$request->email})\n";
    echo "    Requested At: {$request->renewal_requested_at}\n";
    echo "    Status: {$statusIcon}\n";
    echo "    Payment Proof: " . ($request->renewal_payment_proof ? "✅ Uploaded" : "❌ No file") . "\n\n";
}

if ($renewalRequests->count() === 0) {
    echo "  ⚠️  No renewal requests found!\n";
    echo "  💡 Members need to login to member portal and submit renewal requests.\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🧪 Step 3: Creating a test member with expiring card\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Clean up old test member
Registration::where('email', 'renewal_test@test.com')->delete();

// Create a test member with card expiring in 5 days
$testMember = Registration::create([
    'member_type' => 'new',
    'nok_id' => 'NOK999999',
    'memberName' => 'Renewal Test Member',
    'email' => 'renewal_test@test.com',
    'civil_id' => '999888777666',
    'password' => bcrypt('NOK1234'),
    'age' => 30,
    'gender' => 'Male',
    'mobile' => '+96599999999',
    'whatsapp' => '99999999',
    'address' => 'Test Address Kuwait',
    'department' => 'Testing',
    'job_title' => 'Test Nurse',
    'institution' => 'Test Hospital',
    'passport' => 'P9999999',
    'blood_group' => 'O+',
    'phone_india' => '9999999999',
    'nominee_name' => 'Test Nominee',
    'nominee_relation' => 'Spouse',
    'nominee_contact' => '9999999998',
    'guardian_name' => 'Test Guardian',
    'guardian_contact' => '9999999997',
    'bank_account_name' => 'Test Bank',
    'account_number' => '999999999',
    'ifsc_code' => 'TEST9999',
    'bank_branch' => 'Test Branch',
    'doj' => now()->subYear(),
    'card_issued_at' => now()->subYear(),
    'card_valid_until' => now()->addDays(5), // Expiring in 5 days
    'renewal_status' => 'approved',
    'login_status' => 'approved',
    'last_renewed_at' => now()->subYear(),
    'renewal_count' => 1,
    'renewal_requested_at' => null, // No renewal request yet
    'renewal_payment_proof' => null,
]);

echo "✅ Created test member:\n";
echo "   Name: {$testMember->memberName}\n";
echo "   Email: {$testMember->email}\n";
echo "   NOK ID: {$testMember->nok_id}\n";
echo "   Civil ID: {$testMember->civil_id}\n";
echo "   Card Expiry: {$testMember->card_valid_until} (5 days from now)\n";
echo "   Login Status: {$testMember->login_status}\n";
echo "   Renewal Status: {$testMember->renewal_status}\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎯 Step 4: What to test now\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "1️⃣  Login to Member Panel:\n";
echo "   URL: http://127.0.0.1:8000/member/panel/login\n";
echo "   Civil ID: {$testMember->civil_id}\n";
echo "   Password: NOK1234\n\n";

echo "2️⃣  Submit Renewal Request:\n";
echo "   - Click 'Request Renewal' button (should be RED/Orange)\n";
echo "   - Upload payment proof\n";
echo "   - Submit request\n\n";

echo "3️⃣  Check Admin Renewal Requests:\n";
echo "   URL: http://127.0.0.1:8000/admin/renewal-requests\n";
echo "   - Should see the test member's renewal request\n";
echo "   - Click 'Approve Renewal' button\n\n";

echo "4️⃣  Verify in Renewals Page:\n";
echo "   URL: http://127.0.0.1:8000/admin/renewals\n";
echo "   - Status should change to 'Renewed' (Green)\n";
echo "   - Card validity should extend to end of year\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Test setup complete!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

