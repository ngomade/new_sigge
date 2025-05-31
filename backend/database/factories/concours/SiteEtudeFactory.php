<?php

namespace Database\Factories\concours;

use App\Models\concours\SiteEtude;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteEtudeFactory extends Factory
{
    protected $model = SiteEtude::class;

    public function definition(): array
    {
        return [
            'label_site' => $this->faker->city,
            'description_site' => $this->faker->sentence(),
        ];
    }
}
