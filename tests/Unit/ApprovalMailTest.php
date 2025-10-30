<?php

namespace Tests\Unit;

use App\Mail\MembershipCardMail;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApprovalMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_membership_card_mail_is_sent_with_download_link(): void
    {
        Mail::fake();

        $reg = Registration::create([
            'member_type' => 'new',
            'memberName' => 'Mail User',
            'email' => 'mail@example.com',
            'civil_id' => 'MAIL1',
            'age' => 31,
            'gender' => 'M',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->endOfYear(),
        ]);

        Mail::to('mail@example.com')->send(new MembershipCardMail(['record' => $reg]));

        Mail::assertSent(MembershipCardMail::class, function ($mailable) use ($reg) {
            $data = $mailable->content()->with;
            return isset($data['downloadLink']) && ! empty($data['downloadLink']);
        });
    }
}











