<?php

namespace App\Console\Commands;

use App\Models\Registration;
use Illuminate\Console\Command;

class CheckExpiringSoon extends Command
{
    protected $signature = 'check:expiring-soon';
    protected $description = 'Diagnose why expiring soon widget is empty';

    public function handle(): int
    {
        $this->info('=== Diagnostic Report: Expiring Soon Widget ===');
        $this->newLine();

        $now = now();
        $limit = now()->addDays(30);

        // Total registrations
        $total = Registration::count();
        $this->line("📊 Total Registrations: {$total}");
        $this->newLine();

        // Check login_status distribution
        $this->info('🔐 Login Status Distribution:');
        $loginStatuses = Registration::selectRaw('login_status, COUNT(*) as count')
            ->groupBy('login_status')
            ->get();
        foreach ($loginStatuses as $status) {
            $this->line("   - {$status->login_status}: {$status->count}");
        }
        $this->newLine();

        // Check renewal_status distribution
        $this->info('🔄 Renewal Status Distribution:');
        $renewalStatuses = Registration::selectRaw('renewal_status, COUNT(*) as count')
            ->groupBy('renewal_status')
            ->get();
        foreach ($renewalStatuses as $status) {
            $this->line("   - {$status->renewal_status}: {$status->count}");
        }
        $this->newLine();

        // Members with card_valid_until
        $withCards = Registration::whereNotNull('card_valid_until')->count();
        $this->line("💳 Members with card_valid_until set: {$withCards}");
        $this->newLine();

        // Members expiring in 30 days (any status)
        $expiringAny = Registration::whereNotNull('card_valid_until')
            ->whereBetween('card_valid_until', [$now, $limit])
            ->get();
        
        $this->info("📅 Cards expiring within 30 days (ANY status): {$expiringAny->count()}");
        if ($expiringAny->count() > 0) {
            $this->table(
                ['Name', 'NOK ID', 'Expiry Date', 'Login Status', 'Renewal Status'],
                $expiringAny->map(fn($m) => [
                    $m->memberName,
                    $m->nok_id,
                    $m->card_valid_until?->format('Y-m-d'),
                    $m->login_status,
                    $m->renewal_status,
                ])
            );
        }
        $this->newLine();

        // Test with login_status = approved
        $expiringLogin = Registration::where('login_status', 'approved')
            ->whereNotNull('card_valid_until')
            ->whereBetween('card_valid_until', [$now, $limit])
            ->count();
        $this->line("✅ With login_status = 'approved': {$expiringLogin}");

        // Test with renewal_status = approved
        $expiringRenewal = Registration::where('renewal_status', 'approved')
            ->whereNotNull('card_valid_until')
            ->whereBetween('card_valid_until', [$now, $limit])
            ->count();
        $this->line("✅ With renewal_status = 'approved': {$expiringRenewal}");
        $this->newLine();

        // Show date range being checked
        $this->info('📆 Date Range:');
        $this->line("   From: {$now->format('Y-m-d H:i:s')}");
        $this->line("   To:   {$limit->format('Y-m-d H:i:s')}");
        $this->newLine();

        // Show some sample card expiry dates
        $this->info('📋 Sample Card Expiry Dates:');
        $sampleCards = Registration::whereNotNull('card_valid_until')
            ->orderBy('card_valid_until')
            ->take(10)
            ->get(['memberName', 'card_valid_until', 'login_status', 'renewal_status']);
        
        if ($sampleCards->count() > 0) {
            $this->table(
                ['Member', 'Card Expires', 'Login Status', 'Renewal Status'],
                $sampleCards->map(fn($m) => [
                    $m->memberName,
                    $m->card_valid_until?->format('Y-m-d'),
                    $m->login_status ?? 'NULL',
                    $m->renewal_status ?? 'NULL',
                ])
            );
        } else {
            $this->warn('   No members have card_valid_until set!');
        }
        $this->newLine();

        // Recommendation
        $this->info('💡 Recommendation:');
        if ($expiringLogin > 0) {
            $this->line("   Use: ->where('login_status', 'approved')");
            $this->line("   This will show {$expiringLogin} members");
        } elseif ($expiringRenewal > 0) {
            $this->line("   Use: ->where('renewal_status', 'approved')");
            $this->line("   This will show {$expiringRenewal} members");
        } elseif ($expiringAny->count() > 0) {
            $this->warn("   Members exist but none have 'approved' status!");
            $this->line("   You may need to approve members first.");
        } else {
            $this->warn("   No members have cards expiring in the next 30 days.");
            $this->line("   This is normal if all cards expire later or are already expired.");
        }

        return self::SUCCESS;
    }
}

