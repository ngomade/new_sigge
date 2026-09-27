<?php

namespace Database\Seeders;

use App\Models\concours\Candidat;
use App\Models\concours\Compte;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // 1. Ajouter le seeder pour les utilisateurs et candidats de test
        $this->call(UserAndCandidatSeeder::class);

        // 2. Ajouter les rôles de laboratoire
        $this->call(RoleLaboSeeder::class);

        // 3. Ajouter les équipements de test
        $this->call(EquipementsSeeder::class);

        // 4. Ajouter les entretiens et réservations de test
        $this->call(EntretiensReservationsSeeder::class);
    }
}