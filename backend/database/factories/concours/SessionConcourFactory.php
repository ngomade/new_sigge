<?php

namespace Database\Factories\concours;

use App\Models\concours\Personnel;
use App\Models\concours\Sessionconcour;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionConcourFactory extends Factory
{
    protected $model = Sessionconcour::class;

    public function definition(): array
    {
        return [
            'code_pers' => Personnel::factory(),
            'annee' => $this->faker->year,
            'debut' => $this->faker->year,
            'cloture' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
        ];
    }
}
