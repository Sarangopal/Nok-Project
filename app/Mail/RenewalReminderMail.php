<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration, public int $daysLeft)
    {
    }

    public function build()
    {
        return $this->subject('Membership Renewal Reminder')
            ->markdown('emails.membership.renewal_reminder')
            ->with([
                'memberName' => $this->registration->memberName,
                'validUntil' => optional($this->registration->card_valid_until)?->toDateString(),
                'daysLeft' => $this->daysLeft,
            ]);
    }
}



