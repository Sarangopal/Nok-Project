<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing creation of Lisa Wong...\n\n";

try {
    $member = App\Models\Registration::create([
        'memberName' => 'Lisa Wong',
        'nok_id' => 'NOK001005',
        'age' => 34,
        'gender' => 'Female',
        'email' => 'lisa.wong@example.com',
        'mobile' => '+96512345699',
        'whatsapp' => '+96512345699',
        'department' => 'Nephrology',
        'job_title' => 'Registered Nurse',
        'institution' => 'Jahra Hospital',
        'passport' => 'P9012345',
        'civil_id' => '287654321012353',
        'blood_group' => 'O+',
        'phone_india' => '+919876543217',
        'nominee_name' => 'Michael Wong',
        'nominee_relation' => 'Husband',
        'nominee_contact' => '+96512345700',
        'login_status' => 'approved',
        'renewal_status' => 'pending',
        'password' => bcrypt('NOK7890'),
        'doj' => now()->subYear(),
        'card_issued_at' => now()->subYear(),
        'card_valid_until' => now()->addDays(20),
        'renewal_count' => 0,
    ]);
    
    echo "✅ SUCCESS! Lisa Wong created with ID: {$member->id}\n";
    echo "   NOK ID: {$member->nok_id}\n";
    echo "   Email: {$member->email}\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}




