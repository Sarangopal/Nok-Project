<?php

namespace Tests\Browser;

use App\Models\Registration;
use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MemberRenewalFlowTest extends DuskTestCase
{
    /**
     * Test complete member renewal flow:
     * 1. Member login
     * 2. View expiring card
     * 3. Submit renewal request
     * 4. Verify request was submitted
     */
    public function test_member_can_login_and_submit_renewal_request()
    {
        // Create or get test member with expiring card
        $member = Registration::where('email', 'renewal.test@nokw.com')->first();
        
        if (!$member) {
            $member = Registration::create([
                'member_type' => 'new',
                'nok_id' => 'NOK' . str_pad((string)(Registration::max('id') + 1), 6, '0', STR_PAD_LEFT),
                'memberName' => 'Renewal Test Member',
                'email' => 'renewal.test@nokw.com',
                'civil_id' => 'TEST' . rand(100000, 999999),
                'password' => Hash::make('password123'),
                'age' => 35,
                'gender' => 'M',
                'mobile' => '555' . rand(10000, 99999),
                'doj' => now()->subMonths(11),
                'login_status' => 'approved',
                'renewal_status' => 'approved',
                'card_issued_at' => now()->subMonths(11),
                'card_valid_until' => now()->addDays(15), // Expiring in 15 days
            ]);
        }
        
        // Reset renewal request
        $member->renewal_requested_at = null;
        $member->renewal_status = 'approved';
        $member->save();

        $this->browse(function (Browser $browser) use ($member) {
            $browser->visit('/member/panel')
                    ->assertSee('Member Login')
                    ->type('civil_id', $member->civil_id)
                    ->type('password', 'password123')
                    ->press('Login')
                    ->waitForLocation('/member/dashboard', 10)
                    ->assertSee('Welcome, ' . $member->memberName)
                    ->assertSee('Membership Card')
                    ->screenshot('member-dashboard-before-renewal');

            // Check if expiring soon warning is shown
            $daysLeft = now()->diffInDays($member->card_valid_until, false);
            if ($daysLeft <= 30 && $daysLeft > 0) {
                $browser->assertSee('Your membership expires in');
            }

            // Submit renewal request
            if ($browser->seeIn('.card-body', 'Request')) {
                $browser->press('Request Early Renewal')
                        ->waitForText('Renewal request submitted', 5)
                        ->assertSee('Waiting for admin approval')
                        ->screenshot('member-dashboard-renewal-requested');
            }

            // Logout
            $browser->press('Logout')
                    ->waitForLocation('/member/panel', 5);
        });

        // Verify renewal request was saved in database
        $member->refresh();
        $this->assertNotNull($member->renewal_requested_at);
        $this->assertEquals('pending', $member->renewal_status);
        
        echo "\n✅ Member successfully logged in and submitted renewal request\n";
        echo "   Member: {$member->memberName}\n";
        echo "   Email: {$member->email}\n";
        echo "   Renewal Status: {$member->renewal_status}\n";
        echo "   Renewal Requested At: {$member->renewal_requested_at}\n";
    }

    /**
     * Test that member with expired card sees correct warning
     */
    public function test_member_with_expired_card_sees_warning()
    {
        $member = Registration::create([
            'member_type' => 'new',
            'nok_id' => 'NOK' . str_pad((string)(Registration::max('id') + 1000), 6, '0', STR_PAD_LEFT),
            'memberName' => 'Expired Card Test',
            'email' => 'expired.test@nokw.com',
            'civil_id' => 'EXPIRED' . rand(100000, 999999),
            'password' => Hash::make('password123'),
            'age' => 40,
            'gender' => 'F',
            'mobile' => '555' . rand(10000, 99999),
            'doj' => now()->subYears(2),
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'card_issued_at' => now()->subYears(2),
            'card_valid_until' => now()->subDays(10), // Expired 10 days ago
        ]);

        $this->browse(function (Browser $browser) use ($member) {
            $browser->visit('/member/panel')
                    ->type('civil_id', $member->civil_id)
                    ->type('password', 'password123')
                    ->press('Login')
                    ->waitForLocation('/member/dashboard', 10)
                    ->assertSee('Your membership has expired')
                    ->assertSee('Request Renewal Now')
                    ->screenshot('member-dashboard-expired-card');
        });
        
        echo "\n✅ Member with expired card sees correct warning\n";
    }

    /**
     * Test member login with incorrect credentials
     */
    public function test_member_login_fails_with_wrong_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/member/panel')
                    ->type('civil_id', 'WRONG123')
                    ->type('password', 'wrongpassword')
                    ->press('Login')
                    ->waitFor('.alert-danger', 5)
                    ->assertSee('credentials')
                    ->screenshot('member-login-failed');
        });
        
        echo "\n✅ Login correctly fails with wrong credentials\n";
    }
}

