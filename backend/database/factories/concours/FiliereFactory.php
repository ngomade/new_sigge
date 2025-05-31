<?php

namespace Database\Factories\concours;

use App\Models\concours\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FiliereFactory extends Factory
{
    protected $model = Filiere::class;

    public function definition(): array
    {
        return [
            'filiere_code' => Str::upper($this->faker->unique()->lexify('FIL????')),
            'filiere_label' => $this->faker->words(3, true),
            'filiere_description' => $this->faker->sentence(),
            // Ajoute ici d'autres champs si besoin
        ];
    }
}
