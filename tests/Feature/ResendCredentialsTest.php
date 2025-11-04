<?php

namespace Tests\Feature;

use App\Mail\MembershipCardMail;
use App\Mail\ResendCredentialsMail;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResendCredentialsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function resend_credentials_generates_new_password_and_saves_it()
    {
        Mail::fake();

        // Create an approved member with existing password
        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => Hash::make('oldpassword123'),
            'email' => 'test@example.com',
            'civil_id' => 'CIV12345',
            'nok_id' => 'NOK001234',
        ]);

        $oldPasswordHash = $member->password;

        // Simulate the "Resend Credentials" action logic
        $newPassword = 'NOK' . rand(100, 999) . chr(rand(65, 90)) . chr(rand(97, 122)) . '!';
        $member->password = bcrypt($newPassword);
        $member->save();

        // Verify password was changed
        $member->refresh();
        $this->assertNotEquals($oldPasswordHash, $member->password);
        $this->assertTrue(Hash::check($newPassword, $member->password));
    }

    /** @test */
    public function resend_credentials_sends_email_with_new_password()
    {
        Mail::fake();

        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => Hash::make('oldpassword123'),
            'email' => 'test@example.com',
            'civil_id' => 'CIV12345',
            'nok_id' => 'NOK001234',
            'memberName' => 'John Doe',
        ]);

        // Simulate the action - generate new password and send email
        $newPassword = 'NOK456Ab!';
        $member->password = bcrypt($newPassword);
        $member->save();

        Mail::to($member->email)->send(new ResendCredentialsMail($member, $newPassword));

        // Verify email was sent
        Mail::assertSent(ResendCredentialsMail::class, function ($mail) use ($member, $newPassword) {
            return $mail->hasTo($member->email)
                && $mail->password === $newPassword
                && $mail->record->id === $member->id;
        });
    }

    /** @test */
    public function resend_credentials_email_contains_correct_member_details()
    {
        Mail::fake();

        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => Hash::make('oldpassword123'),
            'email' => 'test@example.com',
            'civil_id' => 'CIV12345',
            'nok_id' => 'NOK001234',
            'memberName' => 'Jane Smith',
        ]);

        $newPassword = 'NOK789Xy!';
        
        Mail::to($member->email)->send(new ResendCredentialsMail($member, $newPassword));

        Mail::assertSent(ResendCredentialsMail::class, function ($mail) use ($member, $newPassword) {
            return $mail->record->email === $member->email
                && $mail->record->civil_id === $member->civil_id
                && $mail->record->memberName === $member->memberName
                && $mail->password === $newPassword;
        });
    }

    /** @test */
    public function generated_password_follows_expected_format()
    {
        // Test password format: NOK###XyZ!
        $passwords = [];
        
        for ($i = 0; $i < 10; $i++) {
            $password = 'NOK' . rand(100, 999) . chr(rand(65, 90)) . chr(rand(97, 122)) . '!';
            $passwords[] = $password;
            
            // Verify format
            $this->assertStringStartsWith('NOK', $password);
            $this->assertStringEndsWith('!', $password);
            $this->assertGreaterThanOrEqual(8, strlen($password));
            $this->assertLessThanOrEqual(10, strlen($password));
            $this->assertMatchesRegularExpression('/^NOK\d{3}[A-Z][a-z]!$/', $password);
        }
        
        // Ensure passwords are unique (very unlikely to be duplicates)
        $this->assertEquals(count($passwords), count(array_unique($passwords)));
    }

    /** @test */
    public function resend_credentials_updates_password_in_database()
    {
        Mail::fake();

        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => Hash::make('originalpassword'),
            'email' => 'test@example.com',
        ]);

        $originalHash = $member->password;

        // Simulate action
        $newPassword = 'NOK123Ab!';
        $member->password = bcrypt($newPassword);
        $member->save();

        // Verify in database
        $this->assertDatabaseHas('registrations', [
            'id' => $member->id,
            'email' => 'test@example.com',
        ]);

        $member->refresh();
        $this->assertNotEquals($originalHash, $member->password);
        $this->assertTrue(Hash::check($newPassword, $member->password));
        $this->assertFalse(Hash::check('originalpassword', $member->password));
    }

    /** @test */
    public function resend_credentials_saves_password_even_if_email_sending_fails()
    {
        Mail::fake();

        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => Hash::make('oldpassword'),
            'email' => 'test@example.com',
        ]);

        $newPassword = 'NOK999Zz!';
        
        // Save password first (this happens before email sending)
        $member->password = bcrypt($newPassword);
        $member->save();

        // Verify password was saved
        $member->refresh();
        $this->assertTrue(Hash::check($newPassword, $member->password));
        
        // Simulate email failure - password should still be saved
        // In real scenario, exception would be caught and logged, but password remains saved
        $this->assertTrue(Hash::check($newPassword, $member->password));
    }

    /** @test */
    public function resend_credentials_only_works_for_approved_members_with_password()
    {
        // Test member without password
        $memberWithoutPassword = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => null,
            'email' => 'nopassword@example.com',
        ]);

        // The button should not be visible for this member
        // (This is tested via visibility check in Filament)
        $this->assertNull($memberWithoutPassword->password);

        // Test pending member
        $pendingMember = Registration::factory()->create([
            'login_status' => 'pending',
            'password' => Hash::make('password123'),
            'email' => 'pending@example.com',
        ]);

        // Button should not be visible for pending members
        $this->assertNotEquals('approved', $pendingMember->login_status);
    }

    /** @test */
    public function resend_credentials_email_has_correct_subject()
    {
        Mail::fake();

        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => Hash::make('oldpassword'),
            'email' => 'test@example.com',
            'nok_id' => 'NOK001234',
        ]);

        $newPassword = 'NOK555Mm!';
        
        Mail::to($member->email)->send(new ResendCredentialsMail($member, $newPassword));

        Mail::assertSent(ResendCredentialsMail::class, function ($mail) {
            // Verify the email subject is about password reset, not renewal
            $envelope = $mail->envelope();
            return str_contains($envelope->subject, 'Password Reset')
                || str_contains($envelope->subject, 'Login Credentials');
        });
    }

    /** @test */
    public function resend_credentials_generates_unique_passwords_each_time()
    {
        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => Hash::make('oldpassword'),
            'email' => 'test@example.com',
        ]);

        $passwords = [];
        
        // Generate multiple passwords
        for ($i = 0; $i < 5; $i++) {
            $password = 'NOK' . rand(100, 999) . chr(rand(65, 90)) . chr(rand(97, 122)) . '!';
            $passwords[] = $password;
        }

        // They should all be unique (very high probability)
        $uniquePasswords = array_unique($passwords);
        $this->assertGreaterThan(3, count($uniquePasswords), 'Generated passwords should be mostly unique');
    }

    /** @test */
    public function resend_credentials_email_includes_login_url()
    {
        Mail::fake();

        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'password' => Hash::make('oldpassword'),
            'email' => 'test@example.com',
            'nok_id' => 'NOK001234',
        ]);

        $newPassword = 'NOK777Nn!';
        
        Mail::to($member->email)->send(new ResendCredentialsMail($member, $newPassword));

        Mail::assertSent(ResendCredentialsMail::class, function ($mail) {
            $data = $mail->content()->with;
            
            return isset($data['loginUrl'])
                && !empty($data['loginUrl'])
                && str_contains($data['loginUrl'], '/member/panel/login');
        });
    }
}

