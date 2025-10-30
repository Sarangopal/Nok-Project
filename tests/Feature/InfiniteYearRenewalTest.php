<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Test that renewal system works for ANY year (2025, 2026, 2027, 2028, 2029, 2030...)
 * NOT hardcoded to specific years - works FOREVER!
 */
class InfiniteYearRenewalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function renewal_system_works_for_years_2025_to_2035()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  TEST: Renewal System Works for ANY Future Year');
        $this->info('  Testing 2025 → 2026 → 2027 → 2028 → 2029 → 2030 → 2035');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');

        // Create a member in 2025
        Carbon::setTestNow('2025-01-15 10:00:00');
        
        $member = Registration::factory()->create([
            'memberName' => 'Future Years Test Member',
            'email' => 'future@example.com',
            'login_status' => 'approved',
            'card_issued_at' => now(),
            'renewal_count' => 0,
        ]);

        // Initial approval
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();
        $member->refresh();

        $this->info("2025: Initial Registration");
        $this->info("  Joined: Jan 15, 2025");
        $this->info("  Expires: {$member->card_valid_until->format('Y-m-d')}");
        $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
        $this->info('');

        // Test years 2026 through 2030
        $years = [2026, 2027, 2028, 2029, 2030, 2035, 2040, 2050];
        
        foreach ($years as $year) {
            // Renew in January of each year
            Carbon::setTestNow("{$year}-01-10 10:00:00");
            
            $member->renewal_status = 'pending';
            $member->renewal_requested_at = now();
            $member->save();
            
            // Admin approves
            $member->renewal_status = 'approved';
            $member->last_renewed_at = now();
            $member->renewal_count = ($member->renewal_count ?? 0) + 1;
            $member->card_valid_until = $member->computeCalendarYearValidity();
            $member->save();
            $member->refresh();
            
            $expectedExpiry = "{$year}-12-31";
            
            $this->info("{$year}: Renewal Approved");
            $this->info("  Renewed: Jan 10, {$year}");
            $this->info("  Expires: {$member->card_valid_until->format('Y-m-d')} ✅");
            
            $this->assertEquals($expectedExpiry, $member->card_valid_until->format('Y-m-d'));
        }
        
        $this->info('');
        $this->info("✅ System works for ALL years: 2025 through 2050+");
        $this->info("✅ NOT hardcoded - uses dynamic calculation");
        $this->info("✅ Will work forever into the future!");
        $this->info('');
    }

    /** @test */
    public function calendar_year_calculation_is_dynamic_not_hardcoded()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  TEST: Calculation is Dynamic (Not Hardcoded)');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');

        $testYears = [
            2025 => '2025-12-31',
            2026 => '2026-12-31',
            2027 => '2027-12-31',
            2028 => '2028-12-31',
            2029 => '2029-12-31',
            2030 => '2030-12-31',
            2040 => '2040-12-31',
            2050 => '2050-12-31',
            2100 => '2100-12-31', // Even works in next century!
        ];

        $member = Registration::factory()->create([
            'memberName' => 'Dynamic Test',
            'email' => 'dynamic@test.com',
        ]);

        foreach ($testYears as $year => $expectedExpiry) {
            Carbon::setTestNow("{$year}-06-15 10:00:00");
            
            $member->card_issued_at = now();
            $calculated = $member->computeCalendarYearValidity();
            
            $this->info("Year {$year}:");
            $this->info("  Current Date: Jun 15, {$year}");
            $this->info("  Calculated Expiry: {$calculated->format('Y-m-d')}");
            $this->info("  Expected: {$expectedExpiry}");
            $this->info("  ✅ MATCH: " . ($calculated->format('Y-m-d') === $expectedExpiry ? 'YES' : 'NO'));
            $this->info('');
            
            $this->assertEquals($expectedExpiry, $calculated->format('Y-m-d'));
        }
        
        $this->info("✅ Formula: now()->endOfYear() is DYNAMIC");
        $this->info("✅ Works for ANY year (past, present, future)");
        $this->info("✅ No year limits or hardcoding!");
        $this->info('');
    }

    /** @test */
    public function member_can_renew_continuously_for_decades()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  TEST: Member Can Renew for 10+ Years');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');

        Carbon::setTestNow('2025-01-01 10:00:00');
        
        $member = Registration::factory()->create([
            'memberName' => 'Long-term Member',
            'email' => 'longtime@example.com',
            'login_status' => 'approved',
            'card_issued_at' => now(),
            'card_valid_until' => now()->endOfYear(),
            'renewal_count' => 0,
        ]);

        $this->info("Member joins: 2025");
        $this->info("Initial expiry: 2025-12-31");
        $this->info('');

        // Renew every year for 10 years
        for ($year = 2026; $year <= 2035; $year++) {
            Carbon::setTestNow("{$year}-01-15 10:00:00");
            
            // Renew
            $member->renewal_status = 'approved';
            $member->last_renewed_at = now();
            $member->renewal_count += 1;
            $member->card_valid_until = $member->computeCalendarYearValidity();
            $member->save();
            $member->refresh();
            
            $this->assertEquals("{$year}-12-31", $member->card_valid_until->format('Y-m-d'));
        }

        $this->info("After 10 renewals (2026-2035):");
        $this->info("  Final expiry: {$member->card_valid_until->format('Y-m-d')}");
        $this->info("  Renewal count: {$member->renewal_count}");
        $this->info("  ✅ Member renewed successfully for 10 consecutive years!");
        $this->info('');
        
        $this->assertEquals('2035-12-31', $member->card_valid_until->format('Y-m-d'));
        $this->assertEquals(10, $member->renewal_count);
    }

    /** @test */
    public function system_handles_leap_years_correctly()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  TEST: System Handles Leap Years');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');

        $leapYears = [2024, 2028, 2032, 2036, 2040];
        
        $member = Registration::factory()->create([
            'memberName' => 'Leap Year Test',
            'email' => 'leap@test.com',
        ]);

        foreach ($leapYears as $year) {
            // Test renewal on Feb 29 (leap day)
            Carbon::setTestNow("{$year}-02-29 10:00:00");
            
            $member->card_issued_at = now();
            $calculated = $member->computeCalendarYearValidity();
            
            $this->info("Leap Year {$year}:");
            $this->info("  Joined on: Feb 29, {$year} (leap day!)");
            $this->info("  Expires: {$calculated->format('Y-m-d')}");
            $this->info("  ✅ Still Dec 31, {$year}");
            $this->info('');
            
            $this->assertEquals("{$year}-12-31", $calculated->format('Y-m-d'));
        }
        
        $this->info("✅ Leap years handled correctly!");
        $this->info("✅ Always expires Dec 31, regardless of leap day");
        $this->info('');
    }

    /** @test */
    public function code_inspection_shows_dynamic_calculation()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  CODE INSPECTION: How It Works');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');
        
        $this->info("File: app/Models/Registration.php");
        $this->info("Method: computeCalendarYearValidity()");
        $this->info('');
        $this->info("Code:");
        $this->info("  public function computeCalendarYearValidity(?Carbon \$baseDate = null): Carbon");
        $this->info("  {");
        $this->info("      \$date = \$baseDate ?: (\$this->last_renewed_at ?: (\$this->card_issued_at ?: now()));");
        $this->info("      return Carbon::parse(\$date)->endOfYear();");
        $this->info("                                   ^^^^^^^^^^");
        $this->info("                                   This is DYNAMIC!");
        $this->info("  }");
        $this->info('');
        $this->info("Explanation:");
        $this->info("  • endOfYear() calculates Dec 31 of CURRENT year");
        $this->info("  • NOT hardcoded: '2025-12-31' ❌");
        $this->info("  • Uses: Carbon::parse(\$date)->endOfYear() ✅");
        $this->info("  • Works for ANY year: 2025, 2026, 2027, 2028, 2029...");
        $this->info("  • Will work forever into the future!");
        $this->info('');
        
        // Demonstrate with actual calculation
        $member = Registration::factory()->create();
        
        // Test 2028
        Carbon::setTestNow('2028-03-15 10:00:00');
        $member->card_issued_at = now();
        $result2028 = $member->computeCalendarYearValidity();
        
        // Test 2050
        Carbon::setTestNow('2050-07-20 10:00:00');
        $member->card_issued_at = now();
        $result2050 = $member->computeCalendarYearValidity();
        
        $this->info("Live Demonstration:");
        $this->info("  For date: Mar 15, 2028 → Result: {$result2028->format('Y-m-d')} ✅");
        $this->info("  For date: Jul 20, 2050 → Result: {$result2050->format('Y-m-d')} ✅");
        $this->info('');
        
        $this->assertEquals('2028-12-31', $result2028->format('Y-m-d'));
        $this->assertEquals('2050-12-31', $result2050->format('Y-m-d'));
        
        $this->info("✅ System is FUTURE-PROOF!");
        $this->info('');
    }

    private function info(string $message): void
    {
        fwrite(STDOUT, $message . PHP_EOL);
    }
}





