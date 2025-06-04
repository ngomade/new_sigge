<?php

namespace Database\Factories\concours;

use App\Models\concours\Compte;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompteFactory extends Factory
{
    protected $model = Compte::class;

    public function definition(): array
    {
        return [
            'code_pers' => 'PERS' . $this->faker->unique()->numberBetween(1000, 9999),
            'nom_pers' => $this->faker->lastName,
            'prenom_pers' => $this->faker->firstName,
            'sexe_pers' => $this->faker->randomElement(['M', 'F']),
            'date_naissance_pers' => $this->faker->date('Y-m-d', '-20 years'),
            'lieu_naissance_pers' => $this->faker->city,
            'statut_mat_pers' => $this->faker->randomElement(['Célibataire', 'Marié(e)', 'Divorcé(e)']),
            'lieu_residence_pers' => $this->faker->address,
            'first_phone_pers' => '6' . $this->faker->numerify('########'),
            'second_phone_pers' => '6' . $this->faker->numerify('########'),
            'cni_pers' => $this->faker->unique()->numerify('##########'),
            'date_deliv_cni_pers' => $this->faker->date('Y-m-d', '-1 year'),
            'email_pers' => $this->faker->unique()->safeEmail,
            'login_pers' => $this->faker->unique()->userName,
            'pwd_pers' => bcrypt('password'),
            'lang_pers' => 'fr',
            'nationalite_pers' => 'Camerounaise',
            'region_pers' => $this->faker->randomElement(['Littoral', 'Centre', 'Ouest']),
            'depart_pers' => $this->faker->city,
            'nb_enfant_pers' => $this->faker->numberBetween(0, 5),
        ];
    }
}
