<?php

namespace Database\Factories\concours;

use App\Models\concours\Candidat;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatFactory extends Factory
{
    protected $model = Candidat::class;

    public function definition(): array
    {
        return [
            'ca_code' => $this->faker->unique()->bothify('CA####'),
            'ca_nom' => $this->faker->lastName,
            'ca_prenom' => $this->faker->firstName,
            'ca_sexe' => $this->faker->randomElement(['M', 'F']),
            'ca_date_naiss' => $this->faker->date(),
            'ca_lieu_naiss' => $this->faker->city,
            'ca_statut_mat' => $this->faker->randomElement(['Célibataire', 'Marié(e)']),
            'ca_adresse' => $this->faker->address,
            'ca_telephone' => $this->faker->phoneNumber,
            'ca_num_cni' => $this->faker->bothify('CNI########'),
            'ca_email' => $this->faker->unique()->safeEmail,
            'ca_premiere_lang' => $this->faker->randomElement(['Français', 'Anglais']),
            'ca_nationalite' => $this->faker->country,
            'ca_region_origine' => $this->faker->city,
            'ca_depart_origine' => $this->faker->city,
            'ca_diplome_admission' => $this->faker->word,
            'ca_annee_diplome' => $this->faker->year,
            'ca_serie_diplome' => $this->faker->word,
            'ca_mention_diplome' => $this->faker->word,
            'ca_etab_diplome' => $this->faker->company,
            'ca_pays_diplome' => $this->faker->country,
            'ca_centre_examen' => $this->faker->city,
            'ca_centre_depot' => $this->faker->city,
            'ca_nom_pere' => $this->faker->lastName,
            'ca_telephone_pere' => $this->faker->phoneNumber,
            'ca_nom_mere' => $this->faker->lastName,
            'ca_telephone_mere' => $this->faker->phoneNumber,
            'ca_handicap' => $this->faker->randomElement(['Aucun', 'Oui']),
            'ca_email_pere' => $this->faker->optional()->safeEmail,
            'ca_deliv_cni' => $this->faker->city,
            'ca_num_recu' => $this->faker->unique()->bothify('REC####'),
            'ca_recu' => $this->faker->bothify('R####'),
        ];
    }
}
