<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;

echo "🗑️  Deleting old 'Expired Test Member'...\n";
Registration::where('email', 'expired@test.com')->delete();

echo "✅ Deleted\n\n";

echo "➕ Creating NEW pending member...\n";

$member = Registration::create([
    'member_type' => 'new',
    'memberName' => 'Pending Test User',
    'email' => 'pending@test.com',
    'civil_id' => '888777666555',
    'age' => 28,
    'gender' => 'Male',
    'mobile' => '+96588888888',
    'whatsapp' => '88888888',
    'address' => 'Kuwait City Test',
    'department' => 'Emergency',
    'job_title' => 'Staff Nurse',
    'institution' => 'Test Hospital',
    'passport' => 'P8888888',
    'blood_group' => 'A+',
    'phone_india' => '8888888888',
    'nominee_name' => 'Test Nominee',
    'nominee_relation' => 'Sibling',
    'nominee_contact' => '8888888887',
    'guardian_name' => 'Test Guardian',
    'guardian_contact' => '8888888886',
    'bank_account_name' => 'Test Bank Account',
    'account_number' => '888888888',
    'ifsc_code' => 'TEST0888',
    'bank_branch' => 'Kuwait Branch',
    'doj' => now(),
    'login_status' => 'pending',      // NEW REGISTRATION - PENDING
    'renewal_status' => 'pending',    // Default value (will be used for renewals later)
    'card_issued_at' => null,         // Card not issued yet
    'card_valid_until' => null,       // No validity yet
]);

echo "✅ Created new pending member:\n";
echo "   Name: {$member->memberName}\n";
echo "   Email: {$member->email}\n";
echo "   Civil ID: {$member->civil_id}\n";
echo "   Login Status: {$member->login_status}\n";
echo "   Card Issued: " . ($member->card_issued_at ?? 'NULL') . "\n";
echo "\n✅ Done! Refresh your browser to see the Approve/Reject buttons!\n";

