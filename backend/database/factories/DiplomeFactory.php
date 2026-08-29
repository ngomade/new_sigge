<?php

// DiplomeFactory.php

namespace Database\Factories;

use App\Models\Diplome;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiplomeFactory extends Factory
{
    protected $model = Diplome::class;

    public function definition(): array
    {
        $diplomes = [
            'Baccalauréat',
            'BTS',
            'DUT',
            'Licence',
            'Master',
            'Doctorat',
            'CAP',
            'BEP',
            'DEUG',
            'DESS',
        ];

        return [
            'label_dip' => $this->faker->randomElement($diplomes),
        ];
    }
}
