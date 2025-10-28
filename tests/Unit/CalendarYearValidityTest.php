<?php

namespace Tests\Unit;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CalendarYearValidityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_registration_approved_in_january_expires_december_31()
    {
        Carbon::setTestNow('2025-01-15 10:00:00');

        $registration = Registration::factory()->create([
            'memberName' => 'January Member',
            'email' => 'jan@test.com',
            'login_status' => 'pending',
            'renewal_count' => 0,
        ]);

        // Simulate approval
        $registration->login_status = 'approved';
        $registration->card_issued_at = now();
        $registration->card_valid_until = $registration->computeCalendarYearValidity();
        $registration->save();

        $this->assertEquals('2025-12-31', $registration->card_valid_until->format('Y-m-d'));
        $this->assertTrue($registration->card_valid_until->isLastOfMonth());
        $this->assertEquals(12, $registration->card_valid_until->month);
    }

    /** @test */
    public function new_registration_approved_in_october_expires_december_31_same_year()
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $registration = Registration::factory()->create([
            'memberName' => 'October Member',
            'email' => 'oct@test.com',
            'login_status' => 'pending',
            'renewal_count' => 0,
        ]);

        // Simulate approval
        $registration->login_status = 'approved';
        $registration->card_issued_at = now();
        $registration->card_valid_until = $registration->computeCalendarYearValidity();
        $registration->save();

        // Even though approved in October, card expires end of same year
        $this->assertEquals('2025-12-31', $registration->card_valid_until->format('Y-m-d'));
        
        // This means only ~2.5 months validity for late-year registrations
        // Client requirement: All cards expire Dec 31, regardless of join date
        $daysValid = now()->diffInDays($registration->card_valid_until);
        $this->assertLessThan(75, $daysValid); // Less than 3 months
    }

    /** @test */
    public function renewal_approved_before_expiry_extends_to_current_year_end()
    {
        Carbon::setTestNow('2025-11-15 10:00:00');

        $registration = Registration::factory()->create([
            'memberName' => 'Renewal Member',
            'email' => 'renewal@test.com',
            'renewal_status' => 'pending',
            'card_valid_until' => '2025-12-31', // Expires end of this year
        ]);

        // Simulate renewal approval
        $registration->renewal_status = 'approved';
        $registration->last_renewed_at = now();
        $registration->card_valid_until = $registration->computeCalendarYearValidity();
        $registration->save();

        // Renewed in November 2025, expires Dec 31, 2025 (current year end)
        $this->assertEquals('2025-12-31', $registration->card_valid_until->format('Y-m-d'));
    }

    /** @test */
    public function renewal_approved_after_expiry_extends_to_current_year_end()
    {
        Carbon::setTestNow('2026-02-15 10:00:00'); // February 2026

        $registration = Registration::factory()->create([
            'memberName' => 'Late Renewal Member',
            'email' => 'late@test.com',
            'renewal_status' => 'pending',
            'card_valid_until' => '2025-12-31', // Expired last year
        ]);

        // Simulate late renewal approval (approved in 2026 after expiring in 2025)
        $registration->renewal_status = 'approved';
        $registration->last_renewed_at = now();
        $registration->card_valid_until = $registration->computeCalendarYearValidity();
        $registration->save();

        // Should extend to end of CURRENT year (2026), not next year
        $this->assertEquals('2026-12-31', $registration->card_valid_until->format('Y-m-d'));
    }

    /** @test */
    public function compute_calendar_year_validity_uses_last_renewed_date_if_available()
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $registration = Registration::factory()->create([
            'memberName' => 'Test Member',
            'email' => 'test@test.com',
            'last_renewed_at' => '2025-01-15 10:00:00',
            'card_issued_at' => '2024-01-01 10:00:00',
        ]);

        $validity = $registration->computeCalendarYearValidity();

        // Should use last_renewed_at (2025) to compute end of year
        $this->assertEquals('2025-12-31', $validity->format('Y-m-d'));
    }

    /** @test */
    public function compute_calendar_year_validity_falls_back_to_card_issued_at()
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $registration = Registration::factory()->create([
            'memberName' => 'Test Member',
            'email' => 'test@test.com',
            'last_renewed_at' => null,
            'card_issued_at' => '2025-03-15 10:00:00',
        ]);

        $validity = $registration->computeCalendarYearValidity();

        // Should use card_issued_at (2025) to compute end of year
        $this->assertEquals('2025-12-31', $validity->format('Y-m-d'));
    }

    /** @test */
    public function compute_calendar_year_validity_defaults_to_now()
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $registration = Registration::factory()->create([
            'memberName' => 'Test Member',
            'email' => 'test@test.com',
            'last_renewed_at' => null,
            'card_issued_at' => null,
        ]);

        $validity = $registration->computeCalendarYearValidity();

        // Should use current date (now) to compute end of year
        $this->assertEquals('2025-12-31', $validity->format('Y-m-d'));
    }

    /** @test */
    public function compute_calendar_year_validity_with_custom_base_date()
    {
        $customDate = Carbon::parse('2026-06-15 10:00:00');

        $registration = Registration::factory()->create([
            'memberName' => 'Test Member',
            'email' => 'test@test.com',
        ]);

        $validity = $registration->computeCalendarYearValidity($customDate);

        // Should use custom date (2026) to compute end of year
        $this->assertEquals('2026-12-31', $validity->format('Y-m-d'));
    }

    /** @test */
    public function booted_method_sets_calendar_year_validity_on_first_approval()
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $registration = Registration::factory()->create([
            'memberName' => 'New Member',
            'email' => 'new@test.com',
            'login_status' => 'pending',
            'card_valid_until' => null,
            'renewal_count' => 0,
        ]);

        // Approve the registration (triggers booted method)
        $registration->login_status = 'approved';
        $registration->save();

        $registration->refresh();

        // Should automatically set to end of current year via booted() method
        $this->assertNotNull($registration->card_valid_until);
        $this->assertEquals('2025-12-31', $registration->card_valid_until->format('Y-m-d'));
    }

    /** @test */
    public function booted_method_does_not_override_existing_card_validity()
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $existingValidity = '2025-11-30';
        $registration = Registration::factory()->create([
            'memberName' => 'Existing Member',
            'email' => 'existing@test.com',
            'login_status' => 'pending',
            'card_valid_until' => $existingValidity,
            'renewal_count' => 1, // Already renewed once
        ]);

        // Changing status should not override existing validity
        $registration->login_status = 'approved';
        $registration->save();

        $registration->refresh();

        // Should keep the existing validity date
        $this->assertEquals($existingValidity, $registration->card_valid_until->format('Y-m-d'));
    }

    /** @test */
    public function all_members_expire_on_december_31_regardless_of_join_date()
    {
        Carbon::setTestNow('2025-06-15 10:00:00');

        // Create members joining in different months
        $months = [1, 3, 6, 9, 11, 12];
        $members = [];

        foreach ($months as $month) {
            Carbon::setTestNow("2025-{$month}-15 10:00:00");
            
            $member = Registration::factory()->create([
                'memberName' => "Member Month {$month}",
                'email' => "member{$month}@test.com",
                'login_status' => 'pending',
                'renewal_count' => 0,
            ]);

            // Approve
            $member->login_status = 'approved';
            $member->card_issued_at = now();
            $member->card_valid_until = $member->computeCalendarYearValidity();
            $member->save();

            $members[] = $member->fresh();
        }

        // Assert ALL members expire on Dec 31 of the same year
        foreach ($members as $member) {
            $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
            $this->assertEquals(12, $member->card_valid_until->month);
            $this->assertEquals(31, $member->card_valid_until->day);
        }
    }

    /** @test */
    public function renewal_in_december_extends_to_same_year_december_31()
    {
        Carbon::setTestNow('2025-12-25 10:00:00'); // Christmas 2025

        $registration = Registration::factory()->create([
            'memberName' => 'December Renewal',
            'email' => 'dec@test.com',
            'renewal_status' => 'pending',
            'card_valid_until' => '2025-12-31',
        ]);

        // Renew on Dec 25
        $registration->renewal_status = 'approved';
        $registration->last_renewed_at = now();
        $registration->card_valid_until = $registration->computeCalendarYearValidity();
        $registration->save();

        // Should still be Dec 31 of current year (only 6 days validity!)
        $this->assertEquals('2025-12-31', $registration->card_valid_until->format('Y-m-d'));
    }
}

