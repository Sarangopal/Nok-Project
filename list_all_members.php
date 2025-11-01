<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📋 ALL SEEDED MEMBERS\n";
echo str_repeat('=', 80) . "\n\n";

// Pending Registrations
echo "⏳ PENDING REGISTRATIONS (3):\n";
echo str_repeat('-', 80) . "\n";
$pending = App\Models\Registration::where('login_status', 'pending')->get();
foreach ($pending as $member) {
    echo "  Name: {$member->memberName}\n";
    echo "  Email: {$member->email}\n";
    echo "  Civil ID: {$member->civil_id}\n\n";
}

// Approved Members
echo "\n✅ APPROVED MEMBERS (can login):\n";
echo str_repeat('-', 80) . "\n";
$approved = App\Models\Registration::where('login_status', 'approved')->get();
foreach ($approved as $member) {
    $expiredStatus = $member->card_valid_until && now()->gt($member->card_valid_until) ? '(EXPIRED)' : '';
    $renewalStatus = $member->renewal_status ? "Renewal: {$member->renewal_status}" : '';
    
    echo "  Name: {$member->memberName}\n";
    echo "  NOK ID: {$member->nok_id}\n";
    echo "  Civil ID: {$member->civil_id}\n";
    echo "  Email: {$member->email}\n";
    echo "  Valid Until: {$member->card_valid_until} {$expiredStatus}\n";
    if ($renewalStatus) {
        echo "  Status: {$renewalStatus}\n";
    }
    echo "\n";
}

// Rejected
echo "\n❌ REJECTED REGISTRATIONS:\n";
echo str_repeat('-', 80) . "\n";
$rejected = App\Models\Registration::where('login_status', 'rejected')->get();
foreach ($rejected as $member) {
    echo "  Name: {$member->memberName}\n";
    echo "  Email: {$member->email}\n\n";
}

echo str_repeat('=', 80) . "\n";
echo "Total: " . App\Models\Registration::count() . " members\n";





