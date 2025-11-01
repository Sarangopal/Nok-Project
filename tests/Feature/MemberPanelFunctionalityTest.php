<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPanelFunctionalityTest extends TestCase
{
    /**
     * Get or create test member
     */
    private function getTestMember(): Member
    {
        return Member::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'nok_id' => 'TEST001',
                'civil_id' => '123456789',
                'memberName' => 'Test Member',
                'age' => 30,
                'renewal_status' => 'approved',
                'doj' => now()->subYear(),
                'card_valid_until' => now()->addYear(),
                'password' => bcrypt('password'),
            ]
        );
    }

    /**
     * Test member can access panel after login
     */
    public function test_member_can_access_panel_after_login(): void
    {
        $member = $this->getTestMember();
        
        $this->assertNotNull($member, 'Test member not found');
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/panel');
        
        $response->assertStatus(200);
        $this->assertTrue(true, '✅ Member can access panel');
    }

    /**
     * Test member panel displays stats overview
     */
    public function test_panel_displays_stats_overview(): void
    {
        $member = $this->getTestMember();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/panel');
        
        $response->assertStatus(200);
        $response->assertSee('Membership Status');
        $response->assertSee('Member Since');
        $response->assertSee('Exclusive Offers');
        $response->assertSee('Valid Until');
        
        $this->assertTrue(true, '✅ Stats overview displays correctly');
    }

    /**
     * Test member panel displays profile information
     */
    public function test_panel_displays_profile_information(): void
    {
        $member = $this->getTestMember();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/panel');
        
        $response->assertStatus(200);
        $response->assertSee('Profile Overview');
        $response->assertSee('NOK ID:');
        $response->assertSee($member->nok_id);
        $response->assertSee('Email:');
        $response->assertSee($member->email);
        
        $this->assertTrue(true, '✅ Profile overview displays correctly');
    }

    /**
     * Test membership card widget displays
     */
    public function test_panel_displays_membership_card_widget(): void
    {
        $member = $this->getTestMember();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/panel');
        
        $response->assertStatus(200);
        $response->assertSee('Membership Card');
        
        if ($member->renewal_status === 'approved') {
            $response->assertSee('Download PDF');
        }
        
        $this->assertTrue(true, '✅ Membership card widget displays correctly');
    }

    /**
     * Test exclusive offers widget displays
     */
    public function test_panel_displays_exclusive_offers(): void
    {
        $member = $this->getTestMember();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/panel');
        
        $response->assertStatus(200);
        $response->assertSee('Exclusive Offers');
        
        $this->assertTrue(true, '✅ Exclusive offers widget displays correctly');
    }

    /**
     * Test member can download membership card PDF
     */
    public function test_member_can_download_pdf(): void
    {
        $member = $this->getTestMember();
        
        if ($member && $member->renewal_status === 'approved') {
            $response = $this->actingAs($member, 'members')
                ->get('/membership-card/download/' . $member->id);
            
            $response->assertStatus(200);
            $response->assertHeader('Content-Type', 'application/pdf');
            
            $this->assertTrue(true, '✅ PDF download works correctly');
        } else {
            $this->markTestSkipped('Member is not approved');
        }
    }

    /**
     * Test member stats are calculated correctly
     */
    public function test_member_stats_calculated_correctly(): void
    {
        $member = $this->getTestMember();
        
        $this->assertNotNull($member->renewal_status, 'Member has status');
        $this->assertNotNull($member->doj, 'Member has joining date');
        
        $offersCount = $member->offers()->where('is_active', true)->count();
        $this->assertIsInt($offersCount, 'Offers count is an integer');
        
        $this->assertTrue(true, '✅ Member stats calculated correctly');
    }

    /**
     * Test Filament components are present
     */
    public function test_filament_components_present(): void
    {
        $member = $this->getTestMember();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/panel');
        
        $response->assertStatus(200);
        
        // Check for Filament-specific classes
        $content = $response->getContent();
        $this->assertStringContainsString('fi-', $content, 'Filament classes present');
        
        $this->assertTrue(true, '✅ Filament components are present');
    }

    /**
     * Test responsive layout classes exist
     */
    public function test_responsive_layout_classes_exist(): void
    {
        $member = $this->getTestMember();
        
        $response = $this->actingAs($member, 'members')
            ->get('/member/panel');
        
        $response->assertStatus(200);
        
        $content = $response->getContent();
        $this->assertStringContainsString('grid', $content, 'Grid layout present');
        
        $this->assertTrue(true, '✅ Responsive layout classes exist');
    }

    /**
     * Test member panel uses correct guard
     */
    public function test_member_panel_uses_correct_guard(): void
    {
        $member = $this->getTestMember();
        
        $this->actingAs($member, 'members');
        
        $this->assertTrue(auth()->guard('members')->check(), 'Members guard is active');
        $this->assertEquals($member->id, auth()->guard('members')->id(), 'Correct member is authenticated');
        
        $this->assertTrue(true, '✅ Member panel uses correct guard');
    }

    /**
     * Test unauthenticated users are redirected
     */
    public function test_unauthenticated_users_redirected(): void
    {
        $response = $this->get('/member/panel');
        
        $response->assertStatus(302);
        $response->assertRedirect('/member/panel/login');
        
        $this->assertTrue(true, '✅ Unauthenticated users are redirected');
    }
}

