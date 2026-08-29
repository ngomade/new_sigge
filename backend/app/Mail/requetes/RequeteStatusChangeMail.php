<?php

namespace App\Mail\requetes;

use App\Models\requetes\Requete;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequeteStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requete;

    public $oldStatus;

    public $newStatus;

    public function __construct(Requete $requete, string $oldStatus, string $newStatus)
    {
        $this->requete = $requete;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour de votre requête - '.$this->requete->code_requete,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.requetes.requete-status-changed',
            with: [
                'requete' => $this->requete,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
            ],
        );
    }
}
