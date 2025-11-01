<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PublicVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_page_is_accessible(): void
    {
        $response = $this->get(route('verify-member.form'));
        $response->assertStatus(200);
        $response->assertSee('Membership Verification');
    }

    public function test_can_verify_active_member_with_civil_id(): void
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $member = Registration::factory()->create([
            'civil_id' => 'CIV12345',
            'memberName' => 'John Doe',
            'nok_id' => 'NOK001',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addMonths(6),
        ]);

        $response = $this->post(route('verify-member.post'), [
            'civil_id' => 'CIV12345',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Active Member');
        $response->assertSee('John Doe');
        $response->assertSee('NOK001');
        $response->assertSee('🟢');
    }

    public function test_shows_expired_status_for_expired_membership(): void
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $member = Registration::factory()->create([
            'civil_id' => 'CIV99999',
            'memberName' => 'Jane Smith',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->subDay(),
        ]);

        $response = $this->post(route('verify-member.post'), [
            'civil_id' => 'CIV99999',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Expired');
        $response->assertSee('Jane Smith');
        $response->assertSee('🔴');
    }

    public function test_shows_pending_status_for_unapproved_member(): void
    {
        $member = Registration::factory()->create([
            'civil_id' => 'CIV88888',
            'renewal_status' => 'pending',
        ]);

        $response = $this->post(route('verify-member.post'), [
            'civil_id' => 'CIV88888',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Pending Approval');
        $response->assertSee('⚪');
    }

    public function test_shows_not_found_for_invalid_civil_id(): void
    {
        $response = $this->post(route('verify-member.post'), [
            'civil_id' => 'INVALID123',
        ]);

        $response->assertStatus(200);
        $response->assertSee('No member found');
        $response->assertSee('❌');
    }

    public function test_can_verify_with_email_double_verification(): void
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $member = Registration::factory()->create([
            'civil_id' => 'CIV77777',
            'email' => 'test@example.com',
            'memberName' => 'Test User',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addMonths(3),
        ]);

        $response = $this->post(route('verify-member.post'), [
            'civil_id' => 'CIV77777',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Active Member');
        $response->assertSee('Test User');
    }

    public function test_email_mismatch_shows_not_found(): void
    {
        $member = Registration::factory()->create([
            'civil_id' => 'CIV66666',
            'email' => 'correct@example.com',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addMonths(3),
        ]);

        $response = $this->post(route('verify-member.post'), [
            'civil_id' => 'CIV66666',
            'email' => 'wrong@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertSee('No member found');
    }

    public function test_civil_id_is_required(): void
    {
        $response = $this->post(route('verify-member.post'), [
            'civil_id' => '',
        ]);

        $response->assertSessionHasErrors('civil_id');
    }

    public function test_rate_limiting_works(): void
    {
        // Make 11 requests (limit is 10 per minute)
        for ($i = 0; $i < 11; $i++) {
            $response = $this->post(route('verify-member.post'), [
                'civil_id' => 'TEST' . $i,
            ]);
            
            if ($i < 10) {
                $response->assertStatus(200);
            } else {
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }
}


