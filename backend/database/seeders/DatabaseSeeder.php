<?php

namespace Database\Seeders;

use App\Models\concours\Candidat;
use App\Models\concours\Compte;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Diplome;
use App\Models\Filiere;
use App\Models\Personnel;
use App\Models\concours\SiteEtude;
use App\Models\concours\Sessionconcour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        Diplome::create([
//           'label_dip' => "DLw"
//        ]);
//
//        // Crée des personnels
//        Personnel::factory(5)->create();
//        // Création de 5 filières
//        $filieres = Filiere::factory(5)->create();
//        // Création de 5 sites d’étude
//        $sites = SiteEtude::factory(5)->create();
//        // Crée des sessions de concours
//        $sessions = SessionConcour::factory(3)->create();
//
//
//        // Création de 10 candidats avec des filiere_code valides
//        Candidat::factory(10)->make()->each(function ($candidat) use ($filieres, $sites, $sessions) {
//            $candidat->filiere_code = $filieres->random()->filiere_code;
//            $candidat->code_site = $sites->random()->code_site;
//            $candidat->id = $sessions->random()->id;
//            $candidat->save();
//        });
        Compte::create([
            'ca_num_recu' => "samendjiaha@gmail.com",
            'ca_code' => Candidat::all()->random()->ca_code,
            'ca_pwd' => Hash::make("password"),
            'ca_recu' => "0000",
            'ca_nom' => "steeven",
            'ca_email' => "samendjiaha@gmail.com",
            'ca_prenom' => "steeven",
        ]);
    }
}
