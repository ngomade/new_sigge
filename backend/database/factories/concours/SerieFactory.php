<?php

// SerieFactory.php

namespace Database\Factories\concours;

use App\Models\Serie;
use Illuminate\Database\Eloquent\Factories\Factory;

class SerieFactory extends Factory
{
    protected $model = Serie::class;

    public function definition(): array
    {
        $series = [
            'A',
            'C',
            'D',
            'E',
            'F1',
            'F2',
            'F3',
            'G1',
            'G2',
            'G3',
        ];

        return [
            'label_serie' => $this->faker->randomElement($series),
        ];
    }
}
