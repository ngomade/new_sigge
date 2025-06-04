<?php

namespace Database\Factories\concours;

use App\Models\concours\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonnelFactory extends Factory
{
    protected $model = Personnel::class;

    public function definition(): array
    {
        return [
            'code_pers' => $this->faker->numerify("PERS#####"),
            'nom_pers' => $this->faker->lastName,
            'prenom_pers' => $this->faker->firstName,
            'sexe_pers' => $this->faker->randomElement(['M', 'F']),
            'date_naissance_pers' => $this->faker->date(),
            'lieu_naissance_pers' => $this->faker->city,
            'statut_mat_pers' => $this->faker->randomElement(['Célibataire', 'Marié(e)', 'Divorcé(e)']),
            'lieu_residence_pers' => $this->faker->address,
            'first_phone_pers' => $this->faker->phoneNumber,
            'second_phone_pers' => $this->faker->optional()->phoneNumber,
            'cni_pers' => $this->faker->unique()->bothify('CNI#######'),
            'date_deliv_cni_pers' => $this->faker->date(),
            'email_pers' => $this->faker->unique()->safeEmail,
            'login_pers' => $this->faker->userName,
            'pwd_pers' => bcrypt('password'),
            'photo_pers' => $this->faker->optional()->imageUrl(),
            'lang_pers' => $this->faker->optional()->randomElement(['Français', 'Anglais']),
            'nationalite_pers' => $this->faker->optional()->country,
            'region_pers' => $this->faker->optional()->city,
            'depart_pers' => $this->faker->optional()->city,
            'arrond_pers' => $this->faker->optional()->city,
            'bibliographie_pers' => $this->faker->optional()->text(100),
            'nb_enfant_pers' => $this->faker->optional()->numberBetween(0, 5),
        ];
    }
}
