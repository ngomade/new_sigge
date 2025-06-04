<?php

// CentreDepotFactory.php
namespace Database\Factories\concours;

use App\Models\concours\CentreDepot;
use Illuminate\Database\Eloquent\Factories\Factory;

class CentreDepotFactory extends Factory
{
    protected $model = CentreDepot::class;

    public function definition(): array
    {
        static $index = 1;
        
        return [
            'centre_depot_code' => 'CD' . str_pad($index++, 4, '0', STR_PAD_LEFT),
            'centre_depot_label' => $this->faker->company . ' - Centre de Dépôt'
        ];
    }
}