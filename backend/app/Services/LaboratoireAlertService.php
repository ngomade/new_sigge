<?php

namespace App\Services;

use App\Models\laboratoires\ProjetLabo;
use App\Models\laboratoires\Equipements;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\LabNotif;
use App\Notifications\laboratoires\ProjetEcheanceNotification;
use App\Notifications\laboratoires\MaintenanceEquipementNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaboratoireAlertService
{
    /**
     * Vérifier les échéances de projets et envoyer des notifications
     */
    public function checkProjetEcheances()
    {
        $aujourdhui = Carbon::now();

        // Projets qui arrivent à échéance dans les 30 prochains jours
        $projetsEcheance = ProjetLabo::where('fin_projet', '>=', $aujourdhui)
            ->where('fin_projet', '<=', $aujourdhui->copy()->addDays(30))
            ->where('statut_projet', '!=', 'Terminé')
            ->get();

        foreach ($projetsEcheance as $projet) {
            $joursRestants = $aujourdhui->diffInDays($projet->fin_projet, false);

            // Déterminer le type d'alerte
            $type = 'info';
            if ($joursRestants <= 7) {
                $type = 'urgent';
            } elseif ($joursRestants <= 14) {
                $type = 'warning';
            }

            // Envoyer notification aux membres du laboratoire
            $membres = LaboratoirePersLab::where('code_lab', $projet->code_lab)
                ->where('statut_pers_lab', 'Actif')
                ->get();

            foreach ($membres as $membre) {
                // Vérifier si la notification n'a pas déjà été envoyée aujourd'hui
                $notificationExistante = LabNotif::where('code_lab', $projet->code_lab)
                    ->where('id_pers_lab_destinataire', $membre->id_pers_lab)
                    ->where('type', 'projet_echeance')
                    ->where('message', 'LIKE', "%{$projet->code_projet}%")
                    ->whereDate('created_at', $aujourdhui)
                    ->first();

                if (!$notificationExistante) {
                    // Créer notification dans la base de données
                    LabNotif::create([
                        'code_lab' => $projet->code_lab,
                        'id_pers_lab_expediteur' => 'SYSTEM',
                        'id_pers_lab_destinataire' => $membre->id_pers_lab,
                        'type' => 'projet_echeance',
                        'titre' => "Échéance de projet - {$projet->theme_projet}",
                        'message' => "Le projet '{$projet->theme_projet}' arrive à échéance dans {$joursRestants} jour(s). Date de fin : " . Carbon::parse($projet->fin_projet)->format('d/m/Y'),
                        'lu' => false
                    ]);

                    // Envoyer notification par email
                    $membre->notify(new ProjetEcheanceNotification($projet, $joursRestants, $type));
                }
            }
        }
    }

        /**
     * Vérifier les maintenances d'équipements et envoyer des notifications
     */
    public function checkMaintenanceEquipements()
    {
        $aujourdhui = Carbon::now();

        // Récupérer les entretiens/réparations prévus dans les 30 prochains jours
        $entretiensMaintenance = \App\Models\laboratoires\EntretienReparation::where('debut_entretien', '>=', $aujourdhui)
            ->where('debut_entretien', '<=', $aujourdhui->copy()->addDays(30))
            ->where('statut_entretien', '!=', 'Annulé')
            ->with('equipement')
            ->get();

        foreach ($entretiensMaintenance as $entretien) {
            $joursRestants = $aujourdhui->diffInDays($entretien->debut_entretien, false);

            // Déterminer le type d'alerte
            $type = 'info';
            if ($joursRestants <= 3) {
                $type = 'urgent';
            } elseif ($joursRestants <= 7) {
                $type = 'warning';
            }

            // Envoyer notification aux responsables du laboratoire
            $responsables = LaboratoirePersLab::where('code_lab', $entretien->equipement->code_lab)
                ->where('statut_pers_lab', 'Actif')
                ->whereIn('role_labo', ['Responsable', 'Admin'])
                ->get();

            foreach ($responsables as $responsable) {
                // Vérifier si la notification n'a pas déjà été envoyée aujourd'hui
                $notificationExistante = LabNotif::where('code_lab', $entretien->equipement->code_lab)
                    ->where('id_pers_lab_destinataire', $responsable->id_pers_lab)
                    ->where('type', 'maintenance_equipement')
                    ->where('message', 'LIKE', "%{$entretien->equipement->code_equip}%")
                    ->whereDate('created_at', $aujourdhui)
                    ->first();

                if (!$notificationExistante) {
                    // Créer notification dans la base de données
                    LabNotif::create([
                        'code_lab' => $entretien->equipement->code_lab,
                        'id_pers_lab_expediteur' => 'SYSTEM',
                        'id_pers_lab_destinataire' => $responsable->id_pers_lab,
                        'type' => 'maintenance_equipement',
                        'titre' => "Maintenance d'équipement - {$entretien->equipement->nom_equip}",
                        'message' => "Maintenance prévue pour '{$entretien->equipement->nom_equip}' dans {$joursRestants} jour(s). Date : " . Carbon::parse($entretien->debut_entretien)->format('d/m/Y'),
                        'lu' => false
                    ]);

                    // Envoyer notification par email
                    $responsable->notify(new MaintenanceEquipementNotification($entretien->equipement, $entretien->type_entretien, $entretien->debut_entretien, $type));
                }
            }
        }
    }

    /**
     * Exécuter toutes les vérifications d'alertes
     */
    public function runAllChecks()
    {
        $this->checkProjetEcheances();
        $this->checkMaintenanceEquipements();
    }

    /**
     * Obtenir les statistiques des alertes pour un laboratoire
     */
    public function getAlertStats($codeLab)
    {
        $aujourdhui = Carbon::now();

        // Projets en échéance
        $projetsUrgents = ProjetLabo::where('code_lab', $codeLab)
            ->where('fin_projet', '>=', $aujourdhui)
            ->where('fin_projet', '<=', $aujourdhui->copy()->addDays(7))
            ->where('statut_projet', '!=', 'Terminé')
            ->count();

        $projetsImportants = ProjetLabo::where('code_lab', $codeLab)
            ->where('fin_projet', '>=', $aujourdhui)
            ->where('fin_projet', '<=', $aujourdhui->copy()->addDays(30))
            ->where('fin_projet', '>', $aujourdhui->copy()->addDays(7))
            ->where('statut_projet', '!=', 'Terminé')
            ->count();

        // Maintenances d'équipements
        $maintenancesUrgentes = \App\Models\laboratoires\EntretienReparation::whereHas('equipement', function($query) use ($codeLab) {
                $query->where('code_lab', $codeLab);
            })
            ->where('debut_entretien', '>=', $aujourdhui)
            ->where('debut_entretien', '<=', $aujourdhui->copy()->addDays(3))
            ->where('statut_entretien', '!=', 'Annulé')
            ->count();

        $maintenancesImportantes = \App\Models\laboratoires\EntretienReparation::whereHas('equipement', function($query) use ($codeLab) {
                $query->where('code_lab', $codeLab);
            })
            ->where('debut_entretien', '>=', $aujourdhui)
            ->where('debut_entretien', '<=', $aujourdhui->copy()->addDays(30))
            ->where('debut_entretien', '>', $aujourdhui->copy()->addDays(3))
            ->where('statut_entretien', '!=', 'Annulé')
            ->count();

        return [
            'projets_urgents' => $projetsUrgents,
            'projets_importants' => $projetsImportants,
            'maintenances_urgentes' => $maintenancesUrgentes,
            'maintenances_importantes' => $maintenancesImportantes,
            'total_urgent' => $projetsUrgents + $maintenancesUrgentes,
            'total_important' => $projetsImportants + $maintenancesImportantes,
        ];
    }
}
