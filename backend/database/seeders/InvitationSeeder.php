<?php

namespace Database\Seeders;

use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\LaboratoireInvitation;
use App\Models\laboratoires\RoleLabo;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un laboratoire existant
        $laboratoire = Laboratoire::first();
        if (!$laboratoire) {
            $this->command->error('Aucun laboratoire trouvé. Veuillez d\'abord créer un laboratoire.');
            return;
        }

        // Récupérer un rôle existant
        $role = RoleLabo::first();
        if (!$role) {
            $this->command->error('Aucun rôle trouvé. Veuillez d\'abord créer des rôles.');
            return;
        }

        // Créer des invitations d'exemple
        $invitations = [
            [
                'code_lab' => $laboratoire->code_lab,
                'id_rl' => $role->id_rl,
                'date_fin_affectation' => Carbon::now()->addYear(),
                'date_expiration' => Carbon::now()->addDays(7),
                'statut' => 'actif',
                'created_by' => 'PERS0001' // Assurez-vous que cet ID existe dans pers_lab
            ],
            [
                'code_lab' => $laboratoire->code_lab,
                'id_rl' => null, // Pas de rôle spécifique
                'date_fin_affectation' => Carbon::now()->addMonths(6),
                'date_expiration' => Carbon::now()->addDays(14),
                'statut' => 'actif',
                'created_by' => 'PERS0001'
            ],
            [
                'code_lab' => $laboratoire->code_lab,
                'id_rl' => $role->id_rl,
                'date_fin_affectation' => Carbon::now()->addYear(),
                'date_expiration' => Carbon::now()->subDays(1), // Expirée
                'statut' => 'actif',
                'created_by' => 'PERS0001'
            ]
        ];

        foreach ($invitations as $invitationData) {
            LaboratoireInvitation::create($invitationData);
        }

        $this->command->info('Invitations d\'exemple créées avec succès !');
        $this->command->info('Vous pouvez maintenant tester le système d\'invitations.');
    }
}
