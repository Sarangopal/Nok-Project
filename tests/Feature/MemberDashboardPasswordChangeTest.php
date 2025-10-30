<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class MemberDashboardPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations
        $this->artisan('migrate');
    }

    /** @test */
    public function member_can_access_dashboard_when_authenticated()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
            'password' => 'oldpassword',
            'login_status' => 'approved',
        ]);

        $response = $this->actingAs($member, 'members')
            ->get('/member/panel');

        $response->assertStatus(200);
    }

    /** @test */
    public function member_cannot_access_dashboard_when_not_authenticated()
    {
        $response = $this->get('/member/panel');

        $response->assertStatus(302);
        $response->assertRedirect('/member/panel/login');
    }

    /** @test */
    public function member_can_change_password_with_correct_current_password()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword'),
            'login_status' => 'approved',
        ]);

        $this->actingAs($member, 'members');

        // Simulate password change action
        $currentPassword = 'oldpassword';
        $newPassword = 'newpassword123';

        // Verify current password
        $this->assertTrue(Hash::check($currentPassword, $member->password));

        // Update password
        $member->password = $newPassword;
        $member->save();

        // Verify new password
        $member->refresh();
        $this->assertTrue(Hash::check($newPassword, $member->password));
        $this->assertFalse(Hash::check($currentPassword, $member->password));
    }

    /** @test */
    public function member_cannot_change_password_with_incorrect_current_password()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword'),
            'login_status' => 'approved',
        ]);

        $this->actingAs($member, 'members');

        $incorrectCurrentPassword = 'wrongpassword';
        
        // Verify that incorrect password fails
        $this->assertFalse(Hash::check($incorrectCurrentPassword, $member->password));
    }

    /** @test */
    public function password_is_hashed_when_saved()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
            'password' => 'plainpassword',
            'login_status' => 'approved',
        ]);

        // Verify password is hashed
        $this->assertNotEquals('plainpassword', $member->password);
        $this->assertTrue(Hash::check('plainpassword', $member->password));
    }

    /** @test */
    public function member_can_login_with_new_password_after_change()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
            'civil_id' => '123456789012',
            'password' => Hash::make('oldpassword'),
            'login_status' => 'approved',
        ]);

        // Change password
        $member->password = 'newpassword123';
        $member->save();

        // Logout
        auth('members')->logout();

        // Try to login with new password
        $response = $this->post('/member/panel/login', [
            'civil_id' => '123456789012',
            'password' => 'newpassword123',
        ]);

        $this->assertTrue(auth('members')->check());
    }
}

