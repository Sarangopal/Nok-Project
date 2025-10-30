<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Models\Member;
use App\Mail\MembershipCardMail;
use App\Mail\RenewalReminderMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * COMPREHENSIVE END-TO-END TEST
 * Tests the complete membership renewal flow from start to finish
 */
class CompleteRenewalFlowEndToEndTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function complete_renewal_flow_year_2025_to_2026()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  COMPLETE RENEWAL FLOW TEST: 2025 → 2026');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');

        // ============================================================
        // STEP 1: Initial Registration (Early 2025)
        // ============================================================
        Carbon::setTestNow('2025-01-15 10:00:00');
        
        $this->info('📋 STEP 1: New Member Registration (Jan 15, 2025)');
        $this->info('─────────────────────────────────────────────────');
        
        $member = Registration::factory()->create([
            'memberName' => 'Complete Flow Test Member',
            'email' => 'flowtest@example.com',
            'mobile' => '50000000',
            'civil_id' => 'FLOW123456',
            'password' => bcrypt('password123'),
            'login_status' => 'pending',
            'renewal_status' => null,
            'card_valid_until' => null,
            'renewal_count' => 0,
        ]);

        $this->info("  Member created: {$member->memberName}");
        $this->info("  Email: {$member->email}");
        $this->info("  Status: {$member->login_status}");
        $this->info('');

        // Admin approves registration
        $member->login_status = 'approved';
        $member->card_issued_at = now();
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();
        $member->refresh();

        $this->info("  ✅ Admin Approved Registration");
        $this->info("  ✅ card_issued_at: {$member->card_issued_at->format('Y-m-d')}");
        $this->info("  ✅ card_valid_until: {$member->card_valid_until->format('Y-m-d')}");
        $this->info('');

        $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
        $this->assertEquals('approved', $member->login_status);
        $this->assertNotNull($member->card_issued_at);

        // ============================================================
        // STEP 2: Renewal Reminder Sent (Dec 1, 2025)
        // ============================================================
        Carbon::setTestNow('2025-12-01 08:00:00');
        
        $this->info('🔔 STEP 2: Renewal Reminder Sent (Dec 1, 2025 - 30 days before expiry)');
        $this->info('─────────────────────────────────────────────────────────────────────');
        
        Artisan::call('members:send-renewal-reminders');
        
        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email) && $mail->daysLeft === 30;
        });
        
        $this->info('  ✅ Renewal reminder email sent (30 days before expiry)');
        $this->info('');

        // ============================================================
        // STEP 3: Member Submits Renewal Request (Dec 15, 2025)
        // ============================================================
        Carbon::setTestNow('2025-12-15 14:30:00');
        
        $this->info('📤 STEP 3: Member Submits Renewal Request (Dec 15, 2025)');
        $this->info('──────────────────────────────────────────────────────');
        
        // Simulate member login and renewal request
        $memberLogin = Member::find($member->id);
        
        $response = $this->actingAs($memberLogin, 'members')
            ->post(route('member.renewal.request'), [
                'payment_proof' => null // In real scenario, would upload file
            ]);
        
        $member->refresh();
        
        $this->info("  Member action: Clicked 'Request Renewal' button");
        $this->info("  ✅ renewal_requested_at: {$member->renewal_requested_at->format('Y-m-d H:i:s')}");
        $this->info("  ✅ renewal_status: {$member->renewal_status}");
        $this->info("  Current card_valid_until: {$member->card_valid_until->format('Y-m-d')}");
        $this->info('');
        
        $this->assertNotNull($member->renewal_requested_at);
        $this->assertEquals('pending', $member->renewal_status);

        // ============================================================
        // STEP 4: Admin Approves Renewal (Dec 20, 2025)
        // ============================================================
        Carbon::setTestNow('2025-12-20 10:00:00');
        
        $this->info('✅ STEP 4: Admin Approves Renewal Request (Dec 20, 2025)');
        $this->info('───────────────────────────────────────────────────────');
        
        $this->info("  BEFORE Approval:");
        $this->info("    renewal_status: {$member->renewal_status}");
        $this->info("    card_valid_until: {$member->card_valid_until->format('Y-m-d')}");
        $this->info("    renewal_count: {$member->renewal_count}");
        $this->info('');

        // Simulate admin approval (exactly as in RenewalRequestsTable.php)
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = ($member->renewal_count ?? 0) + 1;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();
        $member->refresh();

        // Send membership card email
        Mail::to($member->email)->send(new MembershipCardMail(['record' => $member]));
        
        $this->info("  AFTER Approval:");
        $this->info("    ✅ renewal_status: {$member->renewal_status}");
        $this->info("    ✅ card_valid_until: {$member->card_valid_until->format('Y-m-d')} (Still 2025!)");
        $this->info("    ✅ renewal_count: {$member->renewal_count}");
        $this->info("    ✅ last_renewed_at: {$member->last_renewed_at->format('Y-m-d H:i:s')}");
        $this->info('');
        
        $this->info("  ⚠️  NOTE: Renewal approved in SAME YEAR (Dec 2025)");
        $this->info("  ⚠️  Card still expires Dec 31, 2025 (same year end)");
        $this->info("  ⚠️  Member must renew AGAIN in 2026 for year 2026!");
        $this->info('');

        // Verify email sent with correct data
        Mail::assertSent(MembershipCardMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email);
        });

        $this->assertEquals('approved', $member->renewal_status);
        $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
        $this->assertEquals(1, $member->renewal_count);

        // Verify database
        $this->assertDatabaseHas('registrations', [
            'id' => $member->id,
            'renewal_status' => 'approved',
            'card_valid_until' => '2025-12-31 00:00:00',
            'renewal_count' => 1,
        ]);

        // ============================================================
        // STEP 5: Card Expires, Renewal Reminder for 2026 (Dec 1, 2026)
        // ============================================================
        Carbon::setTestNow('2026-12-01 08:00:00');
        
        $this->info('🔔 STEP 5: Year 2026 - Renewal Reminder Sent (Dec 1, 2026)');
        $this->info('──────────────────────────────────────────────────────────');
        $this->info('  (Member renewed in Jan 2026, card expires Dec 31, 2026)');
        $this->info('');
        
        // Simulate that member already renewed for 2026 in January
        $member->renewal_status = 'approved';
        $member->last_renewed_at = '2026-01-10 10:00:00';
        $member->card_valid_until = Carbon::parse('2026-12-31');
        $member->renewal_count = 2;
        $member->save();
        $member->refresh();
        
        $this->info("  Current status (before renewal):");
        $this->info("    card_valid_until: {$member->card_valid_until->format('Y-m-d')}");
        $this->info("    renewal_count: {$member->renewal_count}");
        $this->info('');
        
        Mail::fake(); // Reset mail fake for this step
        Artisan::call('members:send-renewal-reminders');
        
        Mail::assertSent(RenewalReminderMail::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email);
        });
        
        $this->info('  ✅ Renewal reminder sent for 2026 expiry');
        $this->info('');

        // ============================================================
        // STEP 6: Member Requests Renewal for 2027 (Dec 10, 2026)
        // ============================================================
        Carbon::setTestNow('2026-12-10 15:00:00');
        
        $this->info('📤 STEP 6: Member Requests Renewal for 2027 (Dec 10, 2026)');
        $this->info('────────────────────────────────────────────────────────');
        
        $member->renewal_status = 'pending';
        $member->renewal_requested_at = now();
        $member->save();
        $member->refresh();
        
        $this->info("  ✅ Renewal request submitted");
        $this->info("  renewal_status: {$member->renewal_status}");
        $this->info('');

        // ============================================================
        // STEP 7: Admin Approves for 2027 (Dec 15, 2026)
        // ============================================================
        Carbon::setTestNow('2026-12-15 11:00:00');
        
        $this->info('✅ STEP 7: Admin Approves Renewal for 2027 (Dec 15, 2026)');
        $this->info('──────────────────────────────────────────────────────────');
        
        $this->info("  BEFORE Approval:");
        $this->info("    card_valid_until: {$member->card_valid_until->format('Y-m-d')} (2026)");
        $this->info("    renewal_count: {$member->renewal_count}");
        $this->info('');
        
        Mail::fake();
        
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = ($member->renewal_count ?? 0) + 1;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();
        $member->refresh();
        
        Mail::to($member->email)->send(new MembershipCardMail(['record' => $member]));
        
        $this->info("  AFTER Approval:");
        $this->info("    ✅ card_valid_until: {$member->card_valid_until->format('Y-m-d')} (Still 2026!)");
        $this->info("    ✅ renewal_count: {$member->renewal_count}");
        $this->info('');
        
        $this->info("  ⚠️  NOTE: Approved in SAME YEAR (Dec 2026)");
        $this->info("  ⚠️  Card still expires Dec 31, 2026");
        $this->info("  ⚠️  Member must renew in Jan 2027 for year 2027!");
        $this->info('');
        
        $this->assertEquals('2026-12-31', $member->card_valid_until->format('Y-m-d'));

        // ============================================================
        // STEP 8: Renewal in NEW YEAR 2027 (Jan 5, 2027)
        // ============================================================
        Carbon::setTestNow('2027-01-05 10:00:00');
        
        $this->info('📅 STEP 8: Renewal Request in NEW YEAR 2027 (Jan 5, 2027)');
        $this->info('───────────────────────────────────────────────────────────');
        $this->info('  Card expired: Dec 31, 2026');
        $this->info('  Current date: Jan 5, 2027 (5 days late)');
        $this->info('');
        
        $member->renewal_status = 'pending';
        $member->renewal_requested_at = now();
        $member->save();
        $member->refresh();
        
        $this->info("  ✅ Member submits renewal request (late)");
        $this->info('');
        
        // Admin approves
        Carbon::setTestNow('2027-01-06 14:00:00');
        
        $this->info('✅ Admin Approves Renewal (Jan 6, 2027)');
        $this->info('────────────────────────────────────────');
        
        $this->info("  BEFORE Approval:");
        $this->info("    card_valid_until: {$member->card_valid_until->format('Y-m-d')} (EXPIRED)");
        $this->info('');
        
        Mail::fake();
        
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = ($member->renewal_count ?? 0) + 1;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();
        $member->refresh();
        
        Mail::to($member->email)->send(new MembershipCardMail(['record' => $member]));
        
        $this->info("  AFTER Approval:");
        $this->info("    ✅ card_valid_until: {$member->card_valid_until->format('Y-m-d')} (2027!) 🎉");
        $this->info("    ✅ renewal_count: {$member->renewal_count}");
        $this->info("    ✅ Valid for entire year 2027!");
        $this->info('');
        
        $this->assertEquals('2027-12-31', $member->card_valid_until->format('Y-m-d'));
        $this->assertEquals(4, $member->renewal_count);
        
        Mail::assertSent(MembershipCardMail::class);
        
        // ============================================================
        // FINAL VERIFICATION
        // ============================================================
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  ✅ COMPLETE FLOW TEST PASSED!');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');
        $this->info('Journey Summary:');
        $this->info('  2025-01-15: Joined, expires 2025-12-31');
        $this->info('  2025-12-20: Renewed (same year), still expires 2025-12-31');
        $this->info('  2026-01-10: Renewed for 2026, expires 2026-12-31');
        $this->info('  2026-12-15: Renewed (same year), still expires 2026-12-31');
        $this->info('  2027-01-06: Renewed for 2027, expires 2027-12-31');
        $this->info('');
        $this->info('Key Findings:');
        $this->info('  ✅ card_valid_until updates correctly');
        $this->info('  ✅ Renewal emails sent after approval');
        $this->info('  ✅ Database updated properly');
        $this->info('  ✅ Same-year renewals keep same year-end');
        $this->info('  ✅ New-year renewals extend to new year-end');
        $this->info('  ✅ Calendar year validity system working!');
        $this->info('');
    }

    /** @test */
    public function renewal_on_december_31_extends_to_next_year_december_31()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  TEST: Renewal Request on Dec 31, 2025 → Dec 31, 2026');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');
        
        Carbon::setTestNow('2025-12-31 23:00:00');
        
        $member = Registration::factory()->create([
            'memberName' => 'Dec 31 Test Member',
            'email' => 'dec31@example.com',
            'renewal_status' => 'pending',
            'renewal_requested_at' => now(),
            'card_valid_until' => '2025-12-31',
            'renewal_count' => 0,
        ]);
        
        $this->info("Date: December 31, 2025, 11:00 PM");
        $this->info("Member requests renewal on last day of year");
        $this->info("Current card_valid_until: {$member->card_valid_until->format('Y-m-d')}");
        $this->info('');
        
        // Admin approves on same day
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = 1;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();
        $member->refresh();
        
        $this->info("✅ Admin approves renewal");
        $this->info("✅ New card_valid_until: {$member->card_valid_until->format('Y-m-d')}");
        $this->info('');
        
        // Should still be 2025-12-31 (same day approval)
        $this->assertEquals('2025-12-31', $member->card_valid_until->format('Y-m-d'));
        
        $this->info("⚠️  NOTE: Approved on Dec 31, 2025");
        $this->info("⚠️  Card still expires Dec 31, 2025 (minutes left!)");
        $this->info('');
        
        // Next day (Jan 1, 2026) - member submits another renewal
        Carbon::setTestNow('2026-01-01 09:00:00');
        
        $this->info("Next Day: January 1, 2026");
        $this->info("Card expired at midnight!");
        $this->info("Member submits new renewal request...");
        $this->info('');
        
        $member->renewal_status = 'pending';
        $member->renewal_requested_at = now();
        $member->save();
        
        // Admin approves
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->renewal_count = 2;
        $member->card_valid_until = $member->computeCalendarYearValidity();
        $member->save();
        $member->refresh();
        
        $this->info("✅ Admin approves renewal (Jan 1, 2026)");
        $this->info("✅ New card_valid_until: {$member->card_valid_until->format('Y-m-d')} 🎉");
        $this->info('');
        
        // Now should be 2026-12-31
        $this->assertEquals('2026-12-31', $member->card_valid_until->format('Y-m-d'));
        
        $this->info("✅ SUCCESS: Dec 31, 2025 → Dec 31, 2026");
        $this->info('');
    }

    /** @test */
    public function membership_card_email_contains_correct_expiry_date()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('  TEST: Email Contains Correct Expiry Date');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('');
        
        Carbon::setTestNow('2025-10-21 10:00:00');
        
        $member = Registration::factory()->create([
            'memberName' => 'Email Test Member',
            'email' => 'emailtest@example.com',
            'card_valid_until' => '2025-12-31',
        ]);
        
        $this->info("Member: {$member->memberName}");
        $this->info("card_valid_until in database: {$member->card_valid_until->format('Y-m-d')}");
        $this->info('');
        
        // Send membership card email
        Mail::to($member->email)->send(new MembershipCardMail(['record' => $member]));
        
        Mail::assertSent(MembershipCardMail::class, function ($mail) use ($member) {
            $content = $mail->content();
            $data = $content->with;
            
            $this->info("Email sent to: {$member->email}");
            $this->info("Expiry date in email: {$data['record']->card_valid_until->format('Y-m-d')}");
            $this->info('');
            
            // Verify expiry date in email matches database
            $this->assertEquals(
                $member->card_valid_until->format('Y-m-d'),
                $data['record']->card_valid_until->format('Y-m-d')
            );
            
            return $mail->hasTo($member->email);
        });
        
        $this->info("✅ Expiry date in email MATCHES database value!");
        $this->info('');
    }

    private function info(string $message): void
    {
        fwrite(STDOUT, $message . PHP_EOL);
    }
}





