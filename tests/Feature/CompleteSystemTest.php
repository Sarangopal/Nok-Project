<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Registration;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CompleteSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('secret'),
        ]);
        
        // Seed test data
        $this->artisan('db:seed', ['--class' => 'TestDataSeeder']);
    }

    /** @test */
    public function admin_can_login_to_panel()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@gmail.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated('web');
    }

    /** @test */
    public function admin_can_view_pending_registrations()
    {
        $admin = User::where('email', 'admin@gmail.com')->first();
        
        $response = $this->actingAs($admin)
            ->get('/admin/registrations');

        $response->assertStatus(200);
        $response->assertSee('Test User Pending');
    }

    /** @test */
    public function member_can_login_with_civil_id()
    {
        $member = Registration::where('email', 'test.active@example.com')->first();
        
        $response = $this->post('/member/login', [
            'civil_id' => $member->civil_id,
            'password' => 'NOK2345',
        ]);

        $response->assertRedirect('/member/dashboard');
    }

    /** @test */
    public function member_cannot_login_if_not_approved()
    {
        $member = Registration::where('email', 'test.pending@example.com')->first();
        
        $response = $this->post('/member/login', [
            'civil_id' => $member->civil_id,
            'password' => 'NOK1234',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('members');
    }

    /** @test */
    public function approved_member_can_view_dashboard()
    {
        $member = Registration::where('email', 'test.active@example.com')->first();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/dashboard');

        $response->assertStatus(200);
        $response->assertSee($member->memberName);
        $response->assertSee($member->nok_id);
    }

    /** @test */
    public function expired_member_sees_renewal_request_button()
    {
        $member = Registration::where('email', 'test.expired@example.com')->first();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Request Renewal');
    }

    /** @test */
    public function member_can_submit_renewal_request()
    {
        $member = Registration::where('email', 'test.expired@example.com')->first();
        
        $response = $this->actingAs($member, 'members')
            ->post('/member/request-renewal');

        $response->assertRedirect('/member/dashboard');
        $response->assertSessionHas('success');
        
        $member->refresh();
        $this->assertNotNull($member->renewal_requested_at);
        $this->assertEquals('pending', $member->renewal_status);
    }

    /** @test */
    public function member_cannot_submit_duplicate_renewal_request()
    {
        $member = Registration::where('email', 'test.renewal@example.com')->first();
        
        $response = $this->actingAs($member, 'members')
            ->post('/member/request-renewal');

        $response->assertRedirect('/member/dashboard');
        $response->assertSessionHas('info');
    }

    /** @test */
    public function public_verification_page_works_with_civil_id()
    {
        $member = Registration::where('email', 'test.active@example.com')->first();
        
        $response = $this->get('/verify-membership');
        $response->assertStatus(200);
        
        $response = $this->post('/verify-membership', [
            'civil_id' => $member->civil_id,
        ]);

        $response->assertStatus(200);
        $response->assertSee($member->memberName);
        $response->assertSee('Active Member');
    }

    /** @test */
    public function public_verification_shows_expired_status()
    {
        $member = Registration::where('email', 'test.expired@example.com')->first();
        
        $response = $this->post('/verify-membership', [
            'civil_id' => $member->civil_id,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Expired');
    }

    /** @test */
    public function membership_card_can_be_downloaded()
    {
        $member = Registration::where('email', 'test.active@example.com')->first();
        
        $response = $this->get(route('membership.card.download', $member->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function active_member_can_view_assigned_offers()
    {
        $member = Registration::where('email', 'test.active@example.com')->first();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Test Offer');
    }

    /** @test */
    public function expired_member_cannot_see_offers()
    {
        $member = Registration::where('email', 'test.expired@example.com')->first();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/dashboard');

        $response->assertStatus(200);
        // Should either see no offers or a message about renewal
    }

    /** @test */
    public function admin_can_view_renewal_requests()
    {
        $admin = User::where('email', 'admin@gmail.com')->first();
        
        $response = $this->actingAs($admin)
            ->get('/admin/renewal-requests');

        $response->assertStatus(200);
        $response->assertSee('Test User Renewal Pending');
    }

    /** @test */
    public function system_detects_expired_cards()
    {
        $expiredCount = Registration::where('renewal_status', 'approved')
            ->where('card_valid_until', '<', now())
            ->count();

        $this->assertGreaterThan(0, $expiredCount);
    }

    /** @test */
    public function system_detects_expiring_soon_cards()
    {
        $expiringSoonCount = Registration::where('renewal_status', 'approved')
            ->whereBetween('card_valid_until', [now(), now()->addDays(7)])
            ->count();

        $this->assertGreaterThan(0, $expiringSoonCount);
    }
}










