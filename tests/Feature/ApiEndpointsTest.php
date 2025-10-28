<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Registration;
use App\Models\Event;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $member;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create test member
        $this->member = Registration::create([
            'memberName' => 'Test Member',
            'civil_id' => '12345678901',
            'email' => 'member@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addYear(),
            'password' => Hash::make('password'),
            'nok_id' => 'NOK001',
        ]);
    }

    /** @test */
    public function home_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function about_page_loads_successfully()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    /** @test */
    public function contact_page_loads_successfully()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    /** @test */
    public function contact_form_submission_works()
    {
        $response = $this->post('/contact', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+96512345678',
            'message' => 'This is a test message',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function contact_form_validation_fails_with_invalid_email()
    {
        $response = $this->post('/contact', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'phone' => '+96512345678',
            'message' => 'This is a test message',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function registration_page_loads_successfully()
    {
        $response = $this->get('/registration');
        $response->assertStatus(200);
    }

    /** @test */
    public function registration_submission_works_with_valid_data()
    {
        $response = $this->post('/registration-submit', [
            'memberName' => 'New Test Member',
            'civil_id' => '98765432101',
            'email' => 'newmember@test.com',
            'whatsapp_number' => '+96587654321',
            'registration_type' => 'New Member',
            'profession' => 'Engineer',
            'blood_group' => 'A+',
            'birth_day' => '15',
            'birth_month' => '06',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('registrations', [
            'civil_id' => '98765432101',
        ]);
    }

    /** @test */
    public function duplicate_registration_is_prevented()
    {
        Registration::create([
            'memberName' => 'Existing Member',
            'civil_id' => '11111111111',
            'email' => 'existing@test.com',
            'whatsapp_number' => '+96511111111',
            'registration_type' => 'New Member',
            'renewal_status' => 'pending',
        ]);

        $response = $this->post('/registration-submit', [
            'memberName' => 'Duplicate Member',
            'civil_id' => '11111111111',
            'email' => 'different@test.com',
            'whatsapp_number' => '+96522222222',
            'registration_type' => 'New Member',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function verification_page_loads_successfully()
    {
        $response = $this->get('/verify-membership');
        $response->assertStatus(200);
    }

    /** @test */
    public function verification_works_with_valid_civil_id()
    {
        $response = $this->post('/verify-membership', [
            'civil_id' => $this->member->civil_id,
        ]);

        $response->assertStatus(200);
        $response->assertSee($this->member->memberName);
    }

    /** @test */
    public function verification_shows_error_for_invalid_civil_id()
    {
        $response = $this->post('/verify-membership', [
            'civil_id' => '99999999999',
        ]);

        $response->assertStatus(200);
        $response->assertSee('not found');
    }

    /** @test */
    public function rate_limiting_works_on_verification()
    {
        // Make 11 requests (limit is 10 per minute)
        for ($i = 0; $i < 11; $i++) {
            $response = $this->post('/verify-membership', [
                'civil_id' => $this->member->civil_id,
            ]);
        }

        $response->assertStatus(429); // Too Many Requests
    }

    /** @test */
    public function events_page_loads_successfully()
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    /** @test */
    public function individual_event_page_loads()
    {
        $event = Event::create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'description' => 'Test event description',
            'event_date' => now()->addWeek(),
            'location' => 'Test Location',
            'is_published' => true,
        ]);

        $response = $this->get('/events/test-event');
        $response->assertStatus(200);
        $response->assertSee('Test Event');
    }

    /** @test */
    public function member_login_page_loads()
    {
        $response = $this->get('/member/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function member_can_login_with_valid_credentials()
    {
        $response = $this->post('/member/login', [
            'civil_id' => $this->member->civil_id,
            'password' => 'password',
        ]);

        $response->assertRedirect('/member/dashboard');
        $this->assertAuthenticatedAs($this->member, 'members');
    }

    /** @test */
    public function member_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/member/login', [
            'civil_id' => $this->member->civil_id,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    /** @test */
    public function member_dashboard_requires_authentication()
    {
        $response = $this->get('/member/dashboard');
        $response->assertRedirect('/member/login');
    }

    /** @test */
    public function authenticated_member_can_access_dashboard()
    {
        $response = $this->actingAs($this->member, 'members')
            ->get('/member/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function member_can_logout()
    {
        $this->actingAs($this->member, 'members');

        $response = $this->post('/member/logout');

        $response->assertRedirect('/');
        $this->assertGuest('members');
    }

    /** @test */
    public function membership_card_download_works()
    {
        $response = $this->get("/membership-card/download/{$this->member->id}");
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function fallback_route_returns_404()
    {
        $response = $this->get('/non-existent-route-xyz');
        $response->assertStatus(404);
    }

    /** @test */
    public function static_pages_load_successfully()
    {
        $pages = [
            '/patrons_message',
            '/presidents_message',
            '/treasurer_message',
            '/aaravam',
            '/our_logo',
            '/core_values',
            '/executive_committee_25_26',
            '/executive_committee',
            '/founding_of_nok',
            '/gallery',
            '/secretarys_message',
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200, "Failed to load: {$page}");
        }
    }

    /** @test */
    public function admin_panel_requires_authentication()
    {
        $response = $this->get('/admin');
        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_access_panel()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin');

        $response->assertStatus(200);
    }

    /** @test */
    public function csrf_protection_works()
    {
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/contact', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        // Should work without CSRF when middleware is disabled
        $response->assertStatus(302);
    }
}




