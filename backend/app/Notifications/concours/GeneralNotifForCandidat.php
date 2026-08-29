<?php

namespace App\Notifications\concours;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotifForCandidat extends Notification
{
    use Queueable;

    public string $objet;

    public string $contenu;

    /**
     * Create a new notification instance.
     */
    public function __construct($contenu, $objet)
    {
        $this->contenu = $contenu;
        $this->objet = $objet;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->objet)
            ->greeting("Salut $notifiable->ca_prenom $notifiable->ca_nom")
            ->view('concours.general_mail_for_candidat', [
                'contenu' => $this->contenu,
                'objet' => $this->objet,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sujet' => 'Email de groupe',
            'objet' => $this->objet,
            'contenu' => $this->contenu,
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
        ];
    }
}
