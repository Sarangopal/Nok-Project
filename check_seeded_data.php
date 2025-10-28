<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📊 CHECKING SEEDED DATA...\n\n";

$totalReg = App\Models\Registration::count();
$pendingReg = App\Models\Registration::where('login_status', 'pending')->count();
$approvedReg = App\Models\Registration::where('login_status', 'approved')->count();
$rejectedReg = App\Models\Registration::where('login_status', 'rejected')->count();

echo "Total Registrations: $totalReg\n";
echo "  - Pending: $pendingReg\n";
echo "  - Approved: $approvedReg\n";
echo "  - Rejected: $rejectedReg\n\n";

if (class_exists('App\Models\RenewalRequest')) {
    $totalRenewals = App\Models\RenewalRequest::count();
    echo "Total Renewal Requests: $totalRenewals\n\n";
}

if ($approvedReg > 0) {
    echo "✅ Sample approved members:\n";
    $approved = App\Models\Registration::where('login_status', 'approved')->get();
    foreach ($approved as $member) {
        echo "  - {$member->memberName} (NOK ID: {$member->nok_id}, Civil ID: {$member->civil_id})\n";
    }
}

echo "\n✅ Data seeded successfully!\n";




