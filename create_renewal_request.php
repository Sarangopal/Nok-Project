<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Registration;

$civilId = '309876543210';
$member = Registration::where('civil_id', $civilId)->first();

if (!$member) {
    die("Member not found!\n");
}

// Simulate renewal request submission
$member->renewal_requested_at = now();
$member->renewal_status = 'pending';
$member->renewal_payment_proof = 'renewals/payment-proofs/test_payment_proof.png'; // Simulated path
$member->save();

echo "\n✅ Renewal Request Created Successfully!\n\n";
echo "Member: {$member->memberName}\n";
echo "Email: {$member->email}\n";
echo "Requested At: {$member->renewal_requested_at}\n";
echo "Status: {$member->renewal_status}\n";
echo "Payment Proof: {$member->renewal_payment_proof}\n\n";
echo "✅ Now check the Admin Dashboard to see the renewal request!\n\n";

