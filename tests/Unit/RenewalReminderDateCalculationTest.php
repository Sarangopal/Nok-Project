<?php

namespace Tests\Unit;

use App\Models\Registration;
use App\Models\RenewalReminder;
use App\Mail\RenewalReminderMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Test the EXACT date calculation fix for renewal reminders.
 * 
 * This verifies the fix where reminders were being sent 1 day early.
 * For example, a card expiring Nov 17 should trigger 15-day reminder on Nov 2, NOT Nov 1.
 */
class RenewalReminderDateCalculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the critical fix: Card expiring Nov 17 should trigger on Nov 2 (15 days), not Nov 1
     */
    public function test_nov_17_expiry_sends_15_day_reminder_on_nov_2_not_nov_1(): void
    {
        Mail::fake();

        // Create member with card expiring Nov 17
        $member = Registration::factory()->create([
            'email' => 'nov17@test.com',
            'login_status' => 'approved',
            'card_valid_until' => '2025-11-17',
        ]);

        // Test Nov 1 - Should NOT send (16 days remaining)
        Carbon::setTestNow('2025-11-01 00:00:00');
        Artisan::call('members:send-renewal-reminders', ['--days' => '15']);
        
        Mail::assertNotSent(RenewalReminderMail::class);

        // Test Nov 2 - SHOULD send (15 days remaining)
        Mail::fake(); // Reset
        Carbon::setTestNow('2025-11-02 00:00:00');
        Artisan::call('members:send-renewal-reminders', ['--days' => '15']);
        
        Mail::assertSent(RenewalReminderMail::class, 1);
        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email);
        });
    }

    /**
     * Test exact date calculation for all intervals
     */
    public function test_exact_date_calculation_for_all_intervals(): void
    {
        Mail::fake();
        Carbon::setTestNow('2025-11-01 00:00:00');

        // Create members with precise expiry dates
        $members = [
            'day30' => Registration::factory()->create([
                'email' => 'day30@test.com',
                'login_status' => 'approved',
                'card_valid_until' => '2025-12-01', // Exactly 30 days from Nov 1
            ]),
            'day15' => Registration::factory()->create([
                'email' => 'day15@test.com',
                'login_status' => 'approved',
                'card_valid_until' => '2025-11-16', // Exactly 15 days from Nov 1
            ]),
            'day7' => Registration::factory()->create([
                'email' => 'day7@test.com',
                'login_status' => 'approved',
                'card_valid_until' => '2025-11-08', // Exactly 7 days from Nov 1
            ]),
            'day1' => Registration::factory()->create([
                'email' => 'day1@test.com',
                'login_status' => 'approved',
                'card_valid_until' => '2025-11-02', // Exactly 1 day from Nov 1
            ]),
            'day0' => Registration::factory()->create([
                'email' => 'day0@test.com',
                'login_status' => 'approved',
                'card_valid_until' => '2025-11-01', // Today
            ]),
        ];

        // Create members that should NOT receive reminders (off by 1 day)
        Registration::factory()->create([
            'email' => 'not30@test.com',
            'login_status' => 'approved',
            'card_valid_until' => '2025-12-02', // 31 days - should NOT send
        ]);
        
        Registration::factory()->create([
            'email' => 'not15@test.com',
            'login_status' => 'approved',
            'card_valid_until' => '2025-11-17', // 16 days - should NOT send
        ]);

        // Run the command
        Artisan::call('members:send-renewal-reminders');

        // Should send exactly 5 emails (not 7)
        Mail::assertSent(RenewalReminderMail::class, 5);

        // Verify each correct member got email
        foreach ($members as $key => $member) {
            Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
                return $mail->hasTo($member->email);
            });
        }

        // Verify incorrect dates did NOT get emails
        Mail::assertNotSent(RenewalReminderMail::class, function ($mail) {
            return $mail->hasTo('not30@test.com') || $mail->hasTo('not15@test.com');
        });
    }

    /**
     * Test that expired cards are handled correctly
     */
    public function test_expired_cards_get_reminder(): void
    {
        Mail::fake();
        Carbon::setTestNow('2025-11-01 00:00:00');

        $expiredMember = Registration::factory()->create([
            'email' => 'expired@test.com',
            'login_status' => 'approved',
            'card_valid_until' => '2025-10-31', // Expired yesterday
        ]);

        Artisan::call('members:send-renewal-reminders', ['--days' => '-1']);

        Mail::assertSent(RenewalReminderMail::class, 1);
        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($expiredMember) {
            return $mail->hasTo($expiredMember->email) && $mail->daysLeft === -1;
        });
    }

    /**
     * Test duplicate prevention - same reminder shouldn't be sent twice
     */
    public function test_duplicate_reminder_prevention(): void
    {
        Mail::fake();
        Carbon::setTestNow('2025-11-01 00:00:00');

        $member = Registration::factory()->create([
            'email' => 'duplicate@test.com',
            'login_status' => 'approved',
            'card_valid_until' => '2025-12-01', // 30 days
        ]);

        // First run - should send
        Artisan::call('members:send-renewal-reminders', ['--days' => '30']);
        Mail::assertSent(RenewalReminderMail::class, 1);

        // Create the reminder log manually (simulating first run)
        RenewalReminder::create([
            'registration_id' => $member->id,
            'member_name' => $member->memberName,
            'email' => $member->email,
            'card_valid_until' => $member->card_valid_until,
            'days_before_expiry' => 30,
            'status' => 'sent',
        ]);

        // Second run - should NOT send (duplicate)
        Mail::fake(); // Reset
        Artisan::call('members:send-renewal-reminders', ['--days' => '30']);
        Mail::assertNotSent(RenewalReminderMail::class);
    }

    /**
     * Test carbon date calculation with various timezones
     */
    public function test_date_calculation_at_different_times_of_day(): void
    {
        Mail::fake();

        $member = Registration::factory()->create([
            'email' => 'timezone@test.com',
            'login_status' => 'approved',
            'card_valid_until' => '2025-11-16', // 15 days from Nov 1
        ]);

        // Test at midnight
        Carbon::setTestNow('2025-11-01 00:00:00');
        Artisan::call('members:send-renewal-reminders', ['--days' => '15']);
        Mail::assertSent(RenewalReminderMail::class, 1);

        // Test at noon (should not send again due to duplicate prevention)
        Mail::fake();
        RenewalReminder::where('email', 'timezone@test.com')->delete(); // Remove log
        
        Carbon::setTestNow('2025-11-01 12:00:00');
        Artisan::call('members:send-renewal-reminders', ['--days' => '15']);
        Mail::assertSent(RenewalReminderMail::class, 1);

        // Test at 11:59 PM (should not send again)
        Mail::fake();
        RenewalReminder::where('email', 'timezone@test.com')->delete();
        
        Carbon::setTestNow('2025-11-01 23:59:59');
        Artisan::call('members:send-renewal-reminders', ['--days' => '15']);
        Mail::assertSent(RenewalReminderMail::class, 1);
    }

    /**
     * Test that only approved members get reminders
     */
    public function test_only_approved_members_get_reminders(): void
    {
        Mail::fake();
        Carbon::setTestNow('2025-11-01 00:00:00');

        // Approved member
        Registration::factory()->create([
            'email' => 'approved@test.com',
            'login_status' => 'approved',
            'card_valid_until' => '2025-12-01',
        ]);

        // Pending member
        Registration::factory()->create([
            'email' => 'pending@test.com',
            'login_status' => 'pending',
            'card_valid_until' => '2025-12-01',
        ]);

        // Rejected member
        Registration::factory()->create([
            'email' => 'rejected@test.com',
            'login_status' => 'rejected',
            'card_valid_until' => '2025-12-01',
        ]);

        Artisan::call('members:send-renewal-reminders', ['--days' => '30']);

        // Only 1 email should be sent (approved member)
        Mail::assertSent(RenewalReminderMail::class, 1);
        Mail::assertSent(RenewalReminderMail::class, function ($mail) {
            return $mail->hasTo('approved@test.com');
        });
    }

    /**
     * Test reminder logs are created correctly
     */
    public function test_reminder_logs_created_in_database(): void
    {
        Mail::fake();
        Carbon::setTestNow('2025-11-01 00:00:00');

        $member = Registration::factory()->create([
            'email' => 'logger@test.com',
            'memberName' => 'Logger Member',
            'login_status' => 'approved',
            'card_valid_until' => '2025-12-01',
        ]);

        Artisan::call('members:send-renewal-reminders', ['--days' => '30']);

        // Check database log
        $this->assertDatabaseHas('renewal_reminders', [
            'registration_id' => $member->id,
            'email' => 'logger@test.com',
            'member_name' => 'Logger Member',
            'days_before_expiry' => 30,
            'status' => 'sent',
            'card_valid_until' => '2025-12-01',
        ]);
    }
}

