<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;
use App\Mail\RenewalReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

echo "📧 Creating test members and sending sample reminder emails...\n\n";

// Clean up old test data
echo "🗑️  Cleaning up old test data...\n";
Registration::whereIn('email', [
    'reminder30@test.com',
    'reminder15@test.com',
    'reminder7@test.com',
    'reminder1@test.com',
    'reminder0@test.com'
])->delete();
echo "✅ Cleaned up\n\n";

// Create test members for each reminder interval
$testMembers = [
    [
        'days' => 30,
        'name' => 'Test Member - 30 Days',
        'email' => 'reminder30@test.com',
        'civil_id' => '30030030030030',
        'valid_until' => now()->addDays(30),
    ],
    [
        'days' => 15,
        'name' => 'Test Member - 15 Days',
        'email' => 'reminder15@test.com',
        'civil_id' => '15015015015015',
        'valid_until' => now()->addDays(15),
    ],
    [
        'days' => 7,
        'name' => 'Test Member - 7 Days',
        'email' => 'reminder7@test.com',
        'civil_id' => '07070707070707',
        'valid_until' => now()->addDays(7),
    ],
    [
        'days' => 1,
        'name' => 'Test Member - 1 Day',
        'email' => 'reminder1@test.com',
        'civil_id' => '01010101010101',
        'valid_until' => now()->addDays(1),
    ],
    [
        'days' => 0,
        'name' => 'Test Member - Expiring Today',
        'email' => 'reminder0@test.com',
        'civil_id' => '00000000000000',
        'valid_until' => now(),
    ],
];

$createdMembers = [];

echo "➕ Creating test members...\n";
foreach ($testMembers as $testData) {
    $member = Registration::create([
        'member_type' => 'new',
        'memberName' => $testData['name'],
        'email' => $testData['email'],
        'civil_id' => $testData['civil_id'],
        'password' => bcrypt('NOK1234'),
        'age' => 30,
        'gender' => 'Male',
        'mobile' => '+96550000000',
        'whatsapp' => '50000000',
        'address' => 'Kuwait City - Test Address',
        'department' => 'Nursing',
        'job_title' => 'Staff Nurse',
        'institution' => 'Test Hospital',
        'passport' => 'P1234567',
        'blood_group' => 'O+',
        'phone_india' => '9876543210',
        'nominee_name' => 'Test Nominee',
        'nominee_relation' => 'Spouse',
        'nominee_contact' => '9876543211',
        'guardian_name' => 'Test Guardian',
        'guardian_contact' => '9876543212',
        'bank_account_name' => 'Test Bank',
        'account_number' => '1234567890',
        'ifsc_code' => 'TEST0001',
        'bank_branch' => 'Test Branch',
        'nok_id' => 'NOK' . str_pad($testData['days'], 6, '0', STR_PAD_LEFT),
        'doj' => now()->subYear(),
        'card_issued_at' => now()->subYear(),
        'card_valid_until' => $testData['valid_until']->toDateString(),
        'renewal_status' => 'approved',
        'login_status' => 'approved',
        'last_renewed_at' => now()->subYear(),
        'renewal_count' => 1,
    ]);
    
    $createdMembers[] = [
        'member' => $member,
        'days' => $testData['days']
    ];
    
    echo "   ✅ Created: {$testData['name']} (expires in {$testData['days']} days)\n";
}

echo "\n📧 Sending sample reminder emails...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$emailsSent = 0;
$emailsFailed = 0;

foreach ($createdMembers as $data) {
    $member = $data['member'];
    $days = $data['days'];
    
    try {
        Mail::to($member->email)->send(new RenewalReminderMail($member, $days));
        $emailsSent++;
        
        echo "✅ Email sent to: {$member->email}\n";
        echo "   Name: {$member->memberName}\n";
        echo "   NOK ID: {$member->nok_id}\n";
        echo "   Days until expiry: {$days}\n";
        echo "   Expiry date: {$member->card_valid_until}\n";
        echo "   Subject: Membership Renewal Reminder\n";
        echo "\n";
        
    } catch (\Exception $e) {
        $emailsFailed++;
        echo "❌ Failed to send to: {$member->email}\n";
        echo "   Error: {$e->getMessage()}\n\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n📊 Summary:\n";
echo "   ✅ Total emails sent: {$emailsSent}\n";
echo "   ❌ Failed: {$emailsFailed}\n";
echo "\n💡 Tip: Check your email inbox (including spam folder) for the sample emails!\n";
echo "\n📧 Email addresses used:\n";
foreach ($createdMembers as $data) {
    echo "   • {$data['member']->email} ({$data['days']} days reminder)\n";
}

echo "\n✅ Done! Sample reminder emails have been sent.\n";
echo "\n🗑️  To clean up test data, run:\n";
echo "   php artisan tinker\n";
echo "   >>> Registration::whereIn('email', ['reminder30@test.com','reminder15@test.com','reminder7@test.com','reminder1@test.com','reminder0@test.com'])->delete();\n";

