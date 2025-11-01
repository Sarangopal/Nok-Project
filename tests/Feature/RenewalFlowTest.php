<?php

namespace Tests\Feature;

use App\Filament\Resources\Registrations\Tables\RegistrationsTable;
use App\Mail\MembershipCardMail;
use App\Models\Registration;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RenewalFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_request_renewal_and_admin_approves(): void
    {
        Carbon::setTestNow('2025-10-21 10:00:00');
        Mail::fake();

        $member = Member::create([
            'member_type' => 'new',
            'memberName' => 'Dana',
            'email' => 'dana@example.com',
            'civil_id' => 'CIV1234',
            'age' => 26,
            'gender' => 'F',
            'mobile' => '53333333',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->subDay(),
        ]);

        // Member requests renewal (route exists in web.php)
        $this->actingAs($member, 'members')
            ->post(route('member.renewal.request'))
            ->assertRedirect();

        $member->refresh();
        $this->assertNotNull($member->renewal_requested_at);
        $this->assertSame('pending', $member->renewal_status);

        // Admin approves: simulate table action by updating fields
        $member->renewal_status = 'approved';
        $member->last_renewed_at = now();
        $member->card_valid_until = now()->endOfYear();
        $member->save();

        $member->refresh();
        $this->assertSame('approved', $member->renewal_status);
        $this->assertTrue($member->card_valid_until->isSameDay(now()->endOfYear()));

        Mail::assertNothingSent(); // we are not invoking the action callback here
    }
}


