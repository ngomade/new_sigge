<?php

namespace App\Mail;

use App\Models\concours\Candidat;
use App\Models\Mails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $candidat;
    protected $mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Candidat $candidat, $mail)
    {
        $this->candidat = $candidat;
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('concours.frontend.mails.info_update')
        ->subject("Mise à jour de votre Profil")
        ->with("candidat", $this->candidat)
        ->with("mail", $this->mail);
    }
}
