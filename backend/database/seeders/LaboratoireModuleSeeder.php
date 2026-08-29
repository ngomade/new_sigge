<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LaboratoireModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌐 Création des données pour le module Laboratoire...');

        // 1. Créer des laboratoires
        $this->createLaboratoires();

        // 2. Créer des rôles de laboratoire
        $this->createRolesLabo();

        // 3. Créer des membres (personnel et utilisateurs)
        $this->createMembres();

        // 4. Créer des affectations de membres aux laboratoires
        $this->createAffectationsMembres();

        // 5. Créer des utilisateurs externes
        $this->createUtilisateursExternes();

        // 6. Créer des projets
        $this->createProjets();

        // 7. Créer des participants aux projets
        $this->createParticipantsProjets();

        // 8. Créer des équipements
        $this->createEquipements();

        // 9. Créer des réservations d'équipements
        $this->createReservationsEquipements();

        // 10. Créer des entretiens d'équipements
        $this->createEntretiensEquipements();

        // 11. Créer des publications
        $this->createPublications();

        // 12. Créer des candidatures
        $this->createCandidatures();

        $this->command->info('✅ Module Laboratoire créé avec succès !');
        $this->command->info('🔑 Comptes de test créés :');
        $this->command->info('   - Admin Labo 1: admin@labo1.ufd-tsi.com / password');
        $this->command->info('   - Admin Labo 2: admin@labo2.ufd-tsi.com / password');
        $this->command->info('   - Chef Projet: chef@labo1.ufd-tsi.com / password');
        $this->command->info('   - Chercheur: chercheur@labo1.ufd-tsi.com / password');
    }

    private function createLaboratoires()
    {
        $this->command->info('   📋 Création des laboratoires...');

        $laboratoires = [
            [
                'code_lab' => 'LABO1',
                'nom_lab' => 'Laboratoire d\'Intelligence Artificielle et Systèmes Intelligents',
                'description_lab' => 'Laboratoire spécialisé dans l\'intelligence artificielle, le machine learning et les systèmes intelligents. Nos recherches portent sur l\'optimisation des algorithmes, la reconnaissance de formes et l\'automatisation des processus.',
                'admin_pers_labo' => 'PERS001',
                'date_creation' => '2020-01-15',
                'statut' => 'actif',
                'localisation' => 'Bâtiment A, 2ème étage',
                'capacite' => 25,
                'budget_annuel' => 150000.00,
                'equipements_principaux' => 'Serveurs GPU, Stations de travail haute performance, Capteurs IoT',
                'partenaires' => 'Google Research, Microsoft, CNRS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_lab' => 'LABO2',
                'nom_lab' => 'Laboratoire de Cybersécurité et Sécurité des Réseaux',
                'description_lab' => 'Laboratoire dédié à la cybersécurité, la cryptographie et la sécurité des réseaux. Nous développons des solutions de protection contre les cyberattaques et formons les experts en sécurité informatique.',
                'admin_pers_labo' => 'PERS002',
                'date_creation' => '2021-03-20',
                'statut' => 'actif',
                'localisation' => 'Bâtiment B, 1er étage',
                'capacite' => 20,
                'budget_annuel' => 120000.00,
                'equipements_principaux' => 'Salle blanche, Analyseurs de trafic, Outils de pentesting',
                'partenaires' => 'ANSSI, Orange Cyberdefense, Thales',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_lab' => 'LABO3',
                'nom_lab' => 'Laboratoire de Développement Logiciel et Architecture Système',
                'description_lab' => 'Laboratoire spécialisé dans le développement logiciel, l\'architecture des systèmes et les méthodologies agiles. Nous développons des applications innovantes et formons les développeurs de demain.',
                'admin_pers_labo' => 'PERS003',
                'date_creation' => '2019-09-10',
                'statut' => 'actif',
                'localisation' => 'Bâtiment C, 3ème étage',
                'capacite' => 30,
                'budget_annuel' => 180000.00,
                'equipements_principaux' => 'Serveurs de développement, Outils DevOps, Environnements de test',
                'partenaires' => 'Atos, Capgemini, Sopra Steria',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('laboratoire')->insert($laboratoires);
    }

    private function createRolesLabo()
    {
        $this->command->info('   👥 Création des rôles de laboratoire...');

        $roles = [
            ['nom_role' => 'admin', 'description_role' => 'Gestion complète du laboratoire', 'permissions' => 'all'],
            ['nom_role' => 'chef_projet', 'description_role' => 'Gestion des projets et équipes', 'permissions' => 'projets,equipements,publications,membres,dashboard'],
            ['nom_role' => 'chercheur', 'description_role' => 'Participation aux recherches', 'permissions' => 'projets,publications,equipements,dashboard'],
            ['nom_role' => 'technicien', 'description_role' => 'Maintenance et support technique', 'permissions' => 'equipements,reservations,dashboard'],
            ['nom_role' => 'secretaire', 'description_role' => 'Gestion administrative', 'permissions' => 'membres,candidatures,documents,dashboard'],
            ['nom_role' => 'membre', 'description_role' => 'Utilisateur générique', 'permissions' => 'projets,equipements,publications,dashboard'],
        ];

        DB::table('role_labo')->truncate();
        DB::table('role_labo')->insert($roles);
    }

    private function createMembres()
    {
        $this->command->info('   👤 Création des membres...');

        // Créer des membres dans pers_lab
        $membres = [
            ['id_pers_lab' => 'PERS001', 'type_pers_lab' => 'personnel', 'date_entree' => '2020-01-15', 'statut' => 'actif'],
            ['id_pers_lab' => 'PERS002', 'type_pers_lab' => 'personnel', 'date_entree' => '2021-03-20', 'statut' => 'actif'],
            ['id_pers_lab' => 'PERS003', 'type_pers_lab' => 'personnel', 'date_entree' => '2019-09-10', 'statut' => 'actif'],
            ['id_pers_lab' => 'USER001', 'type_pers_lab' => 'users', 'date_entree' => '2022-01-10', 'statut' => 'actif'],
            ['id_pers_lab' => 'USER002', 'type_pers_lab' => 'users', 'date_entree' => '2022-02-15', 'statut' => 'actif'],
            ['id_pers_lab' => 'USER003', 'type_pers_lab' => 'users', 'date_entree' => '2022-03-20', 'statut' => 'actif'],
            ['id_pers_lab' => 'USER004', 'type_pers_lab' => 'users', 'date_entree' => '2022-04-25', 'statut' => 'actif'],
            ['id_pers_lab' => 'USER005', 'type_pers_lab' => 'users', 'date_entree' => '2022-05-30', 'statut' => 'actif'],
        ];

        DB::table('pers_lab')->insert($membres);

        // Créer des utilisateurs dans la table users
        $users = [
            [
                'code_user' => 'USER001',
                'nom_user' => 'Dupont',
                'prenom_user' => 'Marie',
                'email' => 'marie.dupont@ufd-tsi.com',
                'telephone' => '+225 0123456789',
                'password' => Hash::make('password'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_user' => 'USER002',
                'nom_user' => 'Martin',
                'prenom_user' => 'Pierre',
                'email' => 'pierre.martin@ufd-tsi.com',
                'telephone' => '+225 0123456790',
                'password' => Hash::make('password'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_user' => 'USER003',
                'nom_user' => 'Bernard',
                'prenom_user' => 'Sophie',
                'email' => 'sophie.bernard@ufd-tsi.com',
                'telephone' => '+225 0123456791',
                'password' => Hash::make('password'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_user' => 'USER004',
                'nom_user' => 'Petit',
                'prenom_user' => 'Lucas',
                'email' => 'lucas.petit@ufd-tsi.com',
                'telephone' => '+225 0123456792',
                'password' => Hash::make('password'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_user' => 'USER005',
                'nom_user' => 'Robert',
                'prenom_user' => 'Emma',
                'email' => 'emma.robert@ufd-tsi.com',
                'telephone' => '+225 0123456793',
                'password' => Hash::make('password'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        // Créer des comptes admin pour les laboratoires
        $adminUsers = [
            [
                'code_user' => 'ADMIN001',
                'nom_user' => 'Admin',
                'prenom_user' => 'Labo1',
                'email' => 'admin@labo1.ufd-tsi.com',
                'telephone' => '+225 0123456794',
                'password' => Hash::make('password'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_user' => 'ADMIN002',
                'nom_user' => 'Admin',
                'prenom_user' => 'Labo2',
                'email' => 'admin@labo2.ufd-tsi.com',
                'telephone' => '+225 0123456795',
                'password' => Hash::make('password'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_user' => 'ADMIN003',
                'nom_user' => 'Admin',
                'prenom_user' => 'Labo3',
                'email' => 'admin@labo3.ufd-tsi.com',
                'telephone' => '+225 0123456796',
                'password' => Hash::make('password'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($adminUsers);
    }

    private function createAffectationsMembres()
    {
        $this->command->info('   🔗 Création des affectations de membres...');

        $affectations = [
            // LABO1 - Intelligence Artificielle
            ['code_lab' => 'LABO1', 'id_pers_lab' => 'PERS001', 'id_rl' => 1, 'date_affectation' => '2020-01-15', 'statut' => 'actif'],
            ['code_lab' => 'LABO1', 'id_pers_lab' => 'USER001', 'id_rl' => 2, 'date_affectation' => '2022-01-10', 'statut' => 'actif'],
            ['code_lab' => 'LABO1', 'id_pers_lab' => 'USER002', 'id_rl' => 3, 'date_affectation' => '2022-02-15', 'statut' => 'actif'],
            ['code_lab' => 'LABO1', 'id_pers_lab' => 'USER003', 'id_rl' => 4, 'date_affectation' => '2022-03-20', 'statut' => 'actif'],

            // LABO2 - Cybersécurité
            ['code_lab' => 'LABO2', 'id_pers_lab' => 'PERS002', 'id_rl' => 1, 'date_affectation' => '2021-03-20', 'statut' => 'actif'],
            ['code_lab' => 'LABO2', 'id_pers_lab' => 'USER004', 'id_rl' => 2, 'date_affectation' => '2022-04-25', 'statut' => 'actif'],
            ['code_lab' => 'LABO2', 'id_pers_lab' => 'USER005', 'id_rl' => 5, 'date_affectation' => '2022-05-30', 'statut' => 'actif'],

            // LABO3 - Développement Logiciel
            ['code_lab' => 'LABO3', 'id_pers_lab' => 'PERS003', 'id_rl' => 1, 'date_affectation' => '2019-09-10', 'statut' => 'actif'],
        ];

        DB::table('laboratoire_pers_lab')->insert($affectations);
    }

    private function createUtilisateursExternes()
    {
        $this->command->info('   🌍 Création des utilisateurs externes...');

        $externes = [
            [
                'id_user_ext' => 'EXT001',
                'nom_user_ext' => 'Koné',
                'prenom_user_ext' => 'Aminata',
                'email_user_ext' => 'aminata.kone@entreprise1.ci',
                'tel_user_ext' => '+225 0123456797',
                'institution' => 'Entreprise Tech Solutions',
                'fonction' => 'Data Scientist',
                'code_lab' => 'LABO1',
                'statut' => 'actif',
                'date_inscription' => '2023-01-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user_ext' => 'EXT002',
                'nom_user_ext' => 'Traoré',
                'prenom_user_ext' => 'Moussa',
                'email_user_ext' => 'moussa.traore@entreprise2.ci',
                'tel_user_ext' => '+225 0123456798',
                'institution' => 'CyberSec Pro',
                'fonction' => 'Expert en Cybersécurité',
                'code_lab' => 'LABO2',
                'statut' => 'actif',
                'date_inscription' => '2023-02-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user_ext' => 'EXT003',
                'nom_user_ext' => 'Ouattara',
                'prenom_user_ext' => 'Fatou',
                'email_user_ext' => 'fatou.ouattara@universite.ci',
                'tel_user_ext' => '+225 0123456799',
                'institution' => 'Université Félix Houphouët-Boigny',
                'fonction' => 'Chercheuse',
                'code_lab' => 'LABO1',
                'statut' => 'actif',
                'date_inscription' => '2023-03-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user_ext' => 'EXT004',
                'nom_user_ext' => 'Bamba',
                'prenom_user_ext' => 'Kouassi',
                'email_user_ext' => 'kouassi.bamba@startup.ci',
                'tel_user_ext' => '+225 0123456800',
                'institution' => 'Startup Innovation Lab',
                'fonction' => 'Développeur Full-Stack',
                'code_lab' => 'LABO3',
                'statut' => 'actif',
                'date_inscription' => '2023-04-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('user_externe')->insert($externes);
    }

    private function createProjets()
    {
        $this->command->info('   📊 Création des projets...');

        $projets = [
            [
                'code_projet' => 'PROJ001',
                'theme_projet' => 'Développement d\'un système de reconnaissance faciale intelligent',
                'description_projet' => 'Ce projet vise à développer un système de reconnaissance faciale utilisant des algorithmes de deep learning pour améliorer la sécurité et l\'identification des personnes.',
                'code_lab' => 'LABO1',
                'statut_projet' => 'en_cours',
                'debut_projet' => '2023-01-15',
                'fin_projet' => '2024-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_projet' => 'PROJ002',
                'theme_projet' => 'Analyse prédictive des cyberattaques',
                'description_projet' => 'Développement d\'un système d\'analyse prédictive pour détecter et prévenir les cyberattaques en temps réel.',
                'code_lab' => 'LABO2',
                'statut_projet' => 'en_cours',
                'debut_projet' => '2023-03-01',
                'fin_projet' => '2024-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_projet' => 'PROJ003',
                'theme_projet' => 'Plateforme de développement collaboratif',
                'description_projet' => 'Création d\'une plateforme web pour le développement collaboratif de projets logiciels avec intégration continue.',
                'code_lab' => 'LABO3',
                'statut_projet' => 'en_cours',
                'debut_projet' => '2023-02-15',
                'fin_projet' => '2024-08-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_projet' => 'PROJ004',
                'theme_projet' => 'Optimisation des algorithmes de machine learning',
                'description_projet' => 'Recherche sur l\'optimisation des algorithmes de machine learning pour améliorer les performances et réduire la consommation énergétique.',
                'code_lab' => 'LABO1',
                'statut_projet' => 'en_attente',
                'debut_projet' => '2024-01-01',
                'fin_projet' => '2025-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_projet' => 'PROJ005',
                'theme_projet' => 'Système de gestion des équipements IoT',
                'description_projet' => 'Développement d\'un système de gestion et de monitoring des équipements IoT pour les laboratoires.',
                'code_lab' => 'LABO3',
                'statut_projet' => 'termine',
                'debut_projet' => '2022-09-01',
                'fin_projet' => '2023-08-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('projet_labo')->insert($projets);
    }

    private function createParticipantsProjets()
    {
        $this->command->info('   👥 Création des participants aux projets...');

        $participants = [
            // Projet 1 - Reconnaissance faciale
            ['code_projet' => 'PROJ001', 'id_pers_lab' => 'USER001', 'role' => 'Chef de projet', 'debut_participation' => '2023-01-15', 'fin_participation' => null],
            ['code_projet' => 'PROJ001', 'id_pers_lab' => 'USER002', 'role' => 'Chercheur principal', 'debut_participation' => '2023-01-15', 'fin_participation' => null],
            ['code_projet' => 'PROJ001', 'id_user_ext' => 'EXT001', 'role' => 'Expert en IA', 'debut_participation' => '2023-02-01', 'fin_participation' => null],
            ['code_projet' => 'PROJ001', 'id_user_ext' => 'EXT003', 'role' => 'Chercheuse associée', 'debut_participation' => '2023-03-15', 'fin_participation' => null],

            // Projet 2 - Cybersécurité
            ['code_projet' => 'PROJ002', 'id_pers_lab' => 'USER004', 'role' => 'Chef de projet', 'debut_participation' => '2023-03-01', 'fin_participation' => null],
            ['code_projet' => 'PROJ002', 'id_pers_lab' => 'USER005', 'role' => 'Expert technique', 'debut_participation' => '2023-03-01', 'fin_participation' => null],
            ['code_projet' => 'PROJ002', 'id_user_ext' => 'EXT002', 'role' => 'Consultant cybersécurité', 'debut_participation' => '2023-04-01', 'fin_participation' => null],

            // Projet 3 - Plateforme collaborative
            ['code_projet' => 'PROJ003', 'id_pers_lab' => 'USER003', 'role' => 'Développeur principal', 'debut_participation' => '2023-02-15', 'fin_participation' => null],
            ['code_projet' => 'PROJ003', 'id_user_ext' => 'EXT004', 'role' => 'Développeur Full-Stack', 'debut_participation' => '2023-05-01', 'fin_participation' => null],

            // Projet 4 - Optimisation ML
            ['code_projet' => 'PROJ004', 'id_pers_lab' => 'USER002', 'role' => 'Chercheur principal', 'debut_participation' => '2024-01-01', 'fin_participation' => null],

            // Projet 5 - IoT (terminé)
            ['code_projet' => 'PROJ005', 'id_pers_lab' => 'USER001', 'role' => 'Chef de projet', 'debut_participation' => '2022-09-01', 'fin_participation' => '2023-08-31'],
            ['code_projet' => 'PROJ005', 'id_pers_lab' => 'USER003', 'role' => 'Développeur', 'debut_participation' => '2022-09-01', 'fin_participation' => '2023-08-31'],
        ];

        DB::table('participer_projet')->insert($participants);
    }

    private function createEquipements()
    {
        $this->command->info('   🔧 Création des équipements...');

        $equipements = [
            // LABO1 - IA
            [
                'code_equip' => 'EQ001',
                'nom_equip' => 'Serveur GPU NVIDIA DGX A100',
                'ref_equip' => 'DGX-A100-8G',
                'desc_equip' => 'Serveur de calcul haute performance avec 8 GPU NVIDIA A100 pour le deep learning',
                'code_lab' => 'LABO1',
                'etat' => 'disponible',
                'date_achat' => '2023-01-15',
                'valeur' => 250000.00,
                'localisation' => 'Salle serveurs A201',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_equip' => 'EQ002',
                'nom_equip' => 'Station de travail HP Z8 G4',
                'ref_equip' => 'HP-Z8-G4',
                'desc_equip' => 'Station de travail professionnelle pour développement et tests',
                'code_lab' => 'LABO1',
                'etat' => 'en utilisation',
                'date_achat' => '2023-02-20',
                'valeur' => 15000.00,
                'localisation' => 'Bureau 205',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_equip' => 'EQ003',
                'nom_equip' => 'Caméra HD Logitech Brio',
                'ref_equip' => 'LOG-BRIO-4K',
                'desc_equip' => 'Caméra 4K pour tests de reconnaissance faciale',
                'code_lab' => 'LABO1',
                'etat' => 'disponible',
                'date_achat' => '2023-03-10',
                'valeur' => 300.00,
                'localisation' => 'Salle de test A203',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // LABO2 - Cybersécurité
            [
                'code_equip' => 'EQ004',
                'nom_equip' => 'Analyseur de trafic réseau',
                'ref_equip' => 'NET-ANALYZER-1',
                'desc_equip' => 'Outil d\'analyse du trafic réseau pour détection d\'intrusions',
                'code_lab' => 'LABO2',
                'etat' => 'en utilisation',
                'date_achat' => '2023-03-01',
                'valeur' => 8000.00,
                'localisation' => 'Salle réseau B101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_equip' => 'EQ005',
                'nom_equip' => 'Serveur de test isolé',
                'ref_equip' => 'TEST-SERVER-1',
                'desc_equip' => 'Serveur isolé pour tests de vulnérabilités',
                'code_lab' => 'LABO2',
                'etat' => 'disponible',
                'date_achat' => '2023-04-15',
                'valeur' => 12000.00,
                'localisation' => 'Salle isolée B102',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // LABO3 - Développement
            [
                'code_equip' => 'EQ006',
                'nom_equip' => 'Serveur de développement',
                'ref_equip' => 'DEV-SERVER-1',
                'desc_equip' => 'Serveur pour développement et tests d\'applications',
                'code_lab' => 'LABO3',
                'etat' => 'en utilisation',
                'date_achat' => '2023-02-15',
                'valeur' => 18000.00,
                'localisation' => 'Salle serveurs C301',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_equip' => 'EQ007',
                'nom_equip' => 'Station de développement MacBook Pro',
                'ref_equip' => 'MAC-PRO-2023',
                'desc_equip' => 'Station de développement pour applications iOS/macOS',
                'code_lab' => 'LABO3',
                'etat' => 'disponible',
                'date_achat' => '2023-05-20',
                'valeur' => 2500.00,
                'localisation' => 'Bureau 305',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('equipements')->insert($equipements);
    }

    private function createReservationsEquipements()
    {
        $this->command->info('   📅 Création des réservations d\'équipements...');

        $reservations = [
            [
                'code_equip' => 'EQ002',
                'id_pers_lab' => 2, // USER001
                'date_debut' => '2024-01-15 09:00:00',
                'date_fin' => '2024-01-15 17:00:00',
                'motif' => 'Développement d\'algorithmes de reconnaissance faciale',
                'statut' => 'confirmee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_equip' => 'EQ004',
                'id_pers_lab' => 6, // USER004
                'date_debut' => '2024-01-16 10:00:00',
                'date_fin' => '2024-01-16 16:00:00',
                'motif' => 'Tests de détection d\'intrusions',
                'statut' => 'confirmee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_equip' => 'EQ006',
                'id_pers_lab' => 4, // USER003
                'date_debut' => '2024-01-17 08:00:00',
                'date_fin' => '2024-01-17 18:00:00',
                'motif' => 'Développement de la plateforme collaborative',
                'statut' => 'confirmee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('reservation_agent')->insert($reservations);
    }

    private function createEntretiensEquipements()
    {
        $this->command->info('   🔧 Création des entretiens d\'équipements...');

        $entretiens = [
            [
                'code_equip' => 'EQ001',
                'id_pers_lab' => 7, // USER005
                'type_entretien' => 'maintenance_preventive',
                'description' => 'Maintenance préventive mensuelle - Nettoyage des ventilateurs et vérification des températures',
                'date_entretien' => '2024-01-10',
                'cout' => 500.00,
                'statut' => 'termine',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_equip' => 'EQ004',
                'id_pers_lab' => 7, // USER005
                'type_entretien' => 'reparation',
                'description' => 'Réparation du module d\'analyse de trafic - Remplacement de la carte réseau',
                'date_entretien' => '2024-01-12',
                'cout' => 1200.00,
                'statut' => 'en_cours',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('entretien_reparation')->insert($entretiens);
    }

    private function createPublications()
    {
        $this->command->info('   📚 Création des publications...');

        $publications = [
            [
                'code_pub' => 'PUB001',
                'titre_pub' => 'Optimisation des algorithmes de reconnaissance faciale pour la sécurité biométrique',
                'auteurs' => 'Marie Dupont, Pierre Martin, Aminata Koné',
                'journal' => 'Journal of Computer Vision and Pattern Recognition',
                'annee_publication' => 2023,
                'doi' => '10.1000/abc123',
                'code_lab' => 'LABO1',
                'statut' => 'publiee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_pub' => 'PUB002',
                'titre_pub' => 'Nouvelle approche pour la détection prédictive des cyberattaques',
                'auteurs' => 'Lucas Petit, Moussa Traoré',
                'journal' => 'International Journal of Cybersecurity',
                'annee_publication' => 2023,
                'doi' => '10.1000/def456',
                'code_lab' => 'LABO2',
                'statut' => 'soumis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_pub' => 'PUB003',
                'titre_pub' => 'Architecture d\'une plateforme de développement collaboratif',
                'auteurs' => 'Sophie Bernard, Kouassi Bamba',
                'journal' => 'Software Engineering Journal',
                'annee_publication' => 2023,
                'doi' => '10.1000/ghi789',
                'code_lab' => 'LABO3',
                'statut' => 'publiee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('publication')->insert($publications);
    }

    private function createCandidatures()
    {
        $this->command->info('   📝 Création des candidatures...');

        $candidatures = [
            [
                'id_user_ext' => 'CAND001',
                'nom_user_ext' => 'Diabaté',
                'prenom_user_ext' => 'Aïcha',
                'email_user_ext' => 'aicha.diabate@candidat.ci',
                'tel_user_ext' => '+225 0123456801',
                'institution' => 'Université de Cocody',
                'fonction' => 'Étudiante en Master',
                'motivation' => 'Passionnée par l\'intelligence artificielle, je souhaite contribuer aux projets de recherche du laboratoire.',
                'competences' => 'Python, TensorFlow, Machine Learning',
                'code_lab' => 'LABO1',
                'statut' => 'en_attente',
                'date_inscription' => '2024-01-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user_ext' => 'CAND002',
                'nom_user_ext' => 'Yao',
                'prenom_user_ext' => 'Kouamé',
                'email_user_ext' => 'kouame.yao@candidat.ci',
                'tel_user_ext' => '+225 0123456802',
                'institution' => 'École Supérieure d\'Informatique',
                'fonction' => 'Développeur junior',
                'motivation' => 'Je souhaite approfondir mes connaissances en cybersécurité et contribuer à des projets innovants.',
                'competences' => 'Java, Sécurité réseau, Cryptographie',
                'code_lab' => 'LABO2',
                'statut' => 'en_attente',
                'date_inscription' => '2024-01-08',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user_ext' => 'CAND003',
                'nom_user_ext' => 'Kouassi',
                'prenom_user_ext' => 'N\'Guessan',
                'email_user_ext' => 'nguessan.kouassi@candidat.ci',
                'tel_user_ext' => '+225 0123456803',
                'institution' => 'Institut de Formation en Informatique',
                'fonction' => 'Stagiaire développeur',
                'motivation' => 'Passionné par le développement web moderne, je souhaite rejoindre l\'équipe de développement.',
                'competences' => 'JavaScript, React, Node.js, DevOps',
                'code_lab' => 'LABO3',
                'statut' => 'en_attente',
                'date_inscription' => '2024-01-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('user_externe')->insert($candidatures);
    }
}
