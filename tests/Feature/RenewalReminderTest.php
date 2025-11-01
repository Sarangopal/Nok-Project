<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Registration;
use App\Mail\RenewalReminderMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class RenewalReminderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake(); // Prevent actual emails from being sent
    }

    /** @test */
    public function it_sends_reminder_30_days_before_expiry()
    {
        // Create a member whose card expires in exactly 30 days
        $member = Registration::factory()->create([
            'memberName' => 'Test Member 30 Days',
            'email' => 'member30@test.com',
            'civil_id' => '30001122334455',
            'password' => bcrypt('password'),
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->addDays(30)->toDateString(),
        ]);

        // Run the command
        Artisan::call('members:send-renewal-reminders');

        // Assert email was sent
        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email) && $mail->registration->id === $member->id;
        });
    }

    /** @test */
    public function it_sends_reminder_15_days_before_expiry()
    {
        $member = Registration::factory()->create([
            'memberName' => 'Test Member 15 Days',
            'email' => 'member15@test.com',
            'civil_id' => '15001122334455',
            'password' => bcrypt('password'),
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->addDays(15)->toDateString(),
        ]);

        Artisan::call('members:send-renewal-reminders');

        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email) && $mail->registration->id === $member->id;
        });
    }

    /** @test */
    public function it_sends_reminder_7_days_before_expiry()
    {
        $member = Registration::factory()->create([
            'memberName' => 'Test Member 7 Days',
            'email' => 'member7@test.com',
            'civil_id' => '70001122334455',
            'password' => bcrypt('password'),
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->addDays(7)->toDateString(),
        ]);

        Artisan::call('members:send-renewal-reminders');

        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email) && $mail->registration->id === $member->id;
        });
    }

    /** @test */
    public function it_sends_reminder_1_day_before_expiry()
    {
        $member = Registration::factory()->create([
            'memberName' => 'Test Member 1 Day',
            'email' => 'member1@test.com',
            'civil_id' => '10001122334455',
            'password' => bcrypt('password'),
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->addDays(1)->toDateString(),
        ]);

        Artisan::call('members:send-renewal-reminders');

        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email) && $mail->registration->id === $member->id;
        });
    }

    /** @test */
    public function it_sends_reminder_on_expiry_day()
    {
        $member = Registration::factory()->create([
            'memberName' => 'Test Member Expiring Today',
            'email' => 'membertoday@test.com',
            'civil_id' => '00001122334455',
            'password' => bcrypt('password'),
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->toDateString(), // Expiring TODAY
        ]);

        Artisan::call('members:send-renewal-reminders');

        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email) && $mail->registration->id === $member->id;
        });
    }

    /** @test */
    public function it_does_not_send_reminder_to_pending_members()
    {
        // Member with pending renewal status should NOT receive reminders
        Registration::factory()->create([
            'memberName' => 'Pending Member',
            'email' => 'pending@test.com',
            'civil_id' => '99001122334455',
            'password' => bcrypt('password'),
            'login_status' => 'approved',
            'renewal_status' => 'pending', // PENDING - should not get reminder
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->addDays(30)->toDateString(),
        ]);

        Artisan::call('members:send-renewal-reminders');

        Mail::assertNotSent(RenewalReminderMail::class, function ($mail) {
            return $mail->hasTo('pending@test.com');
        });
    }

    /** @test */
    public function it_does_not_send_reminder_to_rejected_login_members()
    {
        // Members with rejected login_status should NOT receive reminders
        Registration::factory()->create([
            'memberName' => 'Rejected Login Member',
            'email' => 'rejected@test.com',
            'civil_id' => '88001122334455',
            'password' => bcrypt('password'),
            'login_status' => 'rejected',
            'renewal_status' => 'pending', // renewal_status only has 'pending' or 'approved'
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->addDays(30)->toDateString(),
        ]);

        Artisan::call('members:send-renewal-reminders');

        Mail::assertNotSent(RenewalReminderMail::class, function ($mail) {
            return $mail->hasTo('rejected@test.com');
        });
    }

    /** @test */
    public function it_does_not_send_reminder_to_members_expiring_in_10_days()
    {
        // Member expiring in 10 days should NOT get reminder (not in 30,15,7,1,0 list)
        $member = Registration::factory()->create([
            'memberName' => 'Member 10 Days',
            'email' => 'member10@test.com',
            'civil_id' => '10101122334455',
            'password' => bcrypt('password'),
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->addDays(10)->toDateString(),
        ]);

        Artisan::call('members:send-renewal-reminders');

        Mail::assertNotSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email);
        });
    }

    /** @test */
    public function it_sends_multiple_reminders_on_same_day()
    {
        // Create multiple members expiring on same day
        $member1 = Registration::factory()->create([
            'email' => 'member1_30@test.com',
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addDays(30)->toDateString(),
        ]);

        $member2 = Registration::factory()->create([
            'email' => 'member2_30@test.com',
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addDays(30)->toDateString(),
        ]);

        Artisan::call('members:send-renewal-reminders');

        // Assert both members got emails
        Mail::assertSent(RenewalReminderMail::class, 2);
        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member1) {
            return $mail->hasTo($member1->email);
        });
        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member2) {
            return $mail->hasTo($member2->email);
        });
    }

    /** @test */
    public function it_handles_all_reminder_intervals_simultaneously()
    {
        // Create members for all 5 reminder intervals
        $members = [
            Registration::factory()->create([
                'email' => 'day30@test.com',
                'login_status' => 'approved',
                'renewal_status' => 'approved',
                'card_valid_until' => now()->addDays(30)->toDateString(),
            ]),
            Registration::factory()->create([
                'email' => 'day15@test.com',
                'login_status' => 'approved',
                'renewal_status' => 'approved',
                'card_valid_until' => now()->addDays(15)->toDateString(),
            ]),
            Registration::factory()->create([
                'email' => 'day7@test.com',
                'login_status' => 'approved',
                'renewal_status' => 'approved',
                'card_valid_until' => now()->addDays(7)->toDateString(),
            ]),
            Registration::factory()->create([
                'email' => 'day1@test.com',
                'login_status' => 'approved',
                'renewal_status' => 'approved',
                'card_valid_until' => now()->addDays(1)->toDateString(),
            ]),
            Registration::factory()->create([
                'email' => 'day0@test.com',
                'login_status' => 'approved',
                'renewal_status' => 'approved',
                'card_valid_until' => now()->toDateString(),
            ]),
        ];

        Artisan::call('members:send-renewal-reminders');

        // Assert all 5 emails were sent
        Mail::assertSent(RenewalReminderMail::class, 5);

        foreach ($members as $member) {
            Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
                return $mail->hasTo($member->email);
            });
        }
    }
}

