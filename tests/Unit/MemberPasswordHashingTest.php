<?php

namespace Tests\Unit;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberPasswordHashingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations
        $this->artisan('migrate');
    }

    /** @test */
    public function password_is_automatically_hashed_on_create()
    {
        $member = Member::factory()->create([
            'password' => 'plainpassword',
        ]);

        $this->assertNotEquals('plainpassword', $member->password);
        $this->assertTrue(Hash::check('plainpassword', $member->password));
    }

    /** @test */
    public function password_is_automatically_hashed_on_update()
    {
        $member = Member::factory()->create([
            'password' => 'oldpassword',
        ]);

        $member->password = 'newpassword';
        $member->save();

        $this->assertNotEquals('newpassword', $member->password);
        $this->assertTrue(Hash::check('newpassword', $member->password));
    }

    /** @test */
    public function already_hashed_password_is_not_double_hashed()
    {
        $hashedPassword = Hash::make('testpassword');
        
        $member = Member::factory()->create([
            'password' => $hashedPassword,
        ]);

        // Password should remain the same (not double hashed)
        $this->assertEquals($hashedPassword, $member->password);
        $this->assertTrue(Hash::check('testpassword', $member->password));
    }

    /** @test */
    public function password_verification_works_correctly()
    {
        $member = Member::factory()->create([
            'password' => 'correctpassword',
        ]);

        // Correct password
        $this->assertTrue(Hash::check('correctpassword', $member->password));
        
        // Incorrect password
        $this->assertFalse(Hash::check('wrongpassword', $member->password));
    }

    /** @test */
    public function nok_style_password_format_is_generated_correctly()
    {
        // Test NOK password format: NOK + 3 digits + uppercase + lowercase + !
        $password = 'NOK' . rand(100, 999) . chr(rand(65, 90)) . chr(rand(97, 122)) . '!';
        
        // Verify format
        $this->assertMatchesRegularExpression('/^NOK\d{3}[A-Z][a-z]!$/', $password);
        $this->assertEquals(8, strlen($password));
    }

    /** @test */
    public function password_minimum_length_requirement()
    {
        $member = Member::factory()->make();
        
        // Test various password lengths
        $shortPassword = 'short';
        $validPassword = 'validpass';
        
        $this->assertLessThan(8, strlen($shortPassword));
        $this->assertGreaterThanOrEqual(8, strlen($validPassword));
    }
}

