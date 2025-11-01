<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test registration form is accessible
     */
    public function test_registration_form_accessible()
    {
        $response = $this->get('/registration');
        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    /**
     * Test successful registration with valid data
     */
    public function test_successful_registration_with_valid_data()
    {
        $data = [
            'member_type' => 'new',
            'memberName' => 'John Doe',
            'age' => 30,
            'gender' => 'Male',
            'email' => 'john.doe@example.com',
            'mobile' => '+96551234567',
            'whatsapp' => '+96551234567',
            'department' => 'Nursing',
            'job_title' => 'Senior Nurse',
            'institution' => 'Kuwait Hospital',
            'passport' => 'A1234567',
            'civil_id' => '123456789012',
            'blood_group' => 'A+',
            'address' => '123 Main St, Kuwait City, Kuwait 12345',
            'phone_india' => '+919876543210',
            'nominee_name' => 'Jane Doe',
            'nominee_relation' => 'Spouse',
            'nominee_contact' => '+919876543210',
        ];

        $response = $this->postJson('/registration-submit', $data);
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ]);

        $this->assertDatabaseHas('registrations', [
            'email' => 'john.doe@example.com',
            'memberName' => 'John Doe'
        ]);
    }

    /**
     * Test registration fails with duplicate email
     */
    public function test_registration_fails_with_duplicate_email()
    {
        // Create a registration first
        Registration::create([
            'member_type' => 'new',
            'memberName' => 'Existing User',
            'age' => 30,
            'gender' => 'Female',
            'email' => 'existing@example.com',
            'mobile' => '+96551111111',
            'department' => 'Nursing',
            'job_title' => 'Nurse',
            'institution' => 'Hospital',
            'passport' => 'B1234567',
            'civil_id' => '111111111111',
            'blood_group' => 'O+',
            'address' => '123 Street',
            'phone_india' => '+919111111111',
            'nominee_name' => 'Nominee',
            'nominee_relation' => 'Friend',
            'nominee_contact' => '+919111111111',
            'doj' => now()->toDateString(),
        ]);

        // Try to register with the same email
        $data = [
            'member_type' => 'new',
            'memberName' => 'John Doe',
            'age' => 30,
            'gender' => 'Male',
            'email' => 'existing@example.com', // Duplicate email
            'mobile' => '+96552222222',
            'department' => 'Nursing',
            'job_title' => 'Senior Nurse',
            'institution' => 'Kuwait Hospital',
            'passport' => 'A9999999',
            'civil_id' => '999999999999',
            'blood_group' => 'A+',
            'address' => '123 Main St',
            'phone_india' => '+919876543210',
            'nominee_name' => 'Jane Doe',
            'nominee_relation' => 'Spouse',
            'nominee_contact' => '+919876543210',
        ];

        $response = $this->postJson('/registration-submit', $data);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test registration fails with duplicate mobile
     */
    public function test_registration_fails_with_duplicate_mobile()
    {
        // Create a registration first
        Registration::create([
            'member_type' => 'new',
            'memberName' => 'Existing User',
            'age' => 30,
            'gender' => 'Female',
            'email' => 'user1@example.com',
            'mobile' => '+96551111111',
            'department' => 'Nursing',
            'job_title' => 'Nurse',
            'institution' => 'Hospital',
            'passport' => 'C1234567',
            'civil_id' => '222222222222',
            'blood_group' => 'O+',
            'address' => '123 Street',
            'phone_india' => '+919111111111',
            'nominee_name' => 'Nominee',
            'nominee_relation' => 'Friend',
            'nominee_contact' => '+919111111111',
            'doj' => now()->toDateString(),
        ]);

        // Try to register with the same mobile
        $data = [
            'member_type' => 'new',
            'memberName' => 'John Doe',
            'age' => 30,
            'gender' => 'Male',
            'email' => 'john.unique@example.com',
            'mobile' => '+96551111111', // Duplicate mobile
            'department' => 'Nursing',
            'job_title' => 'Senior Nurse',
            'institution' => 'Kuwait Hospital',
            'passport' => 'D1234567',
            'civil_id' => '333333333333',
            'blood_group' => 'A+',
            'address' => '123 Main St',
            'phone_india' => '+919876543210',
            'nominee_name' => 'Jane Doe',
            'nominee_relation' => 'Spouse',
            'nominee_contact' => '+919876543210',
        ];

        $response = $this->postJson('/registration-submit', $data);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['mobile']);
    }

    /**
     * Test check email endpoint returns exists true for duplicate
     */
    public function test_check_email_returns_exists_for_duplicate()
    {
        Registration::create([
            'member_type' => 'new',
            'memberName' => 'Existing User',
            'age' => 30,
            'gender' => 'Female',
            'email' => 'existing@example.com',
            'mobile' => '+96551111111',
            'department' => 'Nursing',
            'job_title' => 'Nurse',
            'institution' => 'Hospital',
            'passport' => 'E1234567',
            'civil_id' => '444444444444',
            'blood_group' => 'O+',
            'address' => '123 Street',
            'phone_india' => '+919111111111',
            'nominee_name' => 'Nominee',
            'nominee_relation' => 'Friend',
            'nominee_contact' => '+919111111111',
            'doj' => now()->toDateString(),
        ]);

        $response = $this->postJson('/check-email', [
            'email' => 'existing@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'exists' => true
                 ]);
    }

    /**
     * Test check email endpoint returns exists false for unique
     */
    public function test_check_email_returns_not_exists_for_unique()
    {
        $response = $this->postJson('/check-email', [
            'email' => 'unique@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'exists' => false
                 ]);
    }

    /**
     * Test check phone endpoint returns exists true for duplicate
     */
    public function test_check_phone_returns_exists_for_duplicate()
    {
        Registration::create([
            'member_type' => 'new',
            'memberName' => 'Existing User',
            'age' => 30,
            'gender' => 'Female',
            'email' => 'phone@example.com',
            'mobile' => '+96551234567',
            'department' => 'Nursing',
            'job_title' => 'Nurse',
            'institution' => 'Hospital',
            'passport' => 'F1234567',
            'civil_id' => '555555555555',
            'blood_group' => 'O+',
            'address' => '123 Street',
            'phone_india' => '+919111111111',
            'nominee_name' => 'Nominee',
            'nominee_relation' => 'Friend',
            'nominee_contact' => '+919111111111',
            'doj' => now()->toDateString(),
        ]);

        $response = $this->postJson('/check-phone', [
            'phone' => '+96551234567',
            'country' => 'kw'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'exists' => true
                 ]);
    }

    /**
     * Test check phone endpoint returns exists false for unique
     */
    public function test_check_phone_returns_not_exists_for_unique()
    {
        $response = $this->postJson('/check-phone', [
            'phone' => '+96559999999',
            'country' => 'kw'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'exists' => false
                 ]);
    }

    /**
     * Test gender validation accepts Male, Female, Others
     */
    public function test_gender_validation_accepts_valid_values()
    {
        $validGenders = ['Male', 'Female', 'Others'];

        foreach ($validGenders as $gender) {
            $data = [
                'member_type' => 'new',
                'memberName' => 'Test User',
                'age' => 30,
                'gender' => $gender,
                'email' => $this->faker->unique()->email,
                'mobile' => '+96559' . rand(100000, 999999),
                'department' => 'Nursing',
                'job_title' => 'Nurse',
                'institution' => 'Hospital',
                'passport' => 'G' . rand(1000000, 9999999),
                'civil_id' => str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT),
                'blood_group' => 'A+',
                'address' => '123 Street',
                'phone_india' => '+9196' . rand(10000000, 99999999),
                'nominee_name' => 'Nominee',
                'nominee_relation' => 'Friend',
                'nominee_contact' => '+919876543210',
            ];

            $response = $this->postJson('/registration-submit', $data);
            $response->assertStatus(200);
        }
    }

    /**
     * Test gender validation rejects invalid values
     */
    public function test_gender_validation_rejects_invalid_values()
    {
        $data = [
            'member_type' => 'new',
            'memberName' => 'Test User',
            'age' => 30,
            'gender' => 'Transgender', // Should be 'Others' now
            'email' => 'test@example.com',
            'mobile' => '+96559123456',
            'department' => 'Nursing',
            'job_title' => 'Nurse',
            'institution' => 'Hospital',
            'passport' => 'H1234567',
            'civil_id' => '666666666666',
            'blood_group' => 'A+',
            'address' => '123 Street',
            'phone_india' => '+919876543210',
            'nominee_name' => 'Nominee',
            'nominee_relation' => 'Friend',
            'nominee_contact' => '+919876543210',
        ];

        $response = $this->postJson('/registration-submit', $data);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['gender']);
    }

    /**
     * Test required fields validation
     */
    public function test_required_fields_validation()
    {
        $response = $this->postJson('/registration-submit', []);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'memberName',
                     'age',
                     'gender',
                     'email',
                     'mobile',
                     'department',
                     'job_title',
                     'institution',
                     'passport',
                     'civil_id',
                     'blood_group',
                     'address',
                     'phone_india',
                     'nominee_name',
                     'nominee_relation',
                     'nominee_contact'
                 ]);
    }

    /**
     * Test age validation (minimum 18)
     */
    public function test_age_validation_minimum_18()
    {
        $data = [
            'member_type' => 'new',
            'memberName' => 'Young User',
            'age' => 17, // Below minimum
            'gender' => 'Male',
            'email' => 'young@example.com',
            'mobile' => '+96559123456',
            'department' => 'Nursing',
            'job_title' => 'Nurse',
            'institution' => 'Hospital',
            'passport' => 'I1234567',
            'civil_id' => '777777777777',
            'blood_group' => 'A+',
            'address' => '123 Street',
            'phone_india' => '+919876543210',
            'nominee_name' => 'Nominee',
            'nominee_relation' => 'Parent',
            'nominee_contact' => '+919876543210',
        ];

        $response = $this->postJson('/registration-submit', $data);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['age']);
    }

    /**
     * Test civil ID must be exactly 12 digits
     */
    public function test_civil_id_must_be_12_digits()
    {
        $data = [
            'member_type' => 'new',
            'memberName' => 'Test User',
            'age' => 30,
            'gender' => 'Male',
            'email' => 'civilid@example.com',
            'mobile' => '+96559123456',
            'department' => 'Nursing',
            'job_title' => 'Nurse',
            'institution' => 'Hospital',
            'passport' => 'J1234567',
            'civil_id' => '12345', // Too short
            'blood_group' => 'A+',
            'address' => '123 Street',
            'phone_india' => '+919876543210',
            'nominee_name' => 'Nominee',
            'nominee_relation' => 'Friend',
            'nominee_contact' => '+919876543210',
        ];

        $response = $this->postJson('/registration-submit', $data);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['civil_id']);
    }
}

