<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Member;

class RenewalPaymentProofUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake the public storage disk
        Storage::fake('public');
    }

    /** @test */
    public function member_can_upload_payment_proof_with_renewal_request()
    {
        // Create a test member
        $member = Member::create([
            'memberName' => 'Test Member',
            'email' => 'test@example.com',
            'password' => 'password123',
            'age' => 30,
            'gender' => 'Male',
            'mobile' => '+96512345678',
            'member_type' => 'new',
            'renewal_status' => 'approved',
            'login_status' => 'approved',
            'card_valid_until' => now()->addDays(15), // Within renewal window
        ]);

        // Create a fake image
        $file = UploadedFile::fake()->image('payment_proof.jpg', 800, 600)->size(900);

        // Authenticate as member
        $this->actingAs($member, 'members');

        // Make the renewal request with payment proof
        $response = $this->post(route('member.renewal.request'), [
            'payment_proof' => $file,
        ]);

        // Assert successful redirect
        $response->assertRedirect();
        $response->assertSessionHas('status');

        // Refresh the member to get updated data
        $member->refresh();

        // Assert payment proof was stored
        $this->assertNotNull($member->renewal_payment_proof);
        
        // Assert the file exists in storage
        Storage::disk('public')->assertExists($member->renewal_payment_proof);

        // Assert renewal status is pending
        $this->assertEquals('pending', $member->renewal_status);
        
        // Assert renewal_requested_at is set
        $this->assertNotNull($member->renewal_requested_at);
    }

    /** @test */
    public function renewal_request_fails_without_payment_proof()
    {
        // Create a test member
        $member = Member::create([
            'memberName' => 'Test Member',
            'email' => 'test@example.com',
            'password' => 'password123',
            'age' => 30,
            'gender' => 'Male',
            'mobile' => '+96512345678',
            'member_type' => 'new',
            'renewal_status' => 'approved',
            'login_status' => 'approved',
            'card_valid_until' => now()->addDays(15),
        ]);

        // Authenticate as member
        $this->actingAs($member, 'members');

        // Make the renewal request WITHOUT payment proof
        $response = $this->post(route('member.renewal.request'), [
            // No payment_proof
        ]);

        // Assert validation error
        $response->assertSessionHasErrors('payment_proof');
    }

    /** @test */
    public function payment_proof_must_be_an_image()
    {
        // Create a test member
        $member = Member::create([
            'memberName' => 'Test Member',
            'email' => 'test@example.com',
            'password' => 'password123',
            'age' => 30,
            'gender' => 'Male',
            'mobile' => '+96512345678',
            'member_type' => 'new',
            'renewal_status' => 'approved',
            'login_status' => 'approved',
            'card_valid_until' => now()->addDays(15),
        ]);

        // Create a fake PDF file (not an image)
        $file = UploadedFile::fake()->create('document.pdf', 100);

        // Authenticate as member
        $this->actingAs($member, 'members');

        // Make the renewal request with non-image file
        $response = $this->post(route('member.renewal.request'), [
            'payment_proof' => $file,
        ]);

        // Assert validation error
        $response->assertSessionHasErrors('payment_proof');
    }

    /** @test */
    public function payment_proof_must_not_exceed_max_size()
    {
        // Create a test member
        $member = Member::create([
            'memberName' => 'Test Member',
            'email' => 'test@example.com',
            'password' => 'password123',
            'age' => 30,
            'gender' => 'Male',
            'mobile' => '+96512345678',
            'member_type' => 'new',
            'renewal_status' => 'approved',
            'login_status' => 'approved',
            'card_valid_until' => now()->addDays(15),
        ]);

        // Create a fake image larger than 10MB
        $file = UploadedFile::fake()->image('large_payment_proof.jpg')->size(11000); // 11MB

        // Authenticate as member
        $this->actingAs($member, 'members');

        // Make the renewal request with oversized file
        $response = $this->post(route('member.renewal.request'), [
            'payment_proof' => $file,
        ]);

        // Assert validation error
        $response->assertSessionHasErrors('payment_proof');
    }

    /** @test */
    public function multiple_image_formats_are_accepted()
    {
        $formats = ['jpg', 'jpeg', 'png', 'webp'];

        foreach ($formats as $format) {
            Storage::fake('public');

            $member = Member::create([
                'memberName' => 'Test Member ' . $format,
                'email' => "test-{$format}@example.com",
                'password' => 'password123',
                'age' => 30,
                'gender' => 'Male',
                'mobile' => '+96512345678',
                'member_type' => 'new',
                'renewal_status' => 'approved',
                'login_status' => 'approved',
                'card_valid_until' => now()->addDays(15),
            ]);

            $file = UploadedFile::fake()->image("payment_proof.{$format}");

            $this->actingAs($member, 'members');

            $response = $this->post(route('member.renewal.request'), [
                'payment_proof' => $file,
            ]);

            $response->assertRedirect();
            
            $member->refresh();
            $this->assertNotNull($member->renewal_payment_proof);
            Storage::disk('public')->assertExists($member->renewal_payment_proof);
        }
    }

    /** @test */
    public function uploaded_payment_proof_is_stored_in_correct_directory()
    {
        $member = Member::create([
            'memberName' => 'Test Member',
            'email' => 'test@example.com',
            'password' => 'password123',
            'age' => 30,
            'gender' => 'Male',
            'mobile' => '+96512345678',
            'member_type' => 'new',
            'renewal_status' => 'approved',
            'login_status' => 'approved',
            'card_valid_until' => now()->addDays(15),
        ]);

        $file = UploadedFile::fake()->image('payment_proof.jpg');

        $this->actingAs($member, 'members');

        $this->post(route('member.renewal.request'), [
            'payment_proof' => $file,
        ]);

        $member->refresh();
        
        // Assert the file is stored in the renewals/payment-proofs directory
        $this->assertStringContainsString('renewals/payment-proofs/', $member->renewal_payment_proof);
    }
}

