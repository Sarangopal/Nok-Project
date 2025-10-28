<?php

namespace App\Console\Commands;

use App\Models\Registration;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FixCardExpiryDates extends Command
{
    protected $signature = 'members:fix-expiry-dates {--dry-run : Show what would be fixed without making changes}';
    protected $description = 'Fix card expiry dates to follow strict calendar-year logic (Dec 31 of current year)';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be made');
        } else {
            $this->info('🔧 FIXING card expiry dates...');
        }
        
        $this->newLine();
        
        // Get current year end
        $currentYearEnd = now()->endOfYear();
        $this->info("Target expiry date: {$currentYearEnd->format('Y-m-d')} (Dec 31, " . now()->year . ")");
        $this->newLine();
        
        // Find all approved members with wrong expiry dates
        $members = Registration::query()
            ->where(function($query) {
                $query->where('login_status', 'approved')
                      ->orWhere('renewal_status', 'approved');
            })
            ->whereNotNull('card_valid_until')
            ->where('card_valid_until', '!=', $currentYearEnd->toDateString())
            ->get();
        
        if ($members->isEmpty()) {
            $this->info('✓ No members found with incorrect expiry dates!');
            return self::SUCCESS;
        }
        
        $this->warn("Found {$members->count()} members with incorrect expiry dates:");
        $this->newLine();
        
        $fixed = 0;
        
        foreach ($members as $member) {
            $oldExpiry = $member->card_valid_until->format('Y-m-d');
            $newExpiry = $currentYearEnd->format('Y-m-d');
            
            $status = $oldExpiry > $newExpiry ? '⚠️' : '✓';
            
            $this->line("{$status} {$member->nok_id} - {$member->memberName}");
            $this->line("   Old expiry: {$oldExpiry}");
            $this->line("   New expiry: {$newExpiry}");
            
            if (!$isDryRun) {
                $member->card_valid_until = $currentYearEnd;
                $member->save();
                $fixed++;
                $this->line("   ✓ FIXED!");
            } else {
                $this->line("   → Would fix (dry run)");
            }
            
            $this->newLine();
        }
        
        $this->newLine();
        
        if ($isDryRun) {
            $this->warn("🔍 DRY RUN COMPLETE - {$members->count()} members would be fixed");
            $this->info("Run without --dry-run to apply changes:");
            $this->line("   php artisan members:fix-expiry-dates");
        } else {
            $this->info("✓ FIXED {$fixed} members!");
            $this->info("All approved members now expire on: {$currentYearEnd->format('M d, Y')}");
        }
        
        return self::SUCCESS;
    }
}

