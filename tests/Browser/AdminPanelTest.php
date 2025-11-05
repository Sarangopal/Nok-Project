<?php

namespace Tests\Browser;

use App\Models\Registration;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminPanelTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test admin login functionality
     */
    public function test_admin_can_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->assertSee('Login')
                    ->type('email', 'admin@gmail.com')
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/admin')
                    ->assertSee('Dashboard');
        });
    }

    /**
     * Test admin cannot login with wrong credentials
     */
    public function test_admin_cannot_login_with_wrong_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@gmail.com')
                    ->type('password', 'wrongpassword')
                    ->press('Login')
                    ->assertPathIs('/admin/login')
                    ->assertSee('These credentials do not match');
        });
    }

    /**
     * Test registrations page loads
     */
    public function test_registrations_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin/registrations')
                    ->assertSee('New Registrations')
                    ->assertSee('New registration')
                    ->assertSee('Search');
        });
    }

    /**
     * Test duplicate email validation in registration form
     */
    public function test_duplicate_email_validation(): void
    {
        // Create a test registration first
        Registration::factory()->create([
            'email' => 'duplicate@example.com',
            'civil_id' => '123456789012',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin/registrations/create')
                    ->type('memberName', 'Test User')
                    ->type('age', '25')
                    ->type('gender', 'M')
                    ->type('email', 'duplicate@example.com') // Duplicate email
                    ->type('mobile', '50123456')
                    ->type('civil_id', '987654321098')
                    ->press('Create')
                    ->assertSee('This email is already registered');
        });
    }

    /**
     * Test duplicate civil_id validation in registration form
     */
    public function test_duplicate_civil_id_validation(): void
    {
        // Create a test registration first
        Registration::factory()->create([
            'email' => 'test@example.com',
            'civil_id' => '111111111111',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin/registrations/create')
                    ->type('memberName', 'Test User')
                    ->type('age', '25')
                    ->type('gender', 'M')
                    ->type('email', 'newemail@example.com')
                    ->type('mobile', '50123456')
                    ->type('civil_id', '111111111111') // Duplicate civil_id
                    ->press('Create')
                    ->assertSee('This Civil ID is already registered');
        });
    }

    /**
     * Test renewal requests page loads
     */
    public function test_renewal_requests_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin/renewal-requests')
                    ->assertSee('Renewal Requests')
                    ->assertSee('View');
        });
    }

    /**
     * Test renewal request view modal displays payment proof
     */
    public function test_renewal_request_view_modal(): void
    {
        $registration = Registration::factory()->create([
            'renewal_status' => 'pending',
            'renewal_requested_at' => now(),
            'renewal_payment_proof' => 'test/payment.jpg',
        ]);

        $this->browse(function (Browser $browser) use ($registration) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin/renewal-requests')
                    ->click('@view-button-' . $registration->id) // Assuming you add data-testid
                    ->waitFor('.modal')
                    ->assertSee('Payment Proof')
                    ->assertSee('Updated Member Details')
                    ->assertSee('Request Information');
        });
    }

    /**
     * Test events page loads
     */
    public function test_events_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin/events')
                    ->assertSee('Events')
                    ->assertSee('New event');
        });
    }

    /**
     * Test duplicate display_order validation in events
     */
    public function test_duplicate_display_order_validation(): void
    {
        $event = \App\Models\Event::factory()->create([
            'display_order' => 1,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin/events/create')
                    ->type('title', 'Test Event')
                    ->select('event_date', now()->addDays(30)->format('Y-m-d'))
                    ->type('display_order', '1') // Duplicate order
                    ->press('Create')
                    ->assertSee('This order number is already in use');
        });
    }

    /**
     * Test member type badges show correct colors
     */
    public function test_member_type_badge_colors(): void
    {
        Registration::factory()->create([
            'member_type' => 'new',
        ]);
        Registration::factory()->create([
            'member_type' => 'existing',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin/renewals')
                    ->assertSee('new')
                    ->assertSee('existing');
            // Note: Actual color verification requires CSS selector testing
        });
    }

    /**
     * Test logout functionality
     */
    public function test_admin_can_logout(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(\App\Models\User::first(), 'web')
                    ->visit('/admin')
                    ->click('@logout-button') // Assuming logout button has test ID
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test protected routes redirect to login
     */
    public function test_protected_routes_redirect_to_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/registrations')
                    ->assertPathIs('/admin/login');
        });
    }
}


