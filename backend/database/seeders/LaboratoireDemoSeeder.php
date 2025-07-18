<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LaboratoireDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialisation des tables principales du module labo
//        DB::table('laboratoire')->truncate();
        DB::table('pers_lab')->truncate();
        DB::table('laboratoire_pers_lab')->truncate();
        DB::table('projet_labo')->truncate();
        DB::table('equipements')->truncate();
        DB::table('publications')->truncate();
        DB::table('reservation_agent')->truncate();
        DB::table('entretien_reparation')->truncate();
        DB::table('participer_projet')->truncate();
        DB::table('labo_chats')->truncate();

        // 1. Laboratoires
        $labos = [
            [
                'code_lab' => 'LABESTLC',
                'label_labo' => 'Laboratoire ESTLC',
                'desc_labo' => 'Laboratoire d’Electronique, Systèmes et Technologies de la Communication.',
                'axes_recherche' => 'Télécommunications, Systèmes embarqués, IoT, Intelligence Artificielle',
                'email_labo' => 'contact@estlc.edu',
                'tel_labo' => '+221 33 123 4567',
                'adresse_labo' => 'Université ESTLC, Dakar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_lab' => 'LABINFO',
                'label_labo' => 'Laboratoire Informatique',
                'desc_labo' => 'Laboratoire de Recherche en Informatique et Systèmes Intelligents.',
                'axes_recherche' => 'Big Data, IA, Sécurité, Cloud, Réseaux',
                'email_labo' => 'info@labinfo.edu',
                'tel_labo' => '+221 33 987 6543',
                'adresse_labo' => 'Université ESTLC, Dakar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('laboratoire')->insert($labos);

        // 2. Membres (pers_lab)
        $membres = [];
        $noms = [
            ['nom' => 'Diop', 'prenom' => 'Moussa'],
            ['nom' => 'Sarr', 'prenom' => 'Fatou'],
            ['nom' => 'Ba', 'prenom' => 'Abdou'],
            ['nom' => 'Fall', 'prenom' => 'Aminata'],
            ['nom' => 'Ndiaye', 'prenom' => 'Cheikh'],
            ['nom' => 'Sy', 'prenom' => 'Mame'],
            ['nom' => 'Gueye', 'prenom' => 'Ousmane'],
            ['nom' => 'Faye', 'prenom' => 'Seynabou'],
            ['nom' => 'Diallo', 'prenom' => 'Mamadou'],
            ['nom' => 'Kane', 'prenom' => 'Awa'],
        ];
        foreach ($noms as $i => $np) {
            $membres[] = [
                'id_pers_lab' => 'M'.str_pad($i+1, 3, '0', STR_PAD_LEFT),
                'nom_complet' => $np['prenom'].' '.$np['nom'],
                'type_pers_lab' => 'personnel',
                'email' => strtolower($np['prenom']).'.'.strtolower($np['nom']).'@estlc.edu',
                'telephone' => '+221 77 000 00'.($i+1),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('pers_lab')->insert($membres);

        // 3. Affectations de membres à des rôles
        $roles = [
            ['id_rl' => 1, 'lib_rl' => 'Directeur'],
            ['id_rl' => 2, 'lib_rl' => 'Responsable Projet'],
            ['id_rl' => 3, 'lib_rl' => 'Technicien'],
            ['id_rl' => 4, 'lib_rl' => 'Chercheur'],
            ['id_rl' => 5, 'lib_rl' => 'Secrétaire'],
        ];
        DB::table('role_labo')->truncate();
        DB::table('role_labo')->insert($roles);

        $affectations = [];
        foreach ($membres as $i => $membre) {
            $affectations[] = [
                'code_lab' => $i < 5 ? 'LABESTLC' : 'LABINFO',
                'id_pers_lab' => $membre['id_pers_lab'],
                'id_rl' => ($i % 5) + 1,
                'date_affectation' => now()->subMonths(rand(1, 24)),
                'date_fin_affectation' => null,
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('laboratoire_pers_lab')->insert($affectations);

        // 4. Projets
        $projets = [];
        for ($i = 1; $i <= 5; $i++) {
            $projets[] = [
                'code_projet' => $i,
                'theme_projet' => 'Projet '.chr(64+$i),
                'description_projet' => 'Description du projet '.chr(64+$i).'.',
                'code_lab' => $i <= 3 ? 'LABESTLC' : 'LABINFO',
                'statut_projet' => $i % 2 == 0 ? 'en cours' : 'terminé',
                'debut_projet' => now()->subMonths(12 - $i*2),
                'fin_projet' => $i % 2 == 0 ? null : now()->subMonths(6 - $i),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('projet_labo')->insert($projets);

        // 5. Participations aux projets
        $participations = [];
        foreach ($projets as $projet) {
            $membresProjet = array_slice($membres, rand(0, 5), rand(2, 5));
            foreach ($membresProjet as $membre) {
                $participations[] = [
                    'code_projet' => $projet['code_projet'],
                    'id_pers_lab' => $membre['id_pers_lab'],
                    'role' => ['Chef de projet', 'Membre', 'Contributeur'][rand(0,2)],
                    'debut_participation' => $projet['debut_projet'],
                    'fin_participation' => $projet['fin_projet'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('participer_projet')->insert($participations);

        // 6. Équipements
        $equipements = [];
        for ($i = 1; $i <= 10; $i++) {
            $equipements[] = [
                'code_equip' => $i,
                'nom_equip' => 'Équipement '.$i,
                'ref_equip' => 'REF-'.str_pad($i,3,'0',STR_PAD_LEFT),
                'desc_equip' => 'Description de l’équipement '.$i,
                'image_path' => 'equipements/equip'.$i.'.jpg',
                'etat' => ['disponible','en maintenance','hors service','en utilisation'][rand(0,3)],
                'date_achat' => now()->subYears(rand(1,5)),
                'valeur' => rand(100000, 2000000),
                'localisation' => 'Salle '.chr(65+($i%3)),
                'code_lab' => $i <= 5 ? 'LABESTLC' : 'LABINFO',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('equipements')->insert($equipements);

        // 7. Réservations d’équipements
        $reservations = [];
        foreach ($equipements as $equip) {
            $nbRes = rand(1,3);
            for ($j = 0; $j < $nbRes; $j++) {
                $membre = $membres[array_rand($membres)];
                $debut = now()->subDays(rand(1, 60));
                $fin = (clone $debut)->addDays(rand(1, 10));
                $reservations[] = [
                    'code_equip' => $equip['code_equip'],
                    'id_pers_lab' => $membre['id_pers_lab'],
                    'debut_reserv' => $debut,
                    'fin_reserv' => $fin,
                    'statut' => ['confirmé','en attente','refusé'][rand(0,2)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('reservation_agent')->insert($reservations);

        // 8. Entretiens/réparations
        $entretiens = [];
        foreach ($equipements as $equip) {
            $nbEnt = rand(1,2);
            for ($j = 0; $j < $nbEnt; $j++) {
                $membre = $membres[array_rand($membres)];
                $debut = now()->subDays(rand(10, 200));
                $fin = (clone $debut)->addDays(rand(1, 7));
                $entretiens[] = [
                    'code_equip' => $equip['code_equip'],
                    'id_pers_lab' => $membre['id_pers_lab'],
                    'statut_entretien' => ['En cours','Terminé','En pause'][rand(0,2)],
                    'debut_entretien' => $debut,
                    'fin_entretien' => $fin,
                    'type_entretien' => ['Préventif','Curatif'][rand(0,1)],
                    'desc_entretien' => 'Entretien de l’équipement '.$equip['nom_equip'],
                    'cout' => rand(10000, 200000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('entretien_reparation')->insert($entretiens);

        // 9. Documents de projet
        $docs = [];
        foreach ($projets as $projet) {
            $nbDocs = rand(1,3);
            for ($j = 1; $j <= $nbDocs; $j++) {
                $docs[] = [
                    'code_projet' => $projet['code_projet'],
                    'titre_doc' => 'Document '.$j.' du projet '.$projet['theme_projet'],
                    'path' => 'docs/projet_'.$projet['code_projet'].'_doc'.$j.'.pdf',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('doc_projet_labo')->truncate();
        DB::table('doc_projet_labo')->insert($docs);

        // 10. Messages de chat
        $messages = [];
        $phrases = [
            "Bonjour à tous !",
            "Réunion prévue demain à 10h.",
            "Merci pour le partage du document.",
            "L’équipement 3 est en maintenance.",
            "Qui participe au projet B ?",
            "N’oubliez pas de valider vos réservations.",
            "La publication sur l’IA est en ligne !",
            "Besoin d’aide pour l’installation du matériel.",
            "Félicitations à l’équipe du projet D !",
            "Pause café à 16h ?"
        ];
        for ($i = 0; $i < 20; $i++) {
            $membre = $membres[array_rand($membres)];
            $messages[] = [
                'code_lab' => $i < 10 ? 'LABESTLC' : 'LABINFO',
                'id_expediteur' => $membre['id_pers_lab'],
                'type_expediteur' => 'personnel',
                'message' => $phrases[array_rand($phrases)],
                'created_at' => now()->subMinutes(rand(1, 10000)),
                'updated_at' => now(),
            ];
        }
        DB::table('labo_chats')->insert($messages);
    }
}
