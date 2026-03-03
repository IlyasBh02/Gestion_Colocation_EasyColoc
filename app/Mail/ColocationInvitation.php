<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ColocationInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $token,
        public string $colocationName,
        public string $inviterName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation to join ' . $this->colocationName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
