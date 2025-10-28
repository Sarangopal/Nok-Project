<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;

echo "➕ Creating member with card expiring in 20 days...\n";

$member = Registration::create([
    'member_type' => 'new',
    'memberName' => 'Expiring Soon Member',
    'email' => 'expiringsoon@test.com',
    'civil_id' => '777666555444',
    'password' => bcrypt('NOK5678'),
    'age' => 32,
    'gender' => 'Female',
    'mobile' => '+96577777777',
    'whatsapp' => '77777777',
    'address' => 'Kuwait Test Address',
    'department' => 'ICU',
    'job_title' => 'Senior Nurse',
    'institution' => 'Kuwait General Hospital',
    'passport' => 'P7777777',
    'blood_group' => 'O+',
    'phone_india' => '7777777777',
    'nominee_name' => 'Test Nominee 2',
    'nominee_relation' => 'Parent',
    'nominee_contact' => '7777777776',
    'guardian_name' => 'Test Guardian 2',
    'guardian_contact' => '7777777775',
    'bank_account_name' => 'Bank Account Test',
    'account_number' => '777777777',
    'ifsc_code' => 'TEST0777',
    'bank_branch' => 'Kuwait Main Branch',
    'nok_id' => 'NOK001777',
    'doj' => now()->subMonths(10),
    'login_status' => 'approved',
    'renewal_status' => 'approved',
    'card_issued_at' => now()->subMonths(10),
    'card_valid_until' => now()->addDays(20),  // Expires in 20 days - should show YELLOW
]);

echo "✅ Created member:\n";
echo "   Name: {$member->memberName}\n";
echo "   Email: {$member->email}\n";
echo "   NOK ID: {$member->nok_id}\n";
echo "   Card Valid Until: {$member->card_valid_until}\n";
echo "   Days remaining: 20 days\n";
echo "\n✅ This should show YELLOW 'Expiring Soon (20 days)' badge!\n";
echo "\nRefresh your Renewals page to see it!\n";

