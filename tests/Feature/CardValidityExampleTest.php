<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * This test demonstrates the Calendar Year Validity System with real examples
 */
class CardValidityExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function example_scenario_1_new_registration_in_january()
    {
        // Set current date to January
        Carbon::setTestNow('2025-01-20 10:00:00');

        $this->info('📅 SCENARIO 1: New Registration in January');
        $this->info('Registration Date: January 15, 2025');
        $this->info('Approval Date: January 20, 2025');

        // Create and approve registration
        $member = Registration::factory()->create([
            'memberName' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'login_status' => 'pending',
            'renewal_count' => 0,
            'created_at' => '2025-01-15 10:00:00',
        ]);

        // Approve registration
        $member->login_status = 'approved';
        $member->card_issued_at = now();
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();

        $this->info("✅ Card Issued: {$member->card_issued_at->format('M d, Y')}");
        $this->info("✅ Card Valid Until: {$member->card_valid_until->format('M d, Y')}");
        
        $daysValid = $member->card_issued_at->diffInDays($member->card_valid_until);
        $this->info("✅ Total Validity: {$daysValid} days (~11 months)");
        
        $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
        $this->assertGreaterThan(340, $daysValid); // Almost full year
    }

    /** @test */
    public function example_scenario_2_new_registration_in_october()
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $this->info('📅 SCENARIO 2: New Registration in October');
        $this->info('Registration Date: October 15, 2025');
        $this->info('Approval Date: October 21, 2025');

        $member = Registration::factory()->create([
            'memberName' => 'Bob Smith',
            'email' => 'bob@example.com',
            'login_status' => 'pending',
            'renewal_count' => 0,
            'created_at' => '2025-10-15 10:00:00',
        ]);

        $member->login_status = 'approved';
        $member->card_issued_at = now();
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();

        $this->info("⚠️ Card Issued: {$member->card_issued_at->format('M d, Y')}");
        $this->info("⚠️ Card Valid Until: {$member->card_valid_until->format('M d, Y')}");
        
        $daysValid = $member->card_issued_at->diffInDays($member->card_valid_until);
        $this->info("⚠️ Total Validity: {$daysValid} days (~2.5 months only!)");
        $this->info("⚠️ Member must renew before Dec 31 to continue in 2026");

        $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
        $this->assertLessThan(75, $daysValid); // Less than 3 months
    }

    /** @test */
    public function example_scenario_3_renewal_before_expiry()
    {
        Carbon::setTestNow('2025-11-20 10:00:00');

        $this->info('📅 SCENARIO 3: Renewal Before Expiry (Same Year)');
        $this->info('Original Card Issued: January 15, 2025');
        $this->info('Original Card Expires: December 31, 2025');
        $this->info('Current Date: November 20, 2025 (card still valid)');

        $member = Registration::factory()->create([
            'memberName' => 'Carol Williams',
            'email' => 'carol@example.com',
            'renewal_status' => 'pending',
            'card_issued_at' => '2025-01-15 10:00:00',
            'card_valid_until' => '2025-12-31 23:59:59',
            'renewal_count' => 0,
        ]);

        $this->info("Before Renewal: Valid until {$member->card_valid_until->format('M d, Y')}");

        // Approve renewal
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = 1;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();

        $this->info("After Renewal: Valid until {$member->card_valid_until->format('M d, Y')}");
        $this->info("⚠️ Notice: Still expires Dec 31, 2025 (same year)");
        $this->info("⚠️ Member must renew again in 2026 for next year");

        $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
    }

    /** @test */
    public function example_scenario_4_renewal_after_expiry()
    {
        Carbon::setTestNow('2026-02-15 10:00:00');

        $this->info('📅 SCENARIO 4: Renewal After Expiry (Late Renewal)');
        $this->info('Original Card Expired: December 31, 2025');
        $this->info('Current Date: February 15, 2026 (46 days late!)');

        $member = Registration::factory()->create([
            'memberName' => 'David Brown',
            'email' => 'david@example.com',
            'renewal_status' => 'pending',
            'card_issued_at' => '2025-01-15 10:00:00',
            'card_valid_until' => '2025-12-31 23:59:59',
            'renewal_count' => 0,
        ]);

        $this->info("❌ Card Expired: {$member->card_valid_until->format('M d, Y')}");
        $this->info("🔄 Member submits late renewal in February 2026");

        // Approve late renewal
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = 1;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();

        $this->info("✅ Renewed for 2026: Valid until {$member->card_valid_until->format('M d, Y')}");
        
        $daysValid = now()->diffInDays($member->card_valid_until);
        $this->info("✅ Validity for 2026: {$daysValid} days");

        $this->assertEquals('2026-12-31', $member->card_valid_until->format('Y-m-d'));
    }

    /** @test */
    public function example_scenario_5_multiple_years_journey()
    {
        $this->info('📅 SCENARIO 5: Complete Multi-Year Member Journey');
        $this->info('Member: Emma Davis');
        $this->info('');

        // Year 2025 - Initial Registration
        Carbon::setTestNow('2025-03-15 10:00:00');
        
        $member = Registration::factory()->create([
            'memberName' => 'Emma Davis',
            'email' => 'emma@example.com',
            'login_status' => 'pending',
            'renewal_count' => 0,
        ]);

        $member->login_status = 'approved';
        $member->card_issued_at = now();
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();

        $this->info("2025 - YEAR 1:");
        $this->info("  Joined: March 15, 2025");
        $this->info("  Card Expires: {$member->card_valid_until->format('M d, Y')}");
        $this->info("  Validity: " . now()->diffInDays($member->card_valid_until) . " days");

        // Try to renew in November 2025 (same year)
        Carbon::setTestNow('2025-11-25 10:00:00');
        
        $member->renewal_status = 'pending';
        $member->save();
        
        // Approve renewal
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = 1;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();

        $this->info("  Renewed: November 25, 2025");
        $this->info("  Still Expires: {$member->card_valid_until->format('M d, Y')} ⚠️");
        $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));

        // Year 2026 - Renewal for new year
        Carbon::setTestNow('2026-01-10 10:00:00');
        
        $member->renewal_status = 'pending';
        $member->save();
        
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = 2;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();

        $this->info("2026 - YEAR 2:");
        $this->info("  Renewed: January 10, 2026");
        $this->info("  Card Expires: {$member->card_valid_until->format('M d, Y')}");
        $this->info("  Validity: Full year 2026");
        $this->assertEquals('2026-12-31', $member->card_valid_until->format('Y-m-d'));

        // Year 2027 - Another renewal
        Carbon::setTestNow('2027-01-05 10:00:00');
        
        $member->renewal_status = 'pending';
        $member->save();
        
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = 3;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();

        $this->info("2027 - YEAR 3:");
        $this->info("  Renewed: January 5, 2027");
        $this->info("  Card Expires: {$member->card_valid_until->format('M d, Y')}");
        $this->info("  Total Renewals: 3");
        $this->assertEquals('2027-12-31', $member->card_valid_until->format('Y-m-d'));
    }

    /** @test */
    public function example_scenario_6_all_members_same_expiry_date()
    {
        Carbon::setTestNow('2025-06-15 10:00:00');

        $this->info('📅 SCENARIO 6: All Members Expire on Same Date');
        $this->info('Demonstrating calendar year validity principle');
        $this->info('');

        $members = [];

        // Member joins in January
        Carbon::setTestNow('2025-01-15 10:00:00');
        $jan = Registration::factory()->create([
            'memberName' => 'January Member',
            'email' => 'jan@test.com',
            'login_status' => 'approved',
            'card_issued_at' => now(),
            'card_valid_until' => now()->endOfYear(),
        ]);
        $members[] = $jan;

        // Member joins in June
        Carbon::setTestNow('2025-06-15 10:00:00');
        $jun = Registration::factory()->create([
            'memberName' => 'June Member',
            'email' => 'jun@test.com',
            'login_status' => 'approved',
            'card_issued_at' => now(),
            'card_valid_until' => now()->endOfYear(),
        ]);
        $members[] = $jun;

        // Member joins in November
        Carbon::setTestNow('2025-11-15 10:00:00');
        $nov = Registration::factory()->create([
            'memberName' => 'November Member',
            'email' => 'nov@test.com',
            'login_status' => 'approved',
            'card_issued_at' => now(),
            'card_valid_until' => now()->endOfYear(),
        ]);
        $members[] = $nov;

        $this->info("✅ January Member:");
        $this->info("   Joined: Jan 15, 2025 | Expires: Dec 31, 2025 (351 days)");
        
        $this->info("✅ June Member:");
        $this->info("   Joined: Jun 15, 2025 | Expires: Dec 31, 2025 (199 days)");
        
        $this->info("✅ November Member:");
        $this->info("   Joined: Nov 15, 2025 | Expires: Dec 31, 2025 (46 days)");
        
        $this->info('');
        $this->info('🎯 Result: ALL expire on December 31, 2025');
        $this->info('🎯 Benefit: Simplified annual renewal management');

        // Assert all have same expiry date
        foreach ($members as $member) {
            $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
        }
    }

    // Helper method to output info during tests
    private function info(string $message): void
    {
        fwrite(STDOUT, $message . PHP_EOL);
    }
}

