<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MemberPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations
        $this->artisan('migrate');
    }

    /** @test */
    public function member_can_view_password_reset_request_form()
    {
        $response = $this->get('/member/password/reset');
        
        $response->assertStatus(200);
        $response->assertSee('Reset Your Password');
        $response->assertSee('Email Address');
    }

    /** @test */
    public function member_can_request_password_reset_link()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
            'login_status' => 'approved',
        ]);

        Notification::fake();

        $response = $this->post('/member/password/email', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function member_receives_error_for_invalid_email()
    {
        $response = $this->post('/member/password/email', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function member_can_view_password_reset_form_with_valid_token()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
        ]);

        $token = Password::broker('members')->createToken($member);

        $response = $this->get("/member/password/reset/{$token}?email=test@example.com");

        $response->assertStatus(200);
        $response->assertSee('Set New Password');
        $response->assertSee('test@example.com');
    }

    /** @test */
    public function member_can_reset_password_with_valid_token()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
            'password' => 'oldpassword',
            'login_status' => 'approved',
        ]);

        $token = Password::broker('members')->createToken($member);

        $response = $this->post('/member/password/reset', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/member/panel/login');

        // Verify password was changed
        $member->refresh();
        $this->assertTrue(Hash::check('newpassword123', $member->password));
    }

    /** @test */
    public function member_cannot_reset_password_with_invalid_token()
    {
        Member::factory()->create([
            'email' => 'test@example.com',
            'password' => 'oldpassword',
        ]);

        $response = $this->post('/member/password/reset', [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function member_cannot_reset_password_with_mismatched_passwords()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
        ]);

        $token = Password::broker('members')->createToken($member);

        $response = $this->post('/member/password/reset', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function member_cannot_reset_password_with_short_password()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
        ]);

        $token = Password::broker('members')->createToken($member);

        $response = $this->post('/member/password/reset', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_reset_token_expires_after_configured_time()
    {
        $member = Member::factory()->create([
            'email' => 'test@example.com',
        ]);

        $token = Password::broker('members')->createToken($member);

        // Simulate token expiration by manipulating created_at timestamp
        \DB::table('password_reset_tokens')
            ->where('email', 'test@example.com')
            ->update(['created_at' => now()->subHours(2)]);

        $response = $this->post('/member/password/reset', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }
}

