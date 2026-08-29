<?php

namespace Database\Seeders;

use App\Models\laboratoires\EntretienReparation;
use App\Models\laboratoires\Equipements;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\PersLab;
use App\Models\laboratoires\ProjetLabo;
use App\Models\laboratoires\Publication;
use App\Models\laboratoires\ReservationEquipement;
use App\Models\laboratoires\RoleLabo;
use App\Models\laboratoires\UserExterne;
use App\Models\Personnel;
use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LaboratoireDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer un laboratoire principal
        $laboratoire = Laboratoire::create([
            'code_lab' => 'LABO_IA_2024',
            'nom_lab' => 'Laboratoire d\'Intelligence Artificielle et Systèmes Intelligents',
            'description_lab' => 'Laboratoire spécialisé dans la recherche en intelligence artificielle, machine learning et systèmes intelligents pour l\'industrie 4.0',
            'date_creation' => '2020-03-15',
            'statut_lab' => 'actif',
            'capacite_lab' => 50,
            'budget_annuel' => 250000,
            'directeur_lab' => 'Dr. Marie Dubois',
            'email_lab' => 'contact@labo-ia.estlc.cm',
            'tel_lab' => '+237 233 456 789',
            'adresse_lab' => 'Campus ESTLC, Bâtiment A, 2ème étage',
            'site_web' => 'https://labo-ia.estlc.cm',
            'logo_path' => 'logos/labo-ia.png',
        ]);

        // 2. Créer des rôles de laboratoire
        $roles = [
            ['lib_rl' => 'Directeur', 'description_rl' => 'Direction du laboratoire'],
            ['lib_rl' => 'Chercheur Principal', 'description_rl' => 'Responsable de projets de recherche'],
            ['lib_rl' => 'Chercheur', 'description_rl' => 'Membre actif du laboratoire'],
            ['lib_rl' => 'Doctorant', 'description_rl' => 'Étudiant en doctorat'],
            ['lib_rl' => 'Masterant', 'description_rl' => 'Étudiant en master'],
            ['lib_rl' => 'Stagiaire', 'description_rl' => 'Stagiaire de recherche'],
            ['lib_rl' => 'Technicien', 'description_rl' => 'Support technique'],
        ];

        foreach ($roles as $role) {
            RoleLabo::create($role);
        }

        // 3. Créer des personnels et utilisateurs
        $personnels = [
            [
                'code_pers' => 'PERS001',
                'nom_pers' => 'Dubois',
                'prenom_pers' => 'Marie',
                'email_pers' => 'marie.dubois@estlc.cm',
                'tel_pers' => '+237 233 111 111',
                'login_pers' => 'mdubois',
                'pwd_pers' => Hash::make('password123'),
                'statut_pers' => 'actif',
            ],
            [
                'code_pers' => 'PERS002',
                'nom_pers' => 'Martin',
                'prenom_pers' => 'Jean',
                'email_pers' => 'jean.martin@estlc.cm',
                'tel_pers' => '+237 233 222 222',
                'login_pers' => 'jmartin',
                'pwd_pers' => Hash::make('password123'),
                'statut_pers' => 'actif',
            ],
            [
                'code_pers' => 'PERS003',
                'nom_pers' => 'Bernard',
                'prenom_pers' => 'Sophie',
                'email_pers' => 'sophie.bernard@estlc.cm',
                'tel_pers' => '+237 233 333 333',
                'login_pers' => 'sbernard',
                'pwd_pers' => Hash::make('password123'),
                'statut_pers' => 'actif',
            ],
        ];

        foreach ($personnels as $pers) {
            Personnel::create($pers);
        }

        // 4. Créer des étudiants
        $etudiants = [
            [
                'code_user' => 'ETU001',
                'nom_user' => 'Nguyen',
                'prenom_user' => 'Thi',
                'email_user' => 'thi.nguyen@estlc.cm',
                'tel_user' => '+237 233 444 444',
                'login_user' => 'tnguyen',
                'pwd_user' => Hash::make('password123'),
                'statut_user' => 'actif',
            ],
            [
                'code_user' => 'ETU002',
                'nom_user' => 'Garcia',
                'prenom_user' => 'Carlos',
                'email_user' => 'carlos.garcia@estlc.cm',
                'tel_user' => '+237 233 555 555',
                'login_user' => 'cgarcia',
                'pwd_user' => Hash::make('password123'),
                'statut_user' => 'actif',
            ],
        ];

        foreach ($etudiants as $etu) {
            Users::create($etu);
        }

        // 5. Créer des utilisateurs externes
        $externes = [
            [
                'code_lab' => 'LABO_IA_2024',
                'nom_user_ext' => 'Smith',
                'prenom_user_ext' => 'John',
                'email_user_ext' => 'john.smith@entreprise.com',
                'tel_user_ext' => '+237 233 666 666',
                'statut' => 'actif',
                'pwd' => Hash::make('password123'),
                'date_debut' => '2024-01-15',
                'motivation_path' => 'motivations/smith_motivation.pdf',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'nom_user_ext' => 'Johnson',
                'prenom_user_ext' => 'Sarah',
                'email_user_ext' => 'sarah.johnson@startup.com',
                'tel_user_ext' => '+237 233 777 777',
                'statut' => 'actif',
                'pwd' => Hash::make('password123'),
                'date_debut' => '2024-02-01',
                'motivation_path' => 'motivations/johnson_motivation.pdf',
            ],
        ];

        foreach ($externes as $ext) {
            UserExterne::create($ext);
        }

        // 6. Créer des PersLab (liaison personnel/étudiants avec laboratoire)
        $persLabs = [
            ['id_pers_lab' => 'PERS001', 'type_pers_lab' => 'personnel'],
            ['id_pers_lab' => 'PERS002', 'type_pers_lab' => 'personnel'],
            ['id_pers_lab' => 'PERS003', 'type_pers_lab' => 'personnel'],
            ['id_pers_lab' => 'ETU001', 'type_pers_lab' => 'etudiant'],
            ['id_pers_lab' => 'ETU002', 'type_pers_lab' => 'etudiant'],
        ];

        foreach ($persLabs as $persLab) {
            PersLab::create($persLab);
        }

        // 7. Créer des affectations dans le laboratoire
        $affectations = [
            ['code_lab' => 'LABO_IA_2024', 'id_pers_lab' => 'PERS001', 'id_rl' => 1, 'date_affectation' => '2020-03-15', 'statut' => 'actif'],
            ['code_lab' => 'LABO_IA_2024', 'id_pers_lab' => 'PERS002', 'id_rl' => 2, 'date_affectation' => '2020-04-01', 'statut' => 'actif'],
            ['code_lab' => 'LABO_IA_2024', 'id_pers_lab' => 'PERS003', 'id_rl' => 3, 'date_affectation' => '2020-05-01', 'statut' => 'actif'],
            ['code_lab' => 'LABO_IA_2024', 'id_pers_lab' => 'ETU001', 'id_rl' => 4, 'date_affectation' => '2023-09-01', 'statut' => 'actif'],
            ['code_lab' => 'LABO_IA_2024', 'id_pers_lab' => 'ETU002', 'id_rl' => 5, 'date_affectation' => '2023-09-01', 'statut' => 'actif'],
            ['code_lab' => 'LABO_IA_2024', 'id_user_externe' => 1, 'id_rl' => 3, 'date_affectation' => '2024-01-15', 'statut' => 'actif'],
            ['code_lab' => 'LABO_IA_2024', 'id_user_externe' => 2, 'id_rl' => 3, 'date_affectation' => '2024-02-01', 'statut' => 'actif'],
        ];

        foreach ($affectations as $aff) {
            LaboratoirePersLab::create($aff);
        }

        // 8. Créer des équipements
        $equipements = [
            [
                'code_lab' => 'LABO_IA_2024',
                'nom_equip' => 'Station de travail NVIDIA DGX A100',
                'description_equip' => 'Station de travail haute performance pour l\'entraînement de modèles IA',
                'categorie_equip' => 'Informatique',
                'marque_equip' => 'NVIDIA',
                'modele_equip' => 'DGX A100',
                'numero_serie' => 'NV-DGX-2024-001',
                'date_acquisition' => '2023-06-15',
                'cout_acquisition' => 150000,
                'etat_equip' => 'Fonctionnel',
                'statut_equip' => 'Disponible',
                'localisation_equip' => 'Salle serveurs A201',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'nom_equip' => 'Serveur de calcul HPC',
                'description_equip' => 'Serveur haute performance pour calculs parallèles',
                'categorie_equip' => 'Informatique',
                'marque_equip' => 'Dell',
                'modele_equip' => 'PowerEdge R750',
                'numero_serie' => 'DELL-PE-2024-002',
                'date_acquisition' => '2023-08-20',
                'cout_acquisition' => 25000,
                'etat_equip' => 'Fonctionnel',
                'statut_equip' => 'Disponible',
                'localisation_equip' => 'Salle serveurs A201',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'nom_equip' => 'Robot collaboratif UR5',
                'description_equip' => 'Robot industriel pour expérimentations IA',
                'categorie_equip' => 'Robotique',
                'marque_equip' => 'Universal Robots',
                'modele_equip' => 'UR5',
                'numero_serie' => 'UR-ROB-2024-003',
                'date_acquisition' => '2023-10-10',
                'cout_acquisition' => 35000,
                'etat_equip' => 'Fonctionnel',
                'statut_equip' => 'Disponible',
                'localisation_equip' => 'Salle robotique A205',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'nom_equip' => 'Caméra 3D Intel RealSense',
                'description_equip' => 'Caméra de vision 3D pour perception',
                'categorie_equip' => 'Vision',
                'marque_equip' => 'Intel',
                'modele_equip' => 'RealSense D435i',
                'numero_serie' => 'INTEL-CAM-2024-004',
                'date_acquisition' => '2023-12-05',
                'cout_acquisition' => 500,
                'etat_equip' => 'Fonctionnel',
                'statut_equip' => 'Disponible',
                'localisation_equip' => 'Salle vision A203',
            ],
        ];

        foreach ($equipements as $equip) {
            Equipements::create($equip);
        }

        // 9. Créer des projets de recherche
        $projets = [
            [
                'code_lab' => 'LABO_IA_2024',
                'titre_projet' => 'IA pour la détection précoce du cancer',
                'description_projet' => 'Développement d\'algorithmes d\'IA pour la détection précoce du cancer du sein à partir d\'images mammographiques',
                'objectifs_projet' => 'Améliorer la précision de détection de 15% par rapport aux méthodes traditionnelles',
                'methodologie_projet' => 'Deep Learning avec réseaux de neurones convolutifs',
                'date_debut_projet' => '2024-01-01',
                'date_fin_projet' => '2026-12-31',
                'budget_projet' => 500000,
                'statut_projet' => 'En cours',
                'chef_projet' => 'Dr. Marie Dubois',
                'partenaires_projet' => 'Hôpital Central, Université de Yaoundé',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'titre_projet' => 'Système de reconnaissance faciale pour la sécurité',
                'description_projet' => 'Développement d\'un système de reconnaissance faciale robuste pour applications de sécurité',
                'objectifs_projet' => 'Créer un système avec 99% de précision et moins de 1% de faux positifs',
                'methodologie_projet' => 'Computer Vision, Deep Learning, Transfer Learning',
                'date_debut_projet' => '2024-03-01',
                'date_fin_projet' => '2025-08-31',
                'budget_projet' => 200000,
                'statut_projet' => 'En cours',
                'chef_projet' => 'Dr. Jean Martin',
                'partenaires_projet' => 'Ministère de la Défense, Police Nationale',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'titre_projet' => 'Optimisation énergétique par IA',
                'description_projet' => 'Application de l\'IA pour optimiser la consommation énergétique des bâtiments',
                'objectifs_projet' => 'Réduire la consommation énergétique de 20%',
                'methodologie_projet' => 'Reinforcement Learning, IoT, Big Data',
                'date_debut_projet' => '2024-06-01',
                'date_fin_projet' => '2025-11-30',
                'budget_projet' => 150000,
                'statut_projet' => 'En cours',
                'chef_projet' => 'Dr. Sophie Bernard',
                'partenaires_projet' => 'ENEO, Ministère de l\'Énergie',
            ],
        ];

        foreach ($projets as $projet) {
            ProjetLabo::create($projet);
        }

        // 10. Créer des publications
        $publications = [
            [
                'code_lab' => 'LABO_IA_2024',
                'titre_pub' => 'Deep Learning for Medical Image Analysis: A Comprehensive Review',
                'auteurs_pub' => 'Dubois, M., Martin, J., Nguyen, T.',
                'journal_pub' => 'IEEE Transactions on Medical Imaging',
                'annee_pub' => 2024,
                'doi_pub' => '10.1109/TMI.2024.001234',
                'type_pub' => 'Article scientifique',
                'statut_pub' => 'Publié',
                'fichier_pub' => 'publications/deep_learning_medical_2024.pdf',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'titre_pub' => 'Robust Face Recognition System for Security Applications',
                'auteurs_pub' => 'Martin, J., Garcia, C., Smith, J.',
                'journal_pub' => 'Computer Vision and Image Understanding',
                'annee_pub' => 2024,
                'doi_pub' => '10.1016/j.cviu.2024.123456',
                'type_pub' => 'Article scientifique',
                'statut_pub' => 'Soumis',
                'fichier_pub' => 'publications/face_recognition_2024.pdf',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'titre_pub' => 'Energy Optimization in Smart Buildings using Reinforcement Learning',
                'auteurs_pub' => 'Bernard, S., Johnson, S., Nguyen, T.',
                'journal_pub' => 'Applied Energy',
                'annee_pub' => 2024,
                'doi_pub' => '10.1016/j.apenergy.2024.789012',
                'type_pub' => 'Article scientifique',
                'statut_pub' => 'En révision',
                'fichier_pub' => 'publications/energy_optimization_2024.pdf',
            ],
        ];

        foreach ($publications as $pub) {
            Publication::create($pub);
        }

        // 11. Créer des entretiens et réparations
        $entretiens = [
            [
                'code_lab' => 'LABO_IA_2024',
                'id_equip' => 1,
                'type_entretien' => 'Maintenance préventive',
                'description_entretien' => 'Nettoyage et vérification des composants',
                'date_entretien' => '2024-06-15',
                'cout_entretien' => 500,
                'technicien_entretien' => 'Technicien ESTLC',
                'statut_entretien' => 'Terminé',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'id_equip' => 2,
                'type_entretien' => 'Mise à jour logicielle',
                'description_entretien' => 'Installation des dernières mises à jour système',
                'date_entretien' => '2024-07-01',
                'cout_entretien' => 200,
                'technicien_entretien' => 'Technicien ESTLC',
                'statut_entretien' => 'En cours',
            ],
        ];

        foreach ($entretiens as $entretien) {
            EntretienReparation::create($entretien);
        }

        // 12. Créer des réservations d'équipements
        $reservations = [
            [
                'code_lab' => 'LABO_IA_2024',
                'id_equip' => 1,
                'id_pers_lab' => 'PERS001',
                'date_debut_reservation' => '2024-07-20 09:00:00',
                'date_fin_reservation' => '2024-07-20 17:00:00',
                'motif_reservation' => 'Entraînement modèle IA pour projet cancer',
                'statut_reservation' => 'Confirmée',
            ],
            [
                'code_lab' => 'LABO_IA_2024',
                'id_equip' => 3,
                'id_pers_lab' => 'ETU001',
                'date_debut_reservation' => '2024-07-21 14:00:00',
                'date_fin_reservation' => '2024-07-21 18:00:00',
                'motif_reservation' => 'Tests de contrôle robotique',
                'statut_reservation' => 'En attente',
            ],
        ];

        foreach ($reservations as $reservation) {
            ReservationEquipement::create($reservation);
        }

        $this->command->info('✅ Données de démonstration du laboratoire créées avec succès !');
        $this->command->info('📊 Statistiques créées :');
        $this->command->info('   - 1 laboratoire');
        $this->command->info('   - 7 rôles');
        $this->command->info('   - 3 personnels');
        $this->command->info('   - 2 étudiants');
        $this->command->info('   - 2 utilisateurs externes');
        $this->command->info('   - 4 équipements');
        $this->command->info('   - 3 projets de recherche');
        $this->command->info('   - 3 publications');
        $this->command->info('   - 2 entretiens');
        $this->command->info('   - 2 réservations');
    }
}
