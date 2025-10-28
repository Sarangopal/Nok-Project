<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoginRoutesVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $approvedMember;
    protected $pendingMember;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user for testing
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        // Create approved member
        $this->approvedMember = Registration::create([
            'memberName' => 'Approved Member',
            'age' => 35,
            'gender' => 'Male',
            'civil_id' => '12345678901',
            'email' => 'approved@test.com',
            'whatsapp' => '+96512345678',
            'renewal_status' => 'approved',
            'card_valid_until' => Carbon::now()->addYear(),
            'nok_id' => 'NOK001',
            'blood_group' => 'O+',
            'password' => Hash::make('member123'),
        ]);

        // Create pending member (not approved yet)
        $this->pendingMember = Registration::create([
            'memberName' => 'Pending Member',
            'age' => 30,
            'gender' => 'Female',
            'civil_id' => '98765432109',
            'email' => 'pending@test.com',
            'whatsapp' => '+96587654321',
            'renewal_status' => 'pending',
            'blood_group' => 'A+',
            'password' => Hash::make('member123'),
        ]);
    }

    // ==================== ADMIN LOGIN TESTS (/admin/login) ====================

    /** @test */
    public function admin_login_page_loads_successfully()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Sign in'); // Filament default text
    }

    /** @test */
    public function admin_can_login_with_valid_credentials()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin, 'web');
    }

    /** @test */
    public function admin_cannot_login_with_invalid_password()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('web');
    }

    /** @test */
    public function admin_cannot_login_with_invalid_email()
    {
        $response = $this->post('/admin/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('web');
    }

    /** @test */
    public function admin_login_requires_email()
    {
        $response = $this->post('/admin/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('web');
    }

    /** @test */
    public function admin_login_requires_password()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest('web');
    }

    /** @test */
    public function admin_redirects_to_dashboard_when_already_logged_in()
    {
        $this->actingAs($this->admin, 'web');

        $response = $this->get('/admin/login');
        $response->assertRedirect('/admin');
    }

    /** @test */
    public function admin_can_access_admin_panel_after_login()
    {
        $this->actingAs($this->admin, 'web');

        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    // ==================== MEMBER PANEL LOGIN TESTS (/member/panel/login) ====================

    /** @test */
    public function member_panel_login_page_loads_successfully()
    {
        $response = $this->get('/member/panel/login');
        $response->assertStatus(200);
        $response->assertSee('Civil ID'); // Custom field
    }

    /** @test */
    public function member_can_login_to_panel_with_civil_id()
    {
        $response = $this->post('/member/panel/login', [
            'civil_id' => '12345678901',
            'password' => 'member123',
        ]);

        $response->assertRedirect('/member/panel');
        $this->assertAuthenticatedAs($this->approvedMember, 'members');
    }

    /** @test */
    public function member_panel_login_requires_civil_id()
    {
        $response = $this->post('/member/panel/login', [
            'password' => 'member123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    /** @test */
    public function member_panel_login_requires_password()
    {
        $response = $this->post('/member/panel/login', [
            'civil_id' => '12345678901',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    /** @test */
    public function member_cannot_login_to_panel_with_wrong_password()
    {
        $response = $this->post('/member/panel/login', [
            'civil_id' => '12345678901',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    /** @test */
    public function member_cannot_login_to_panel_with_wrong_civil_id()
    {
        $response = $this->post('/member/panel/login', [
            'civil_id' => '99999999999',
            'password' => 'member123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    /** @test */
    public function pending_member_cannot_login_to_panel()
    {
        $response = $this->post('/member/panel/login', [
            'civil_id' => '98765432109',
            'password' => 'member123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    /** @test */
    public function member_can_access_panel_dashboard_after_login()
    {
        $this->actingAs($this->approvedMember, 'members');

        $response = $this->get('/member/panel');
        $response->assertStatus(200);
    }

    // ==================== LEGACY MEMBER LOGIN TESTS (/member/login) ====================

    /** @test */
    public function legacy_member_login_page_loads_successfully()
    {
        $response = $this->get('/member/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function member_can_login_with_civil_id_on_legacy_route()
    {
        $response = $this->post('/member/login', [
            'civil_id' => '12345678901',
            'password' => 'member123',
        ]);

        $response->assertRedirect('/member/dashboard');
        $this->assertAuthenticatedAs($this->approvedMember, 'members');
    }

    /** @test */
    public function legacy_member_login_validates_civil_id()
    {
        $response = $this->post('/member/login', [
            'password' => 'member123',
        ]);

        $response->assertSessionHasErrors('civil_id');
        $this->assertGuest('members');
    }

    /** @test */
    public function legacy_member_login_validates_password()
    {
        $response = $this->post('/member/login', [
            'civil_id' => '12345678901',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest('members');
    }

    /** @test */
    public function pending_member_cannot_login_on_legacy_route()
    {
        $response = $this->post('/member/login', [
            'civil_id' => '98765432109',
            'password' => 'member123',
        ]);

        $response->assertSessionHasErrors('civil_id');
        $response->assertSessionHasErrorsIn('default', ['civil_id']);
        $this->assertGuest('members');
    }

    /** @test */
    public function member_cannot_login_with_wrong_credentials_on_legacy_route()
    {
        $response = $this->post('/member/login', [
            'civil_id' => '12345678901',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('civil_id');
        $this->assertGuest('members');
    }

    // ==================== AUTHENTICATION GUARDS SEPARATION ====================

    /** @test */
    public function admin_and_member_guards_are_separate()
    {
        // Login as admin
        $this->actingAs($this->admin, 'web');
        $this->assertTrue(auth('web')->check());
        $this->assertFalse(auth('members')->check());

        // Logout admin
        auth('web')->logout();

        // Login as member
        $this->actingAs($this->approvedMember, 'members');
        $this->assertTrue(auth('members')->check());
        $this->assertFalse(auth('web')->check());
    }

    /** @test */
    public function admin_cannot_access_member_panel()
    {
        $this->actingAs($this->admin, 'web');

        $response = $this->get('/member/panel');
        $response->assertRedirect('/member/panel/login');
    }

    /** @test */
    public function member_cannot_access_admin_panel()
    {
        $this->actingAs($this->approvedMember, 'members');

        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    // ==================== SESSION & REDIRECT TESTS ====================

    /** @test */
    public function session_is_regenerated_on_admin_login()
    {
        $oldSessionId = session()->getId();

        $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $newSessionId = session()->getId();
        $this->assertNotEquals($oldSessionId, $newSessionId);
    }

    /** @test */
    public function session_is_regenerated_on_member_login()
    {
        $oldSessionId = session()->getId();

        $this->post('/member/login', [
            'civil_id' => '12345678901',
            'password' => 'member123',
        ]);

        $newSessionId = session()->getId();
        $this->assertNotEquals($oldSessionId, $newSessionId);
    }

    /** @test */
    public function guest_accessing_admin_is_redirected_to_login()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function guest_accessing_member_panel_is_redirected_to_login()
    {
        $response = $this->get('/member/panel');
        $response->assertRedirect('/member/panel/login');
    }

    /** @test */
    public function guest_accessing_member_dashboard_is_redirected_to_login()
    {
        $response = $this->get('/member/dashboard');
        $response->assertRedirect('/member/login');
    }

    // ==================== REMEMBER ME FUNCTIONALITY ====================

    /** @test */
    public function admin_login_supports_remember_me()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin, 'web');
    }

    /** @test */
    public function member_login_supports_remember_me()
    {
        $response = $this->post('/member/login', [
            'civil_id' => '12345678901',
            'password' => 'member123',
            'remember' => true,
        ]);

        $response->assertRedirect('/member/dashboard');
        $this->assertAuthenticatedAs($this->approvedMember, 'members');
    }

    // ==================== ERROR HANDLING TESTS ====================

    /** @test */
    public function admin_login_shows_appropriate_error_messages()
    {
        $response = $this->post('/admin/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $response->assertStatus(302);
    }

    /** @test */
    public function member_login_shows_appropriate_error_for_invalid_credentials()
    {
        $response = $this->post('/member/login', [
            'civil_id' => '99999999999',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('civil_id');
        $response->assertStatus(302);
    }

    /** @test */
    public function member_login_shows_appropriate_error_for_pending_approval()
    {
        $response = $this->post('/member/login', [
            'civil_id' => $this->pendingMember->civil_id,
            'password' => 'member123',
        ]);

        $response->assertSessionHasErrors('civil_id');
        $response->assertSessionHasErrorsIn('default', ['civil_id']);
    }

    // ==================== LOGOUT FUNCTIONALITY ====================

    /** @test */
    public function admin_can_logout()
    {
        $this->actingAs($this->admin, 'web');
        $this->assertTrue(auth('web')->check());

        $response = $this->post('/admin/logout');
        
        $this->assertGuest('web');
        $response->assertRedirect();
    }

    /** @test */
    public function member_can_logout_from_legacy_route()
    {
        $this->actingAs($this->approvedMember, 'members');
        $this->assertTrue(auth('members')->check());

        $response = $this->post('/member/logout');
        
        $this->assertGuest('members');
        $response->assertRedirect('/member/login');
    }

    /** @test */
    public function logout_invalidates_session()
    {
        $this->actingAs($this->approvedMember, 'members');
        $sessionId = session()->getId();

        $this->post('/member/logout');

        $newSessionId = session()->getId();
        $this->assertNotEquals($sessionId, $newSessionId);
    }
}




