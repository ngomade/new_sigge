<?php

// DossierFactory.php
namespace Database\Factories\concours;

use App\Models\concours\Dossier;
use Illuminate\Database\Eloquent\Factories\Factory;

class DossierFactory extends Factory
{
    protected $model = Dossier::class;

    public function definition(): array
    {
        $elements = [
            'Acte de naissance',
            'Certificat de scolarité',
            'Relevé de notes',
            'Diplôme',
            'Photo d\'identité',
            'Certificat médical',
            'Casier judiciaire',
            'Attestation de bourse'
        ];

        return [
            'label_el' => $this->faker->randomElement($elements)
        ];
    }
}