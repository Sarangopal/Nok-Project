<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔄 Updating login_status for all records...\n\n";

// Update existing members with card_issued_at to have login_status = approved
$updated = DB::table('registrations')
    ->whereNotNull('card_issued_at')
    ->update(['login_status' => 'approved']);

echo "✅ Updated {$updated} existing approved members with login_status='approved'\n";

// Update ALL pending registrations (including ones with NULL login_status)
$pending = DB::table('registrations')
    ->whereNull('card_issued_at')
    ->update(['login_status' => 'pending']);

echo "✅ Updated {$pending} pending registrations with login_status='pending'\n";

echo "\n📊 Current status distribution:\n";
$stats = DB::table('registrations')
    ->select('login_status', DB::raw('count(*) as total'))
    ->groupBy('login_status')
    ->get();

foreach ($stats as $stat) {
    $status = $stat->login_status ?? 'NULL';
    echo "  - {$status}: {$stat->total}\n";
}

echo "\n✅ Done! Please refresh your browser.\n";

