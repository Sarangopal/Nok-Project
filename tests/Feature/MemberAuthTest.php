<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_login_with_email_civil_id_and_password(): void
    {
        $member = Member::create([
            'member_type' => 'new',
            'memberName' => 'Sam',
            'email' => 'sam@example.com',
            'civil_id' => 'CIV1234',
            'age' => 30,
            'gender' => 'M',
            'mobile' => '50000000',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addMonth(),
            'password' => 'NOK1234',
        ]);

        $response = $this->post(route('member.login.perform'), [
            'email' => 'sam@example.com',
            'civil_id' => 'CIV1234',
            'password' => 'NOK1234',
        ]);

        $response->assertRedirect(route('member.dashboard'));
        $this->assertAuthenticatedAs($member, 'members');
    }

    public function test_member_login_fails_with_wrong_civil_id(): void
    {
        Member::create([
            'member_type' => 'new',
            'memberName' => 'Dana',
            'email' => 'dana@example.com',
            'civil_id' => 'CIVX',
            'age' => 28,
            'gender' => 'F',
            'mobile' => '51111111',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addMonth(),
            'password' => 'NOK5678',
        ]);

        $response = $this->from(route('member.login'))
            ->post(route('member.login.perform'), [
                'email' => 'dana@example.com',
                'civil_id' => 'WRONG',
                'password' => 'NOK5678',
            ]);

        $response->assertRedirect(route('member.login'));
        $response->assertSessionHasErrors('civil_id');
        $this->assertGuest('members');
    }
}











