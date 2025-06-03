<?php

namespace Database\Factories\concours;

use App\Models\concours\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class FiliereFactory extends Factory
{
    protected $model = Filiere::class;

    public function definition()
    {
        static $index = 1;
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
            'Génie de la Maintenance'
        ];

        return [
            'filiere_code' => 'FIL' . str_pad($index++, 4, '0', STR_PAD_LEFT),
            'filiere_label' => $filieres[($index - 2) % count($filieres)],
            'filiere_description' => $this->faker->paragraph()
        ];
    }
}
