<?php

namespace App\Mail;

use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\UserExterne;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExternePasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userExterne;

    public $laboratoire;

    public $newPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(UserExterne $userExterne, Laboratoire $laboratoire, $newPassword)
    {
        $this->userExterne = $userExterne;
        $this->laboratoire = $laboratoire;
        $this->newPassword = $newPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réinitialisation de mot de passe - '.$this->laboratoire->label_labo,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.externe.password_reset',
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
