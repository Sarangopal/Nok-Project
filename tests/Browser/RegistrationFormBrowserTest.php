<?php

namespace Tests\Browser;

use App\Models\Registration;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationFormBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test complete new member registration flow through all 4 steps
     */
    public function test_complete_new_member_registration_flow()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->assertSee('Become a Proud Member of NOK')
                    ->assertSee('Membership Details');

            // STEP 1: Membership Details
            $browser->type('input[name="memberName"]', 'John Doe Test')
                    ->type('input[name="age"]', '35')
                    ->click('input[name="gender"][value="Male"]')
                    ->type('input[name="email"]', 'johndoe' . time() . '@example.com')
                    ->type('input[name="mobile"]', '+96551234567')
                    ->type('input[name="whatsapp"]', '+96551234567')
                    ->pause(1000) // Wait for validation
                    ->assertSee('Looks good!');

            // Click Next Step 1
            $browser->click('#nextStepBtn1')
                    ->pause(2000) // Wait for step transition
                    ->assertSee('Professional Details');

            // STEP 2: Professional Details
            $browser->type('input[name="department"]', 'Cardiology')
                    ->type('input[name="job_title"]', 'Senior Nurse')
                    ->type('input[name="institution"]', 'Kuwait General Hospital')
                    ->type('input[name="passport"]', 'N' . rand(1000000, 9999999))
                    ->type('input[name="civil_id"]', '28' . rand(1000000000, 9999999999))
                    ->select('select[name="blood_group"]', 'O+')
                    ->pause(1000);

            // Click Next Step 2
            $browser->click('#nextStepBtn2')
                    ->pause(2000)
                    ->assertSee('Permanent Address & Nominee Details');

            // STEP 3: Address & Nominee Details
            $browser->type('textarea[name="address"]', 'Block 5, Street 10, Farwaniya, Kuwait')
                    ->type('input[name="phone_india"]', '+919876543210')
                    ->type('input[name="nominee_name"]', 'Jane Doe')
                    ->type('input[name="nominee_relation"]', 'Spouse')
                    ->type('input[name="nominee_contact"]', '+919876543211')
                    ->pause(1000);

            // Click Next Step 3
            $browser->click('#nextStepBtn3')
                    ->pause(2000)
                    ->assertSee('Declaration');

            // STEP 4: Declaration & Submit
            $browser->check('input[name="declaration"]')
                    ->pause(500);

            // Submit the form
            $browser->click('button[type="submit"]')
                    ->pause(5000) // Wait for submission
                    ->assertSee('Registration Successful');

            // Verify data in database
            $this->assertDatabaseHas('registrations', [
                'memberName' => 'John Doe Test',
                'age' => 35,
                'gender' => 'Male',
                'department' => 'Cardiology',
            ]);
        });
    }

    /**
     * Test duplicate email detection with live validation
     */
    public function test_duplicate_email_shows_error_message()
    {
        // Create existing member
        Registration::factory()->create([
            'email' => 'duplicate@test.com',
            'mobile' => '+96599999999',
            'passport' => 'P9999999',
            'civil_id' => '999888777666',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->type('input[name="memberName"]', 'Test User')
                    ->type('input[name="age"]', '30')
                    ->click('input[name="gender"][value="Female"]')
                    ->type('input[name="email"]', 'duplicate@test.com')
                    ->pause(2000) // Wait for duplicate check
                    ->assertSee('already registered');
        });
    }

    /**
     * Test existing member toggle shows NOK ID and DOJ fields
     */
    public function test_existing_member_toggle_shows_additional_fields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->assertSee('Already Member');

            // Toggle to existing member
            $browser->click('#memberSwitch')
                    ->pause(500)
                    ->assertSee('Already a Member')
                    ->assertVisible('input[name="nok_id"]')
                    ->assertVisible('input[name="doj"]');

            // Fill existing member fields
            $browser->type('input[name="nok_id"]', 'NOK12345')
                    ->type('input[name="doj"]', '2020-01-01')
                    ->pause(500);
        });
    }

    /**
     * Test WhatsApp number validation
     */
    public function test_whatsapp_number_validation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->type('input[name="memberName"]', 'Test User')
                    ->type('input[name="age"]', '25')
                    ->click('input[name="gender"][value="Male"]')
                    ->type('input[name="email"]', 'test' . time() . '@example.com')
                    ->type('input[name="mobile"]', '+96551111111')
                    ->type('input[name="whatsapp"]', 'invalid')
                    ->pause(1000)
                    ->assertSee('Invalid whatsapp');

            // Now enter valid WhatsApp
            $browser->clear('input[name="whatsapp"]')
                    ->type('input[name="whatsapp"]', '+96551111111')
                    ->pause(1000)
                    ->assertSee('Looks good!');
        });
    }

    /**
     * Test step validation prevents proceeding with incomplete data
     */
    public function test_cannot_proceed_with_incomplete_step()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->type('input[name="memberName"]', 'Test User')
                    // Don't fill other required fields
                    ->pause(500);

            // Next button should be disabled
            $browser->assertAttribute('#nextStepBtn1', 'disabled', 'true');
        });
    }

    /**
     * Test back button navigation
     */
    public function test_back_button_navigation_between_steps()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/registration')
                    ->type('input[name="memberName"]', 'Test User')
                    ->type('input[name="age"]', '30')
                    ->click('input[name="gender"][value="Male"]')
                    ->type('input[name="email"]', 'test' . time() . '@example.com')
                    ->type('input[name="mobile"]', '+96551234567')
                    ->pause(1000)
                    ->click('#nextStepBtn1')
                    ->pause(2000)
                    ->assertSee('Professional Details');

            // Go back to step 1
            $browser->click('#prevStepBtn2')
                    ->pause(1000)
                    ->assertSee('Membership Details')
                    ->assertInputValue('input[name="memberName"]', 'Test User');
        });
    }
}

