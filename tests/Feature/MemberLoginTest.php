<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MemberLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_cannot_login_if_not_approved(): void
    {
        $member = Member::create([
            'member_type' => 'new',
            'memberName' => 'Alice',
            'email' => 'alice@example.com',
            'civil_id' => 'CIV123',
            'age' => 30,
            'gender' => 'F',
            'mobile' => '50000000',
            'renewal_status' => 'pending',
            'password' => Hash::make('secret'),
        ]);

        $response = $this->post(route('member.login.perform'), [
            'email' => 'alice@example.com',
            'civil_id' => 'CIV123',
            'password' => 'secret',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    public function test_member_cannot_login_if_expired(): void
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $member = Member::create([
            'member_type' => 'new',
            'memberName' => 'Bob',
            'email' => 'bob@example.com',
            'civil_id' => 'CIV999',
            'age' => 32,
            'gender' => 'M',
            'mobile' => '51111111',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->subDay(),
            'password' => Hash::make('secret'),
        ]);

        $response = $this->post(route('member.login.perform'), [
            'email' => 'bob@example.com',
            'civil_id' => 'CIV999',
            'password' => 'secret',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    public function test_member_can_login_when_approved_and_valid(): void
    {
        Carbon::setTestNow('2025-10-21 10:00:00');

        $member = Member::create([
            'member_type' => 'new',
            'memberName' => 'Carol',
            'email' => 'carol@example.com',
            'civil_id' => 'CIV777',
            'age' => 28,
            'gender' => 'F',
            'mobile' => '52222222',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addMonth(),
            'password' => Hash::make('secret'),
        ]);

        $response = $this->post(route('member.login.perform'), [
            'email' => 'carol@example.com',
            'civil_id' => 'CIV777',
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('member.dashboard'));
        $this->assertAuthenticatedAs($member, 'members');
    }
}


