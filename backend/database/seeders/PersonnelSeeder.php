<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('personnel')->insert([
            'code_pers' => 'PERS0001',
            'nom_pers' => 'Admin',
            'prenom_pers' => 'ESTLC',
            'sexe_pers' => 'Masculin',
            'date_naissance_pers' => '1990-01-01',
            'lieu_naissance_pers' => 'Ambam',
            'statut_mat_pers' => 'Célibataire',
            'lieu_residence_pers' => 'Ambam',
            'first_phone_pers' => '600000000',
            'cni_pers' => '1122334455',
            'date_deliv_cni_pers' => '2020-01-01',
            'email_pers' => 'admin@estlc..cm',
            'login_pers' => 'admin',
            // Mot de passe haché (le mot de passe en clair est "password123")
            'pwd_pers' => Hash::make('password123'),
            'lang_pers' => 'Français',
            'nationalite_pers' => 'Camerounaise',
            'region_pers' => 'Sud',
            'depart_pers' => 'Mvila',
            'arrond_pers' => 'Ambam',
            'nb_enfant_pers' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
