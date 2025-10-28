<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_active_member_shows_active_message(): void
    {
        $reg = Registration::create([
            'member_type' => 'new',
            'memberName' => 'Active User',
            'email' => 'active@example.com',
            'civil_id' => 'ACT123',
            'age' => 32,
            'gender' => 'M',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addMonth(),
        ]);

        $response = $this->post(route('verify-member.post'), [
            'civil_id' => 'ACT123',
        ]);

        $response->assertOk();
        $response->assertSee('Active Member');
    }

    public function test_verify_expired_member_shows_expired_message(): void
    {
        $reg = Registration::create([
            'member_type' => 'new',
            'memberName' => 'Expired User',
            'email' => 'expired@example.com',
            'civil_id' => 'EXP123',
            'age' => 32,
            'gender' => 'M',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->subDay(),
        ]);

        $response = $this->post(route('verify-member.post'), [
            'civil_id' => 'EXP123',
        ]);

        $response->assertOk();
        $response->assertSee('Expired');
    }
}







