<?php

namespace App\Mail;

use App\Models\Candidat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $candidat;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Candidat $candidat)
    {
        $this->candidat = $candidat;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('concours.frontend.mails.info_connexion')
            ->subject('Informations perdues')
            ->with('candidat', $this->candidat);
    }
}
