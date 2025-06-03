<?php

// CentreExamenFactory.php
namespace Database\Factories\concours;

use App\Models\concours\CentreExaman;
use App\Models\concours\Ecole;
use Illuminate\Database\Eloquent\Factories\Factory;

class CentreExamenFactory extends Factory
{
    protected $model = CentreExaman::class;

    public function definition(): array
    {
        return [
            'code_ecole' => Ecole::factory(),
            'centre_exam_label' => $this->faker->city . ' - Centre d\'Examen'
        ];
    }
}