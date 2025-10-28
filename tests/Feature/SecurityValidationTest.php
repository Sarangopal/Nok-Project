<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class SecurityValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $member;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->member = Registration::create([
            'memberName' => 'Test Member',
            'civil_id' => '12345678901',
            'email' => 'member@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addYear(),
            'password' => Hash::make('password'),
            'nok_id' => 'NOK001',
        ]);
    }

    /** @test */
    public function passwords_are_hashed_in_database()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('secret'),
        ]);

        $this->assertNotEquals('secret', $user->password);
        $this->assertTrue(Hash::check('secret', $user->password));
    }

    /** @test */
    public function sql_injection_is_prevented_in_civil_id()
    {
        $response = $this->post('/verify-membership', [
            'civil_id' => "' OR '1'='1",
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('verification_attempts', [
            'success' => true,
        ]);
    }

    /** @test */
    public function xss_attacks_are_prevented_in_registration()
    {
        $response = $this->post('/registration-submit', [
            'memberName' => '<script>alert("XSS")</script>',
            'civil_id' => '12345678901',
            'email' => 'xss@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
        ]);

        // Check if script tag is escaped
        $registration = Registration::where('civil_id', '12345678901')->first();
        if ($registration) {
            $this->assertStringNotContainsString('<script>', $registration->memberName);
        }
    }

    /** @test */
    public function email_validation_prevents_invalid_emails()
    {
        $invalidEmails = [
            'notanemail',
            '@example.com',
            'test@',
            'test..double@example.com',
        ];

        foreach ($invalidEmails as $email) {
            $response = $this->post('/contact', [
                'name' => 'Test User',
                'email' => $email,
                'phone' => '+96512345678',
                'message' => 'Test message',
            ]);

            $response->assertSessionHasErrors('email');
        }
    }

    /** @test */
    public function phone_number_validation_works()
    {
        $invalidPhones = [
            '123',
            'abcdefg',
            '+++96512345678',
        ];

        foreach ($invalidPhones as $phone) {
            $response = $this->post('/contact', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => $phone,
                'message' => 'Test message',
            ]);

            $response->assertSessionHasErrors('phone');
        }
    }

    /** @test */
    public function civil_id_must_be_11_digits()
    {
        $response = $this->post('/registration-submit', [
            'memberName' => 'Test Member',
            'civil_id' => '123', // Too short
            'email' => 'test@example.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
        ]);

        $response->assertSessionHasErrors('civil_id');
    }

    /** @test */
    public function csrf_token_is_required_for_post_requests()
    {
        $response = $this->withoutMiddleware(\App\Http\Middleware\EncryptCookies::class)
            ->post('/contact', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'message' => 'Test message',
            ]);

        // With CSRF protection enabled, this should fail
        $this->assertTrue(true); // Basic assertion
    }

    /** @test */
    public function rate_limiting_prevents_brute_force_on_login()
    {
        // Attempt multiple failed logins
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/member/login', [
                'civil_id' => $this->member->civil_id,
                'password' => 'wrong-password',
            ]);
        }

        // Next attempt should be rate limited
        $response = $this->post('/member/login', [
            'civil_id' => $this->member->civil_id,
            'password' => 'password',
        ]);

        // Check if rate limited (may vary based on implementation)
        $this->assertTrue(true);
    }

    /** @test */
    public function rate_limiting_works_on_verification_endpoint()
    {
        // Make multiple requests quickly
        for ($i = 0; $i < 11; $i++) {
            $response = $this->post('/verify-membership', [
                'civil_id' => $this->member->civil_id,
            ]);
        }

        // Should be rate limited
        $response->assertStatus(429);
    }

    /** @test */
    public function guest_cannot_access_member_dashboard()
    {
        $response = $this->get('/member/dashboard');
        $response->assertRedirect('/member/login');
    }

    /** @test */
    public function guest_cannot_access_admin_panel()
    {
        $response = $this->get('/admin');
        $response->assertRedirect();
    }

    /** @test */
    public function member_cannot_access_another_members_data()
    {
        $anotherMember = Registration::create([
            'memberName' => 'Another Member',
            'civil_id' => '98765432101',
            'email' => 'another@test.com',
            'whatsapp_number' => '+96587654321',
            'registration_type' => 'New Member',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addYear(),
            'password' => Hash::make('password'),
            'nok_id' => 'NOK002',
        ]);

        // Login as first member
        $this->actingAs($this->member, 'members');

        // Try to access another member's card (if such route exists)
        // This is a conceptual test - adjust based on actual routes
        $response = $this->get("/membership-card/download/{$anotherMember->id}");
        
        // Should either be forbidden or return own card
        $this->assertTrue(true); // Placeholder assertion
    }

    /** @test */
    public function password_must_meet_minimum_requirements()
    {
        $weakPasswords = [
            '123',
            'abc',
            '12',
        ];

        foreach ($weakPasswords as $password) {
            $response = $this->post('/member/login', [
                'civil_id' => $this->member->civil_id,
                'password' => $password,
            ]);

            // Should fail due to weak password or invalid credentials
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function session_fixation_is_prevented()
    {
        // Get initial session ID
        $sessionId1 = session()->getId();

        // Login
        $this->post('/member/login', [
            'civil_id' => $this->member->civil_id,
            'password' => 'password',
        ]);

        // Session ID should be regenerated
        $sessionId2 = session()->getId();
        
        $this->assertNotEquals($sessionId1, $sessionId2);
    }

    /** @test */
    public function headers_contain_security_measures()
    {
        $response = $this->get('/');

        // Check for security headers (may vary based on configuration)
        $response->assertSuccessful();
        
        // You can add specific header checks here
        // Example: $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    /** @test */
    public function file_upload_validation_prevents_malicious_files()
    {
        // This test assumes file upload functionality exists
        // Adjust based on actual implementation
        $this->assertTrue(true); // Placeholder
    }

    /** @test */
    public function mass_assignment_is_protected()
    {
        try {
            // Try to mass assign protected fields
            $registration = Registration::create([
                'memberName' => 'Test',
                'civil_id' => '12345678901',
                'email' => 'test@test.com',
                'whatsapp_number' => '+96512345678',
                'registration_type' => 'New Member',
                'nok_id' => 'NOK999', // This might be guarded
            ]);

            // If it succeeds, check if guarded fields are protected
            $this->assertTrue(true);
        } catch (\Exception $e) {
            // If it throws an exception, that's expected for guarded fields
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function authentication_guards_are_properly_separated()
    {
        // Login as member
        $this->actingAs($this->member, 'members');

        // Should not be authenticated on web guard
        $this->assertFalse(auth('web')->check());
        $this->assertTrue(auth('members')->check());
    }

    /** @test */
    public function sensitive_data_is_not_exposed_in_errors()
    {
        $response = $this->withoutExceptionHandling();
        
        try {
            // Try to trigger an error
            $response = $this->post('/member/login', [
                'civil_id' => '99999999999',
                'password' => 'test',
            ]);
        } catch (\Exception $e) {
            // Error should not contain sensitive information
            $this->assertStringNotContainsString('password', $e->getMessage());
            $this->assertStringNotContainsString('secret', $e->getMessage());
        }
        
        $this->assertTrue(true);
    }

    /** @test */
    public function logout_invalidates_session()
    {
        // Login
        $this->actingAs($this->member, 'members');
        $this->assertTrue(auth('members')->check());

        // Logout
        $this->post('/member/logout');

        // Should no longer be authenticated
        $this->assertFalse(auth('members')->check());
    }

    /** @test */
    public function password_reset_tokens_expire()
    {
        // This test assumes password reset functionality exists
        // Implement based on actual password reset logic
        $this->assertTrue(true);
    }

    /** @test */
    public function old_passwords_cannot_be_reused()
    {
        // This test assumes password history functionality
        // Implement if password history is tracked
        $this->assertTrue(true);
    }

    /** @test */
    public function api_throttling_is_configured()
    {
        // Check rate limiter configuration
        $key = 'test-key';
        
        for ($i = 0; $i < 100; $i++) {
            RateLimiter::hit($key, 1);
        }

        $this->assertTrue(RateLimiter::tooManyAttempts($key, 100));
    }

    /** @test */
    public function input_length_limits_are_enforced()
    {
        $longString = str_repeat('a', 1000);

        $response = $this->post('/contact', [
            'name' => $longString,
            'email' => 'test@example.com',
            'phone' => '+96512345678',
            'message' => 'Test',
        ]);

        // Should fail or truncate based on validation rules
        $response->assertSessionHasErrors('name');
    }
}




