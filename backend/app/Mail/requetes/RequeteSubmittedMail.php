<?php

namespace App\Mail\requetes;

use App\Models\requetes\Requete;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequeteSubmittedMail extends Mailable
{
   use Queueable, SerializesModels;

    public $requete;

    public function __construct(Requete $requete)
    {
        $this->requete = $requete;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de soumission de votre requête - ' . $this->requete->code_requete,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.requete-submitted',
            with: [
                'requete' => $this->requete,
            ],
        );
    }
}
