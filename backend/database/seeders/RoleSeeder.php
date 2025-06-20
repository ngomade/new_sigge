<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Chef de Département', 'guard_name' => 'web'],
            ['name' => 'Directeur des Études', 'guard_name' => 'web'],
            ['name' => 'Responsable Pédagogique', 'guard_name' => 'web'],
            ['name' => 'Enseignant', 'guard_name' => 'web'],
            ['name' => 'Assistant', 'guard_name' => 'web'],
            ['name' => 'Secrétaire', 'guard_name' => 'web'],
            ['name' => 'Comptable', 'guard_name' => 'web'],
            ['name' => 'Responsable Informatique', 'guard_name' => 'web'],
            ['name' => 'Responsable RH', 'guard_name' => 'web'],
            ['name' => 'Agent de Sécurité', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}