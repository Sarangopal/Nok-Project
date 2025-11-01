<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Registration;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test registration form loads correctly
     */
    public function testRegistrationFormLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->assertSee('Register')
                    ->assertSee('Membership Details')
                    ->assertPresent('#memberName')
                    ->assertPresent('#email')
                    ->assertPresent('#mobile')
                    ->assertPresent('#whatsapp')
                    ->assertPresent('#phone_india');
        });
    }

    /**
     * Test gender field has correct options
     */
    public function testGenderFieldHasCorrectOptions()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->assertSee('Male')
                    ->assertSee('Female')
                    ->assertSee('Others')
                    ->assertDontSee('Transgender');
        });
    }

    /**
     * Test intl-tel-input initializes on phone fields
     */
    public function testIntlTelInputInitializes()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->pause(1000) // Wait for intl-tel-input to initialize
                    ->assertPresent('.iti') // intl-tel-input wrapper
                    ->assertPresent('.iti__selected-flag');
        });
    }

    /**
     * Test complete registration flow with valid data
     */
    public function testCompleteRegistrationFlow()
    {
        $this->browse(function (Browser $browser) {
            $uniqueEmail = 'test' . time() . '@example.com';
            $uniqueMobile = rand(50000000, 99999999);
            $uniquePassport = 'A' . rand(1000000, 9999999);
            $uniqueCivilId = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);

            $browser->visit('/registration')
                    ->pause(1000)
                    
                    // Step 1: Membership Details
                    ->type('#memberName', 'Test User ' . time())
                    ->type('input[name="age"]', '30')
                    ->click('input[name="gender"][value="Male"]')
                    ->type('#email', $uniqueEmail)
                    ->pause(1000) // Wait for email validation
                    ->type('#mobile', $uniqueMobile)
                    ->pause(1000) // Wait for phone validation
                    ->type('#whatsapp', $uniqueMobile)
                    ->pause(500)
                    ->click('#nextStepBtn1')
                    ->pause(2000) // Wait for validation and next step
                    
                    // Step 2: Professional Details
                    ->assertSee('Professional Details')
                    ->type('input[name="department"]', 'Nursing')
                    ->type('input[name="job_title"]', 'Senior Nurse')
                    ->type('input[name="institution"]', 'Kuwait Hospital')
                    ->type('input[name="passport"]', $uniquePassport)
                    ->type('input[name="civil_id"]', $uniqueCivilId)
                    ->select('select[name="blood_group"]', 'A+')
                    ->click('#nextStepBtn2')
                    ->pause(2000)
                    
                    // Step 3: Address & Nominee Details
                    ->assertSee('Permanent Address')
                    ->type('textarea[name="address"]', '123 Main St, Test City, 12345')
                    ->type('#phone_india', '9876543210')
                    ->pause(500)
                    ->type('input[name="nominee_name"]', 'Jane Doe')
                    ->type('input[name="nominee_relation"]', 'Spouse')
                    ->type('input[name="nominee_contact"]', '+919876543210')
                    ->click('#nextStepBtn3')
                    ->pause(2000)
                    
                    // Step 4: Bank Details & Declaration
                    ->assertSee('Declaration')
                    ->click('button[type="submit"]')
                    ->pause(5000) // Wait for submission
                    ->assertSee('successful'); // SweetAlert success message
            
            // Verify registration was created
            $this->assertDatabaseHas('registrations', [
                'email' => $uniqueEmail
            ]);
        });
    }

    /**
     * Test email duplicate validation
     */
    public function testEmailDuplicateValidation()
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

        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->pause(1000)
                    ->type('#email', 'existing@example.com')
                    ->pause(2000) // Wait for AJAX validation
                    ->assertSee('already registered');
        });
    }

    /**
     * Test phone duplicate validation
     */
    public function testPhoneDuplicateValidation()
    {
        // Create a registration first
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

        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->pause(1000)
                    ->type('#mobile', '51234567')
                    ->pause(2000) // Wait for AJAX validation
                    ->assertSee('already registered');
        });
    }

    /**
     * Test Kuwait phone validation (8 digits, starts with 5, 6, or 9)
     */
    public function testKuwaitPhoneValidation()
    {
        $this->browse(function (Browser $browser) {
            // Test valid Kuwait number
            $browser->visit('/registration')
                    ->pause(1000)
                    ->type('#mobile', '51234567')
                    ->pause(1500)
                    ->assertDontSee('must start with 5, 6, or 9');
            
            // Test invalid Kuwait number (starts with 4)
            $browser->refresh()
                    ->pause(1000)
                    ->type('#mobile', '41234567')
                    ->pause(1500)
                    ->assertSee('must start with 5, 6, or 9');
        });
    }

    /**
     * Test India phone validation (10 digits, starts with 6-9)
     */
    public function testIndiaPhoneValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->pause(1000)
                    ->type('#memberName', 'Test User')
                    ->type('input[name="age"]', '30')
                    ->click('input[name="gender"][value="Male"]')
                    ->type('#email', 'test@example.com')
                    ->type('#mobile', '51234567')
                    ->click('#nextStepBtn1')
                    ->pause(2000)
                    ->type('input[name="department"]', 'Nursing')
                    ->type('input[name="job_title"]', 'Nurse')
                    ->type('input[name="institution"]', 'Hospital')
                    ->type('input[name="passport"]', 'A1234567')
                    ->type('input[name="civil_id"]', '123456789012')
                    ->select('select[name="blood_group"]', 'A+')
                    ->click('#nextStepBtn2')
                    ->pause(2000)
                    ->type('textarea[name="address"]', '123 Street')
                    
                    // Test valid India number
                    ->type('#phone_india', '9876543210')
                    ->pause(1500)
                    ->assertDontSee('must start with 6-9');
        });
    }

    /**
     * Test WhatsApp field helper text is present
     */
    public function testWhatsAppFieldHelperText()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->assertSee('Include your country code for WhatsApp');
        });
    }

    /**
     * Test gender field doesn't show "Looks good" message
     */
    public function testGenderFieldNoLooksGoodMessage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->click('input[name="gender"][value="Male"]')
                    ->pause(500)
                    // The validation message should be empty for gender
                    ->assertDontSee('Looks good', '.gender-row .validation-message');
        });
    }
}

