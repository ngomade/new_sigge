<?php

namespace App\Notifications\laboratoires;

use App\Models\laboratoires\ProjetLabo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjetEcheanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $projet;

    public $joursRestants;

    public $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProjetLabo $projet, int $joursRestants, string $type = 'warning')
    {
        $this->projet = $projet;
        $this->joursRestants = $joursRestants;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $urgence = $this->joursRestants <= 7 ? 'URGENT' : ($this->joursRestants <= 30 ? 'IMPORTANT' : 'RAPPEL');

        return (new MailMessage)
            ->subject("[$urgence] Échéance de projet - {$this->projet->theme_projet}")
            ->greeting('Bonjour '.($notifiable->persLab->nom_pers_lab ?? 'Membre du laboratoire'))
            ->line("Le projet **{$this->projet->theme_projet}** arrive à échéance.")
            ->line('**Date de fin prévue :** '.\Carbon\Carbon::parse($this->projet->fin_projet)->format('d/m/Y'))
            ->line("**Jours restants :** {$this->joursRestants} jour(s)")
            ->action('Voir le projet', route('laboratoires.admin.projets.show', [$this->projet->code_lab, $this->projet->code_projet]))
            ->line('Merci de prendre les mesures nécessaires pour finaliser ce projet.')
            ->salutation('Cordialement, l\'équipe de gestion des laboratoires');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'projet_echeance',
            'projet_id' => $this->projet->code_projet,
            'projet_titre' => $this->projet->theme_projet,
            'jours_restants' => $this->joursRestants,
            'date_fin' => $this->projet->fin_projet,
            'urgence' => $this->type,
            'message' => "Le projet '{$this->projet->theme_projet}' arrive à échéance dans {$this->joursRestants} jour(s)",
            'action_url' => route('laboratoires.admin.projets.show', [$this->projet->code_lab, $this->projet->code_projet]),
            'laboratoire_code' => $this->projet->code_lab,
        ];
    }
}
