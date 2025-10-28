<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing creation of Ahmed Hassan...\n\n";

try {
    $member = App\Models\Registration::create([
        'memberName' => 'Ahmed Hassan',
        'nok_id' => 'NOK001006',
        'age' => 36,
        'gender' => 'Male',
        'email' => 'ahmed.hassan@example.com',
        'mobile' => '+96512345701',
        'whatsapp' => '+96512345701',
        'department' => 'Radiology',
        'job_title' => 'Senior Nurse',
        'institution' => 'Sabah Hospital',
        'passport' => 'P0123456',
        'civil_id' => '287654321012354',
        'blood_group' => 'AB-',
        'phone_india' => '+919876543218',
        'nominee_name' => 'Fatima Hassan',
        'nominee_relation' => 'Sister',
        'nominee_contact' => '+96512345702',
        'login_status' => 'approved',
        'renewal_status' => 'approved',
        'password' => bcrypt('NOK1122'),
        'doj' => now()->subYears(2),
        'card_issued_at' => now()->subYears(2),
        'card_valid_until' => now()->addYear(),
        'renewal_count' => 2,
        'last_renewed_at' => now()->subMonths(1),
    ]);
    
    echo "✅ SUCCESS! Ahmed Hassan created with ID: {$member->id}\n";
    echo "   NOK ID: {$member->nok_id}\n";
    echo "   Email: {$member->email}\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}




