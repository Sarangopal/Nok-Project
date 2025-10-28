<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registration;

echo "📊 Current Registration Status:\n\n";

$members = Registration::all();

foreach ($members as $member) {
    echo "ID: {$member->id} | Name: {$member->memberName}\n";
    echo "  Email: {$member->email}\n";
    echo "  Card Issued: " . ($member->card_issued_at ? $member->card_issued_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "  Login Status: " . ($member->login_status ?? 'NULL') . "\n";
    echo "  Renewal Status: " . ($member->renewal_status ?? 'NULL') . "\n";
    echo "  ---\n";
}

echo "\n🔧 Fixing 'Expired Test Member' to have login_status = 'pending'...\n";

$expired = Registration::where('email', 'expired@test.com')->first();
if ($expired) {
    // Set login_status to pending (new registration not yet approved)
    $expired->login_status = 'pending';
    // Clear card_issued_at since this is a NEW registration (not approved yet)
    $expired->card_issued_at = null;
    $expired->save();
    
    echo "✅ Updated: login_status = 'pending', card_issued_at = NULL\n";
} else {
    echo "❌ Expired Test Member not found\n";
}

echo "\n✅ Done! Now refresh your browser and you should see Approve/Reject buttons.\n";

