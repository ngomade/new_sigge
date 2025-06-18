<?php

namespace App\Mail\requetes;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;

class RequeteResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requete;
    public $reponse;

    /**
     * Create a new message instance.
     */
    public function __construct($requete, $reponse)
    {
        $this->requete = $requete;
        $this->reponse = $reponse;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Nouvelle réponse à votre requête')
                    ->view('emails.requetes.response');
    }



    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.requetes.response',
            with: [
                'requete' => $this->requete,
                'reponse' => $this->reponse,
            ],
        );
    }
}