<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test admin user
        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * Test that admin login page loads successfully.
     *
     * @return void
     */
    public function test_admin_login_page_loads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->assertSee('Sign in')
                    ->assertSee('Email')
                    ->assertSee('Password')
                    ->screenshot('admin_login_page');
        });
    }

    /**
     * Test admin can login with valid credentials.
     *
     * @return void
     */
    public function test_admin_can_login_with_valid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password123')
                    ->screenshot('before_admin_login')
                    ->press('Sign in')
                    ->waitForLocation('/admin', 10)
                    ->assertPathIs('/admin')
                    ->assertAuthenticatedAs(User::where('email', 'admin@test.com')->first(), 'web')
                    ->screenshot('after_admin_login_success');
        });
    }

    /**
     * Test admin cannot login with invalid credentials.
     *
     * @return void
     */
    public function test_admin_cannot_login_with_invalid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@test.com')
                    ->type('password', 'wrongpassword')
                    ->screenshot('before_invalid_login')
                    ->press('Sign in')
                    ->waitFor('.fi-error-message', 5) // Wait for error message
                    ->assertSee('credentials')
                    ->assertPathIs('/admin/login')
                    ->screenshot('after_invalid_login');
        });
    }

    /**
     * Test admin cannot login with empty credentials.
     *
     * @return void
     */
    public function test_admin_cannot_login_with_empty_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->press('Sign in')
                    ->waitFor('.fi-error-message', 5)
                    ->assertPathIs('/admin/login')
                    ->screenshot('empty_credentials_error');
        });
    }

    /**
     * Test admin can access dashboard after login.
     *
     * @return void
     */
    public function test_admin_can_access_dashboard_after_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password123')
                    ->press('Sign in')
                    ->waitForLocation('/admin', 10)
                    ->assertSee('Dashboard')
                    ->screenshot('admin_dashboard');
        });
    }

    /**
     * Test remember me functionality.
     *
     * @return void
     */
    public function test_remember_me_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password123')
                    ->check('remember') // Check remember me checkbox
                    ->screenshot('remember_me_checked')
                    ->press('Sign in')
                    ->waitForLocation('/admin', 10)
                    ->assertPathIs('/admin')
                    ->screenshot('logged_in_with_remember');
        });
    }

    /**
     * Test login form validation.
     *
     * @return void
     */
    public function test_login_form_validation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'invalid-email')
                    ->type('password', 'short')
                    ->press('Sign in')
                    ->waitFor('.fi-error-message', 5)
                    ->screenshot('form_validation_errors');
        });
    }

    /**
     * Test that logged-in admin is redirected from login page.
     *
     * @return void
     */
    public function test_logged_in_admin_redirected_from_login_page()
    {
        $this->browse(function (Browser $browser) {
            // First login
            $browser->visit('/admin/login')
                    ->type('email', 'admin@test.com')
                    ->type('password', 'password123')
                    ->press('Sign in')
                    ->waitForLocation('/admin', 10);

            // Try to visit login page again
            $browser->visit('/admin/login')
                    ->waitForLocation('/admin', 5)
                    ->assertPathIs('/admin')
                    ->screenshot('already_logged_in_redirect');
        });
    }
}




