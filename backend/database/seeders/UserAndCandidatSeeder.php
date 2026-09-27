<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserAndCandidatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Création d'un utilisateur de test dans la table `users`
        $codeUser = 'ESTLC2026002';
        
        DB::table('users')->insert([
            'code_user' => $codeUser,
            'code_info_extra' => 1,
            'nom_user' => 'Kakabi',
            'prenom_user' => 'Christian',
            'sexe_user' => 'Masculin',
            'date_naissance_user' => '2000-01-01',
            'lieu_naissance_user' => 'Ambam',
            'statut_mat_user' => 'Célibataire',
            'first_phone_user' => '600000000',
            'numero_cni_user' => '1122334455',
            'email_user' => 'kakabichristian@gmail.com',
            'date_deliv_cni_user' => '2020-01-01',
            'login_user' => 'admin_test',
            // Mot de passe haché (le mot de passe en clair est "password123")
            'pwd_user' => Hash::make('password123'), 
            'statut_user' => 1,
            'ecole_user' => 'ESTLC',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Création d'un candidat associé dans la table `candidat`
        DB::table('candidat')->insert([
            'ca_code' => 'CA2026001',
            'filiere_code' => 'GLTCO',
            'code_site' => 1,
            'ca_nom' => 'Kakabi',
            'ca_prenom' => 'Christian',
            'ca_sexe' => 'Masculin',
            'ca_date_naiss' => '2000-01-01',
            'ca_lieu_naiss' => 'Ambam',
            'ca_statut_mat' => 'Célibataire',
            'ca_telephone' => '600000000',
            'ca_num_cni' => '1122334455',
            'ca_email' => 'kakabichristian@gmail.com',
            'ca_premiere_lang' => 'Français',
            'ca_nationalite' => 'Camerounaise',
            'ca_region_origine' => 'Sud',
            'ca_depart_origine' => 'Mvila',
            'ca_diplome_admission' => 'Baccalauréat',
            'ca_annee_diplome' => '2022',
            'ca_serie_diplome' => 'D',
            'ca_mention_diplome' => 'Assez-Bien',
            'ca_etab_diplome' => 'Lycée d\'Ambam',
            'ca_pays_diplome' => 'Cameroun',
            'ca_centre_examen' => 'Ambam',
            'ca_centre_depot' => 'Ambam',
            'ca_nom_pere' => 'Kakabi joseph',
            'ca_telephone_pere' => '611111111',
            'ca_nom_mere' => 'Kengne Balbine',
            'ca_telephone_mere' => '622222222',
            'ca_handicap' => 'Aucun',
            'ca_deliv_cni' => 'Ambam',
            'ca_num_recu' => 'REC2026001',
            'ca_recu' => 'recu.pdf',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 3. Création du compte candidat pour que le contrôleur puisse le retrouver lors du login
        DB::table('compte')->insert([
            'ca_num_recu' => 'REC2026001', // Utilisé comme 'login' dans le contrôleur
            'ca_code' => 'CA2026001',
            'ca_pwd' => Hash::make('password123'), // Vérifié par Hash::check dans le contrôleur
            'ca_recu' => '0000',
            'ca_nom' => 'Kakabi',
            'ca_prenom' => 'Christian',
            'ca_email' => 'kakabichristian@gmail.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}