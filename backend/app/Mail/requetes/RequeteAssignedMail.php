<?php

namespace App\Mail\requetes;

use App\Models\Bureau;
use App\Models\requetes\Requete;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequeteAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requete;

    public $bureau;

    public function __construct(Requete $requete, string $codeBureau)
    {
        $this->requete = $requete;
        $this->bureau = Bureau::where('code_bureau', $codeBureau)->first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre requête a été transférée - '.$this->requete->code_requete,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.requetes.requete-assigned',
            with: [
                'requete' => $this->requete,
                'bureau' => $this->bureau,
            ],
        );
    }
}
