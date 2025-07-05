<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\laboratoires\RoleLabo;

class RoleLaboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['lib_rl' => 'admin'],
            ['lib_rl' => 'membre'],
            ['lib_rl' => 'chercheur'],
            ['lib_rl' => 'technicien'],
            ['lib_rl' => 'etudiant']
        ];

        foreach ($roles as $role) {
            RoleLabo::firstOrCreate(
                ['lib_rl' => $role['lib_rl']]
            );
        }
    }
}
