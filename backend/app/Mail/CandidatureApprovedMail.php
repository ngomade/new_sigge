<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidatureApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;

    public $tempPassword;

    public function __construct($candidature, $tempPassword)
    {
        $this->candidature = $candidature;
        $this->tempPassword = $tempPassword;
    }

    public function build()
    {
        return $this->subject('Votre candidature a été approuvée')
            ->view('emails.labo.candidature_approved');
    }
}
