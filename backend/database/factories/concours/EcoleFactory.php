<?php

// EcoleFactory.php
namespace Database\Factories\concours;

use App\Models\CentreDepot;
use App\Models\Ecole;
use Illuminate\Database\Eloquent\Factories\Factory;

class EcoleFactory extends Factory
{
    protected $model = Ecole::class;

    public function definition(): array
    {
        static $index = 1;

        return [
            'code_ecole' => 'ECO' . str_pad($index++, 4, '0', STR_PAD_LEFT),
            'label_ecole' => $this->faker->company . ' - École Supérieure',
            'logo_ecole' => $this->faker->optional()->imageUrl(200, 200),
            'desc_ecole' => $this->faker->paragraph(),
            'tel_ecole' => $this->faker->phoneNumber,
            'email_ecole' => $this->faker->unique()->safeEmail,
            'bp_ecole' => 'BP ' . $this->faker->numberBetween(100, 9999),
            'centre_depot_code' => CentreDepot::factory()
        ];
    }
}
