<?php

namespace App\Mail;

use App\Models\Registration;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalRequestSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Registration|Member $member;

    public function __construct(Registration|Member $member)
    {
        $this->member = $member;
    }

    public function build()
    {
        return $this->subject('✅ Renewal Request Submitted Successfully - NOK Kuwait')
            ->markdown('emails.membership.renewal_request_submitted')
            ->with([
                'member' => $this->member,
            ]);
    }
}

