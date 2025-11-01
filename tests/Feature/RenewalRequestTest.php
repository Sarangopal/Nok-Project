<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RenewalRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_submit_renewal_request(): void
    {
        $member = Member::create([
            'member_type' => 'new',
            'memberName' => 'Soon Expiring',
            'email' => 'soon@example.com',
            'civil_id' => 'RENEW1',
            'age' => 29,
            'gender' => 'F',
            'mobile' => '52222222',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->subDay(),
            'password' => 'NOK0001',
        ]);

        $this->actingAs($member, 'members')
            ->post(route('member.renewal.request'))
            ->assertRedirect();

        $member->refresh();
        $this->assertSame('pending', $member->renewal_status);
        $this->assertNotNull($member->renewal_requested_at);
    }
}











