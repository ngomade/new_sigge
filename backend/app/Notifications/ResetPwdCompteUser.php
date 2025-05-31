<?php

namespace App\Notifications;

use App\Models\concours\Compte;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPwdCompteUser extends Notification
{
    use Queueable;

    public string $code;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->view("concours.reset_password", [
                'code' => $this->code,
                'user' => $notifiable,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $email = $notifiable instanceof Compte ? $notifiable->ca_email : $notifiable->email_pers;
        return [
            "code" => $this->code,
            "message" => "Un code de réinitialisation a été envoyé à : " . $email,
            "action" => "reset_password",
            "type_user" => $notifiable instanceof Compte ? 'candidat' : 'personnel',
        ];
    }
}
