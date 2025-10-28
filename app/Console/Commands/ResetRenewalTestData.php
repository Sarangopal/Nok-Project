<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;

class ResetRenewalTestData extends Command
{
    protected $signature = 'renewals:reset-test-data';
    protected $description = 'Reset all member cards to expire on Dec 31, 2025';

    public function handle(): int
    {
        $this->info('=== RESETTING RENEWAL TEST DATA ===');
        $this->newLine();

        $count = Registration::whereNotNull('card_valid_until')->count();
        
        Registration::whereNotNull('card_valid_until')
            ->update(['card_valid_until' => '2025-12-31']);

        $this->info("✅ Reset {$count} member cards to expire on Dec 31, 2025");
        $this->newLine();

        // Verify
        $renewalsPageMembers = Registration::where(function($query) {
            $query->where('login_status', 'approved')
                  ->orWhere('renewal_status', 'approved');
        })
        ->whereNotNull('card_valid_until')
        ->where('card_valid_until', '<=', now()->addDays(30))
        ->count();

        $this->line("Members on Renewals page now: {$renewalsPageMembers}");
        $this->newLine();

        return self::SUCCESS;
    }
}

