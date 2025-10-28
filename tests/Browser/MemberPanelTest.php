<?php

namespace Tests\Browser;

use App\Models\Member;
use App\Models\Offer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MemberPanelTest extends DuskTestCase
{
    /**
     * Test member panel login and dashboard display
     *
     * @return void
     */
    public function testMemberPanelLoginAndDashboard()
    {
        // Get the test member
        $member = Member::where('email', 'samkrishna23@gmail.com')->first();
        
        $this->browse(function (Browser $browser) use ($member) {
            $browser->visit('http://127.0.0.1:8000/member/panel/login')
                    ->assertSee('Civil ID')
                    ->type('data.civil_id', $member->civil_id)
                    ->type('data.password', 'NOK8649')
                    ->press('Sign in')
                    ->waitForLocation('/member/panel')
                    ->assertSee('My Dashboard')
                    ->screenshot('member-panel-dashboard');
        });
    }

    /**
     * Test stats overview widget
     *
     * @return void
     */
    public function testStatsOverviewWidget()
    {
        $member = Member::where('email', 'samkrishna23@gmail.com')->first();
        
        $this->browse(function (Browser $browser) use ($member) {
            $browser->loginAs($member, 'members')
                    ->visit('http://127.0.0.1:8000/member/panel')
                    ->assertSee('Membership Status')
                    ->assertSee('Member Since')
                    ->assertSee('Exclusive Offers')
                    ->assertSee('Valid Until')
                    ->screenshot('stats-overview');
        });
    }

    /**
     * Test profile overview widget
     *
     * @return void
     */
    public function testProfileOverviewWidget()
    {
        $member = Member::where('email', 'samkrishna23@gmail.com')->first();
        
        $this->browse(function (Browser $browser) use ($member) {
            $browser->loginAs($member, 'members')
                    ->visit('http://127.0.0.1:8000/member/panel')
                    ->assertSee('Profile Overview')
                    ->assertSee('NOK ID:')
                    ->assertSee($member->nok_id)
                    ->assertSee('Email:')
                    ->assertSee($member->email)
                    ->assertSee('Mobile:')
                    ->assertSee('Status:')
                    ->screenshot('profile-overview');
        });
    }

    /**
     * Test membership card widget
     *
     * @return void
     */
    public function testMembershipCardWidget()
    {
        $member = Member::where('email', 'samkrishna23@gmail.com')->first();
        
        $this->browse(function (Browser $browser) use ($member) {
            $browser->loginAs($member, 'members')
                    ->visit('http://127.0.0.1:8000/member/panel')
                    ->assertSee('Membership Card')
                    ->assertSee('Download PDF')
                    ->screenshot('membership-card-widget');
        });
    }

    /**
     * Test exclusive offers widget
     *
     * @return void
     */
    public function testExclusiveOffersWidget()
    {
        $member = Member::where('email', 'samkrishna23@gmail.com')->first();
        
        $this->browse(function (Browser $browser) use ($member) {
            $browser->loginAs($member, 'members')
                    ->visit('http://127.0.0.1:8000/member/panel')
                    ->assertSee('Exclusive Offers for Members')
                    ->screenshot('exclusive-offers-widget');
        });
    }

    /**
     * Test styling is not broken
     *
     * @return void
     */
    public function testStylingNotBroken()
    {
        $member = Member::where('email', 'samkrishna23@gmail.com')->first();
        
        $this->browse(function (Browser $browser) use ($member) {
            $browser->loginAs($member, 'members')
                    ->visit('http://127.0.0.1:8000/member/panel')
                    ->waitFor('.fi-dashboard-page')
                    ->assertVisible('.fi-wi-stats-overview')
                    ->assertVisible('.fi-section')
                    ->screenshot('full-page-styling');
        });
    }

    /**
     * Test download PDF functionality
     *
     * @return void
     */
    public function testDownloadPDFButton()
    {
        $member = Member::where('email', 'samkrishna23@gmail.com')->first();
        
        $this->browse(function (Browser $browser) use ($member) {
            $browser->loginAs($member, 'members')
                    ->visit('http://127.0.0.1:8000/member/panel')
                    ->assertSeeLink('Download PDF')
                    ->screenshot('download-pdf-button');
        });
    }
}





