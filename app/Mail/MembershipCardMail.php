<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use App\Models\Registration;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use PDF;

class MembershipCardMail extends Mailable
{
    use Queueable, SerializesModels;

    // public Registration $registration;

    public $record;
    public $password;


    // public function __construct(Registration $registration)
    // {
    //     $this->registration = $registration;
    // }

    public function __construct($data)
    {
        // Support both array and object
        if (is_array($data)) {
            $this->record = $data['record'] ?? $data;
            $this->password = $data['password'] ?? null;
        } else {
            $this->record = $data;
            $this->password = null;
        }
    }

    // Define the email subject and other envelope properties
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Membership Card - Nightingales of Kuwait',
        );
    }

    // Define the content of the email using a markdown template
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.membership.card',  //Blade file: resources/views/emails/membership/card.blade.php
            with: [
                // 'registration' => $this->registration,
                'record' => is_array($this->record) ? (object)$this->record : $this->record,
                'password' => $this->password,
                'downloadLink' => route('membership.card.download', is_array($this->record) ? $this->record['id'] : $this->record->id),
                // 'downloadLink' => route('membership.card.download', $this->registration),
            ],
        );
    }
    // Define any attachments for the email (if needed)

    public function attachments(): array
    {
        try {
            $record = is_array($this->record) ? (object)$this->record : $this->record;
            $pdf = PDF::loadView('membership_card', ['record' => $record]);
            $recordId = is_array($this->record) ? $this->record['id'] : $this->record->id;
            return [
                \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), 'membership_card_'.$recordId.'.pdf')
                    ->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            return [];
        }
    }
}
