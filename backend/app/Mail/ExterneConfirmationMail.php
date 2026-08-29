<?php

namespace App\Mail;

use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\UserExterne;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExterneConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userExterne;

    public $laboratoire;

    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(UserExterne $userExterne, Laboratoire $laboratoire, $password)
    {
        $this->userExterne = $userExterne;
        $this->laboratoire = $laboratoire;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de création de compte - '.$this->laboratoire->label_labo,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.externe.confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
