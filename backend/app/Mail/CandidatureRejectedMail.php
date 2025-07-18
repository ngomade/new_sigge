<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidatureRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;
    public $motif;

    public function __construct($candidature, $motif)
    {
        $this->candidature = $candidature;
        $this->motif = $motif;
    }

    public function build()
    {
        return $this->subject('Votre candidature a été rejetée')
            ->view('emails.labo.candidature_rejetee');
    }
}
