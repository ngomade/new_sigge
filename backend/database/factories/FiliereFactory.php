<?php

namespace Database\Factories;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class FiliereFactory extends Factory
{
    protected $model = Filiere::class;

    public function definition(): array
    {
        $filieres = [
            'Génie Informatique',
            'Génie Civil',
            'Génie Électrique',
            'Génie Mécanique',
            'Génie Chimique',
            'Génie Industriel',
            'Génie des Télécommunications',
            'Génie des Procédés',
            'Génie des Mines',
            'Génie Pétrolier',
            'Génie Logiciel',
            'Génie des Systèmes',
            'Génie des Réseaux',
            'Génie de la Production',
            'Génie des Transports',
            'Génie Énergétique',
            'Génie des Communications',
            'Génie de la Sécurité',
            'Génie de la Gestion',
            'Génie de la Maintenance',
        ];

        return [
            'code_filiere' => $this->faker->numerify('FIL#####'),
            'filiere_label' => $this->faker->randomElement($filieres),
            'filiere_description' => $this->faker->paragraph(),
        ];
    }
}
