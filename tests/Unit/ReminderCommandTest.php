<?php

namespace Tests\Unit;

use App\Console\Commands\SendRenewalReminders;
use App\Mail\RenewalReminderMail;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReminderCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_picks_up_members_with_matching_dates(): void
    {
        Carbon::setTestNow('2025-10-01 00:00:00');
        Mail::fake();

        // Create members expiring in 30 and 15 days
        $m1 = Registration::factory()->create([
            'email' => 'member1@test.com',
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-10-31', // 30 days from now
        ]);

        $m2 = Registration::factory()->create([
            'email' => 'member2@test.com',
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-10-16', // 15 days from now
        ]);

        // Verify data is correct
        $this->assertDatabaseHas('registrations', [
            'email' => 'member1@test.com',
            'card_valid_until' => '2025-10-31',
        ]);

        $exitCode = Artisan::call('members:send-renewal-reminders', [
            '--days' => '30,15',
        ]);

        $this->assertSame(0, $exitCode);
        Mail::assertSent(RenewalReminderMail::class, 2);
    }

    public function test_command_sends_for_30_15_7_1_and_0_days(): void
    {
        Carbon::setTestNow('2025-10-01 00:00:00');
        Mail::fake();

        // 30, 15, 7, 1 days and same day (0)
        Registration::factory()->create([
            'email' => 'd30@test.com',
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-10-31',
        ]);

        Registration::factory()->create([
            'email' => 'd15@test.com',
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-10-16',
        ]);

        Registration::factory()->create([
            'email' => 'd07@test.com',
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-10-08',
        ]);

        Registration::factory()->create([
            'email' => 'd01@test.com',
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-10-02',
        ]);

        Registration::factory()->create([
            'email' => 'd00@test.com',
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-10-01', // expiry today
        ]);

        $exitCode = Artisan::call('members:send-renewal-reminders', [
            '--days' => '30,15,7,1,0',
        ]);

        $this->assertSame(0, $exitCode);
        Mail::assertSent(RenewalReminderMail::class, 5);
    }

    public function test_final_expiry_day_zero_sends_email(): void
    {
        Carbon::setTestNow('2025-10-01 00:00:00');
        Mail::fake();

        Registration::factory()->create([
            'email' => 'final@test.com',
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-10-01', // expiry today
        ]);

        $exitCode = Artisan::call('members:send-renewal-reminders', [
            '--days' => '0',
        ]);

        $this->assertSame(0, $exitCode);
        Mail::assertSent(RenewalReminderMail::class, 1);
    }
}


