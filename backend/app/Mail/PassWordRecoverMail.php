<?php

namespace App\Mail;

use App\Models\Users;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PassWordRecoverMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    protected $pwd;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Users $user, $pwd)
    {
        $this->user = $user;
        $this->pwd = $pwd;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('sige_app.mails.info_connexion')
            ->subject('Informations perdues')
            ->with('user', $this->user)
            ->with('pwd', $this->pwd);
    }
}
