<?php
namespace Database\Factories\concours;

use App\Models\concours\SiteComposition;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteCompositionFactory extends Factory
{
    protected $model = SiteComposition::class;

    public function definition(): array
    {
        static $index = 1;
        
        return [
            'site_code' => 'SITE' . str_pad($index++, 4, '0', STR_PAD_LEFT),
            'site_ville' => $this->faker->city,
            'site_lieu' => $this->faker->streetAddress
        ];
    }
}