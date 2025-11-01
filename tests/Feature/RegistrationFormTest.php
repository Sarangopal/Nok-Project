<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationFormTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'member_type' => 'new',
            'memberName' => 'Test User',
            'age' => 30,
            'gender' => 'Male',
            'email' => 'testuser@example.com',
            'mobile' => '+96551234567',
            'nok_id' => null,
            'doj' => null,
            'whatsapp' => '+96551234567',
            'department' => 'Cardiology',
            'job_title' => 'Staff Nurse',
            'institution' => 'Kuwait General Hospital',
            'passport' => 'N1234567',
            'civil_id' => '123456789012',
            'blood_group' => 'O+',
            'address' => 'Some address, Kuwait',
            'phone_india' => '+919876543210',
            'nominee_name' => 'John Doe',
            'nominee_relation' => 'Brother',
            'nominee_contact' => '+919876543210',
            'guardian_name' => null,
            'guardian_contact' => null,
            'bank_account_name' => null,
            'account_number' => null,
            'ifsc_code' => null,
            'bank_branch' => null,
        ], $overrides);
    }

    public function test_registers_new_member_successfully(): void
    {
        $response = $this->postJson(route('registration.submit'), $this->validPayload());

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('registrations', [
            'email' => 'testuser@example.com',
            'memberName' => 'Test User',
        ]);
    }

    public function test_rejects_duplicate_email(): void
    {
        Registration::factory()->create(['email' => 'dup@example.com']);

        $response = $this->postJson(route('registration.submit'), $this->validPayload([
            'email' => 'dup@example.com',
            'mobile' => '+96551230001',
            'passport' => 'N1234568',
            'civil_id' => '123456789013',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_rejects_duplicate_mobile(): void
    {
        Registration::factory()->create(['mobile' => '+96555550001']);

        $response = $this->postJson(route('registration.submit'), $this->validPayload([
            'email' => 'unique1@example.com',
            'mobile' => '+96555550001',
            'passport' => 'N1234569',
            'civil_id' => '123456789014',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['mobile']);
    }

    public function test_rejects_duplicate_passport(): void
    {
        Registration::factory()->create(['passport' => 'P9876543']);

        $response = $this->postJson(route('registration.submit'), $this->validPayload([
            'email' => 'unique2@example.com',
            'mobile' => '+96555550002',
            'passport' => 'P9876543',
            'civil_id' => '123456789015',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['passport']);
    }

    public function test_rejects_duplicate_civil_id(): void
    {
        Registration::factory()->create(['civil_id' => '555666777888']);

        $response = $this->postJson(route('registration.submit'), $this->validPayload([
            'email' => 'unique3@example.com',
            'mobile' => '+96555550003',
            'passport' => 'N1234570',
            'civil_id' => '555666777888',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['civil_id']);
    }

    public function test_check_duplicate_endpoint_email(): void
    {
        Registration::factory()->create(['email' => 'dup@example.com']);

        $existsResponse = $this->postJson(route('registration.checkDuplicate'), [
            'field' => 'email',
            'value' => 'dup@example.com',
        ])->assertOk();

        $existsResponse->assertJson(['exists' => true]);

        $notExistsResponse = $this->postJson(route('registration.checkDuplicate'), [
            'field' => 'email',
            'value' => 'not-exists@example.com',
        ])->assertOk();

        $notExistsResponse->assertJson(['exists' => false]);
    }

    public function test_existing_member_payload_is_accepted(): void
    {
        $payload = $this->validPayload([
            'member_type' => 'existing',
            'nok_id' => 'NOK12345',
            'doj' => now()->toDateString(),
            'email' => 'existing.member@example.com',
            'mobile' => '+96551234568',
            'passport' => 'N1234571',
            'civil_id' => '123456789016',
        ]);

        $response = $this->postJson(route('registration.submit'), $payload);

        $response->assertOk()->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('registrations', [
            'email' => 'existing.member@example.com',
            'nok_id' => 'NOK12345',
        ]);
    }
}


