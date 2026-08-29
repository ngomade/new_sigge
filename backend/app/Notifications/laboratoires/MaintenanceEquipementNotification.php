<?php

namespace App\Notifications\laboratoires;

use App\Models\laboratoires\Equipements;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceEquipementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $equipement;

    public $typeMaintenance;

    public $dateMaintenance;

    public $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(Equipements $equipement, string $typeMaintenance, $dateMaintenance, string $type = 'info')
    {
        $this->equipement = $equipement;
        $this->typeMaintenance = $typeMaintenance;
        $this->dateMaintenance = $dateMaintenance;
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
        $urgence = $this->type === 'urgent' ? 'URGENT' : ($this->type === 'warning' ? 'IMPORTANT' : 'RAPPEL');

        return (new MailMessage)
            ->subject("[$urgence] Maintenance d'équipement - {$this->equipement->nom_equip}")
            ->greeting('Bonjour '.($notifiable->persLab->nom_pers_lab ?? 'Membre du laboratoire'))
            ->line("Une maintenance est prévue pour l'équipement **{$this->equipement->nom_equip}**.")
            ->line("**Type de maintenance :** {$this->typeMaintenance}")
            ->line('**Date prévue :** '.\Carbon\Carbon::parse($this->dateMaintenance)->format('d/m/Y'))
            ->line("**Localisation :** {$this->equipement->localisation}")
            ->action('Voir l\'équipement', route('laboratoires.admin.equipements.show', [$this->equipement->code_lab, $this->equipement->code_equip]))
            ->line('Merci de planifier cette maintenance dans votre planning.')
            ->salutation('Cordialement, l\'équipe de gestion des laboratoires');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'maintenance_equipement',
            'equipement_id' => $this->equipement->code_equip,
            'equipement_nom' => $this->equipement->nom_equip,
            'type_maintenance' => $this->typeMaintenance,
            'date_maintenance' => $this->dateMaintenance,
            'urgence' => $this->type,
            'message' => "Maintenance prévue pour '{$this->equipement->nom_equip}' le ".\Carbon\Carbon::parse($this->dateMaintenance)->format('d/m/Y'),
            'action_url' => route('laboratoires.admin.equipements.show', [$this->equipement->code_lab, $this->equipement->code_equip]),
            'laboratoire_code' => $this->equipement->code_lab,
        ];
    }
}
