<?php

namespace App\Console\Commands;

use App\Models\Registration;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ShowCardValidityExamples extends Command
{
    protected $signature = 'members:show-validity-examples';
    protected $description = 'Display examples of calendar year card validity system';

    public function handle(): int
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->info('   NOK KUWAIT - MEMBERSHIP CARD VALIDITY SYSTEM EXAMPLES');
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->info('');

        $this->scenario1();
        $this->scenario2();
        $this->scenario3();
        $this->scenario4();
        $this->scenario5();
        
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->info('For detailed documentation, see: CARD_VALIDITY_EXAMPLES.md');
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->info('');

        return self::SUCCESS;
    }

    private function scenario1(): void
    {
        $this->info('📅 SCENARIO 1: New Registration in January');
        $this->info('─────────────────────────────────────────────');
        
        Carbon::setTestNow('2025-01-20 10:00:00');
        
        $member = new Registration([
            'memberName' => 'Alice Johnson',
            'login_status' => 'approved',
            'card_issued_at' => now(),
        ]);
        
        $validUntil = $member->computeCalendarYearValidity();
        $daysValid = now()->diffInDays($validUntil);
        
        $this->line("  Registration Date: January 15, 2025");
        $this->line("  Approval Date: January 20, 2025");
        $this->line("  Card Issued: " . now()->format('M d, Y'));
        $this->line("  Card Valid Until: " . $validUntil->format('M d, Y'));
        $this->info("  ✅ Total Validity: {$daysValid} days (~11 months)");
        $this->line("  ✅ Member gets almost full year!");
        $this->info('');

        Carbon::setTestNow(null);
    }

    private function scenario2(): void
    {
        $this->info('📅 SCENARIO 2: New Registration in October');
        $this->info('─────────────────────────────────────────────');
        
        Carbon::setTestNow('2025-10-21 10:00:00');
        
        $member = new Registration([
            'memberName' => 'Bob Smith',
            'login_status' => 'approved',
            'card_issued_at' => now(),
        ]);
        
        $validUntil = $member->computeCalendarYearValidity();
        $daysValid = now()->diffInDays($validUntil);
        
        $this->line("  Registration Date: October 15, 2025");
        $this->line("  Approval Date: October 21, 2025");
        $this->line("  Card Issued: " . now()->format('M d, Y'));
        $this->line("  Card Valid Until: " . $validUntil->format('M d, Y'));
        $this->warn("  ⚠️  Total Validity: {$daysValid} days (~2.5 months only!)");
        $this->warn("  ⚠️  Member must renew before Dec 31 to continue in 2026");
        $this->info('');

        Carbon::setTestNow(null);
    }

    private function scenario3(): void
    {
        $this->info('📅 SCENARIO 3: Renewal Before Expiry (Same Year)');
        $this->info('─────────────────────────────────────────────');
        
        Carbon::setTestNow('2025-11-20 10:00:00');
        
        $member = new Registration([
            'memberName' => 'Carol Williams',
            'card_issued_at' => '2025-01-15 10:00:00',
            'card_valid_until' => '2025-12-31 23:59:59',
        ]);
        
        $this->line("  Original Card Issued: January 15, 2025");
        $this->line("  Original Card Expires: December 31, 2025");
        $this->line("  Current Date: November 20, 2025 (card still valid)");
        $this->line("  Member submits renewal request...");
        
        // Simulate renewal
        $member->last_renewed_at = now();
        $newValidUntil = $member->computeCalendarYearValidity();
        
        $this->line("  Renewal Approved: " . now()->format('M d, Y'));
        $this->line("  New Card Valid Until: " . $newValidUntil->format('M d, Y'));
        $this->warn("  ⚠️  Notice: Still expires Dec 31, 2025 (same year)");
        $this->warn("  ⚠️  Member must renew again in 2026 for next year");
        $this->info('');

        Carbon::setTestNow(null);
    }

    private function scenario4(): void
    {
        $this->info('📅 SCENARIO 4: Renewal After Expiry (Late Renewal)');
        $this->info('─────────────────────────────────────────────');
        
        Carbon::setTestNow('2026-02-15 10:00:00');
        
        $member = new Registration([
            'memberName' => 'David Brown',
            'card_issued_at' => '2025-01-15 10:00:00',
            'card_valid_until' => '2025-12-31 23:59:59',
        ]);
        
        $expired = Carbon::parse('2025-12-31');
        $daysLate = $expired->diffInDays(now());
        
        $this->line("  Original Card Expired: December 31, 2025");
        $this->line("  Current Date: February 15, 2026");
        $this->error("  ❌ Card expired {$daysLate} days ago!");
        $this->line("  Member submits late renewal request...");
        
        // Simulate late renewal
        $member->last_renewed_at = now();
        $newValidUntil = $member->computeCalendarYearValidity();
        $daysValid = now()->diffInDays($newValidUntil);
        
        $this->line("  Renewal Approved: " . now()->format('M d, Y'));
        $this->line("  New Card Valid Until: " . $newValidUntil->format('M d, Y'));
        $this->info("  ✅ Renewed for 2026: {$daysValid} days validity");
        $this->info('');

        Carbon::setTestNow(null);
    }

    private function scenario5(): void
    {
        $this->info('📅 SCENARIO 5: All Members Expire on Same Date');
        $this->info('─────────────────────────────────────────────');
        $this->line('  Demonstrating calendar year validity principle:');
        $this->info('');
        
        // January member
        Carbon::setTestNow('2025-01-15 10:00:00');
        $jan = new Registration(['card_issued_at' => now()]);
        $janExpiry = $jan->computeCalendarYearValidity();
        $janDays = now()->diffInDays($janExpiry);
        
        $this->line("  👤 Member A (Joined Jan 15, 2025)");
        $this->line("     Expires: {$janExpiry->format('M d, Y')} ({$janDays} days validity)");
        
        // June member
        Carbon::setTestNow('2025-06-15 10:00:00');
        $jun = new Registration(['card_issued_at' => now()]);
        $junExpiry = $jun->computeCalendarYearValidity();
        $junDays = now()->diffInDays($junExpiry);
        
        $this->line("  👤 Member B (Joined Jun 15, 2025)");
        $this->line("     Expires: {$junExpiry->format('M d, Y')} ({$junDays} days validity)");
        
        // November member
        Carbon::setTestNow('2025-11-15 10:00:00');
        $nov = new Registration(['card_issued_at' => now()]);
        $novExpiry = $nov->computeCalendarYearValidity();
        $novDays = now()->diffInDays($novExpiry);
        
        $this->line("  👤 Member C (Joined Nov 15, 2025)");
        $this->line("     Expires: {$novExpiry->format('M d, Y')} ({$novDays} days validity)");
        
        $this->info('');
        $this->info('  🎯 Result: ALL expire on December 31, 2025');
        $this->info('  🎯 Benefit: Simplified annual renewal management');
        $this->info('  🎯 Easy tracking: Everyone renews at year-end');
        $this->info('');

        Carbon::setTestNow(null);
    }
}

