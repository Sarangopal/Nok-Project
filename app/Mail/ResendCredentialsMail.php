<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResendCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $record;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($record, $password)
    {
        $this->record = $record;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset - Login Credentials Updated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.credentials.resend',
            with: [
                'record' => $this->record,
                'password' => $this->password,
                'loginUrl' => url('/member/panel/login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

