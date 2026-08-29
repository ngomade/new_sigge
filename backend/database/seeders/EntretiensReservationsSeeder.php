<?php

namespace Database\Seeders;

use App\Models\laboratoires\EntretienReparation;
use App\Models\laboratoires\Equipements;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\ReservationAgent;
use Illuminate\Database\Seeder;

class EntretiensReservationsSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer quelques équipements
        $equipements = Equipements::take(5)->get();

        // Récupérer quelques membres de laboratoire
        $membres = LaboratoirePersLab::where('statut', 'actif')->take(3)->get();

        if ($equipements->count() > 0 && $membres->count() > 0) {
            // Créer quelques entretiens
            foreach ($equipements->take(3) as $equipement) {
                EntretienReparation::create([
                    'code_equip' => $equipement->code_equip,
                    'id_pers_lab' => $membres->random()->id_pers_lab,
                    'statut_entretien' => 'En cours',
                    'debut_entretien' => now()->subDays(2),
                    'fin_entretien' => now()->addDays(3),
                    'type_entretien' => 'entretien',
                    'desc_entretien' => 'Entretien préventif programmé',
                    'cout' => rand(50000, 200000),
                ]);
            }

            // Créer quelques réservations
            foreach ($equipements->take(2) as $equipement) {
                ReservationAgent::create([
                    'code_equip' => $equipement->code_equip,
                    'id_pers_lab' => $membres->random()->id_pers_lab,
                    'debut_reserv' => now()->addDays(1),
                    'fin_reserv' => now()->addDays(5),
                    'statut' => 'confirmé',
                ]);

                ReservationAgent::create([
                    'code_equip' => $equipement->code_equip,
                    'id_pers_lab' => $membres->random()->id_pers_lab,
                    'debut_reserv' => now()->addDays(10),
                    'fin_reserv' => now()->addDays(12),
                    'statut' => 'en attente',
                ]);
            }
        }
    }
}
