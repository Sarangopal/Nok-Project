<?php

namespace App\Console\Commands;

use App\Models\Registration;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FixRenewalStatus extends Command
{
    protected $signature = 'fix:renewal-status';
    protected $description = 'Fix renewal status for members with valid cards';

    public function handle()
    {
        $this->info('🔧 Fixing Renewal Status for Valid Members...');
        $this->newLine();

        // Get all members with valid cards but pending status
        $membersToFix = Registration::where('renewal_status', 'pending')
            ->whereNotNull('card_valid_until')
            ->where('card_valid_until', '>', now())
            ->get();

        $this->info("Found {$membersToFix->count()} members with valid cards but 'pending' status");
        $this->newLine();

        $fixed = 0;
        foreach ($membersToFix as $member) {
            $daysUntilExpiry = now()->diffInDays(Carbon::parse($member->card_valid_until), false);
            
            // If card is valid (more than 30 days), mark as approved
            if ($daysUntilExpiry > 30) {
                $member->renewal_status = 'approved';
                $member->save();
                
                $this->line("✅ Fixed: {$member->memberName} (Expires: {$member->card_valid_until}, {$daysUntilExpiry} days remaining)");
                $fixed++;
            } else {
                $this->line("⏭️  Skipped: {$member->memberName} (Expires soon: {$daysUntilExpiry} days)");
            }
        }

        $this->newLine();
        $this->info("═══════════════════════════════════════");
        $this->info("✅ Fixed {$fixed} members");
        $this->info("═══════════════════════════════════════");

        // Also fix members with no expiry date who were approved
        $noExpiryMembers = Registration::whereNull('card_valid_until')
            ->where('member_type', '!=', 'new')
            ->whereNotNull('card_issued_at')
            ->get();

        $this->newLine();
        $this->info("Found {$noExpiryMembers->count()} members with no expiry date");

        $setExpiry = 0;
        foreach ($noExpiryMembers as $member) {
            // Set expiry to end of current year if card was issued this year
            if ($member->card_issued_at) {
                $issuedYear = Carbon::parse($member->card_issued_at)->year;
                $currentYear = now()->year;
                
                if ($issuedYear == $currentYear) {
                    $member->card_valid_until = now()->endOfYear();
                    $member->renewal_status = 'approved';
                    $member->save();
                    
                    $this->line("✅ Set expiry for: {$member->memberName} → {$member->card_valid_until}");
                    $setExpiry++;
                }
            }
        }

        $this->newLine();
        $this->info("═══════════════════════════════════════");
        $this->info("✅ Set expiry dates for {$setExpiry} members");
        $this->info("═══════════════════════════════════════");
        $this->newLine();
        $this->info('🎉 ALL DONE!');

        return Command::SUCCESS;
    }
}








