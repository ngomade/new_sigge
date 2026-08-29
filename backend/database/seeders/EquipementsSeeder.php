<?php

namespace Database\Seeders;

use App\Models\laboratoires\Equipements;
use App\Models\laboratoires\Laboratoire;
use Illuminate\Database\Seeder;

class EquipementsSeeder extends Seeder
{
    public function run(): void
    {
        $laboratoires = Laboratoire::all();

        foreach ($laboratoires as $laboratoire) {
            // Équipements de laboratoire informatique
            if (str_contains(strtolower($laboratoire->nom_lab), 'informatique') || str_contains(strtolower($laboratoire->nom_lab), 'info')) {
                $equipements = [
                    [
                        'nom_equip' => 'Serveur Dell PowerEdge R740',
                        'ref_equip' => 'DELL-R740-001',
                        'desc_equip' => 'Serveur haute performance pour le traitement de données et l\'hébergement d\'applications',
                        'etat' => 'disponible',
                        'date_achat' => '2023-03-15',
                        'valeur' => 2500000,
                        'localisation' => 'Salle serveurs A12',
                    ],
                    [
                        'nom_equip' => 'Microscope électronique à balayage',
                        'ref_equip' => 'SEM-2023-001',
                        'desc_equip' => 'Microscope électronique pour l\'analyse des surfaces et structures microscopiques',
                        'etat' => 'disponible',
                        'date_achat' => '2023-06-20',
                        'valeur' => 4500000,
                        'localisation' => 'Laboratoire d\'analyse B15',
                    ],
                    [
                        'nom_equip' => 'Station de travail HP Z8 G4',
                        'ref_equip' => 'HP-Z8-2023-001',
                        'desc_equip' => 'Station de travail haute performance pour le calcul intensif et la modélisation 3D',
                        'etat' => 'en maintenance',
                        'date_achat' => '2023-09-10',
                        'valeur' => 1800000,
                        'localisation' => 'Salle informatique C8',
                    ],
                    [
                        'nom_equip' => 'Oscilloscope numérique Tektronix',
                        'ref_equip' => 'TEK-OSC-001',
                        'desc_equip' => 'Oscilloscope haute fréquence pour l\'analyse des signaux électroniques',
                        'etat' => 'disponible',
                        'date_achat' => '2022-11-05',
                        'valeur' => 850000,
                        'localisation' => 'Laboratoire électronique D3',
                    ],
                    [
                        'nom_equip' => 'Imprimante 3D Ultimaker S5',
                        'ref_equip' => 'ULT-S5-2023-001',
                        'desc_equip' => 'Imprimante 3D professionnelle pour la fabrication additive',
                        'etat' => 'hors service',
                        'date_achat' => '2023-01-15',
                        'valeur' => 1200000,
                        'localisation' => 'Atelier de prototypage E7',
                    ],
                ];
            }
            // Équipements de laboratoire de recherche
            else {
                $equipements = [
                    [
                        'nom_equip' => 'Centrifugeuse haute vitesse',
                        'ref_equip' => 'CENT-HS-2023-001',
                        'desc_equip' => 'Centrifugeuse pour la séparation des composants biologiques',
                        'etat' => 'disponible',
                        'date_achat' => '2023-04-12',
                        'valeur' => 950000,
                        'localisation' => 'Laboratoire de biologie F10',
                    ],
                    [
                        'nom_equip' => 'Spectrophotomètre UV-Vis',
                        'ref_equip' => 'SPEC-UV-2023-001',
                        'desc_equip' => 'Appareil de mesure d\'absorbance pour analyses chimiques',
                        'etat' => 'disponible',
                        'date_achat' => '2023-07-08',
                        'valeur' => 750000,
                        'localisation' => 'Laboratoire de chimie G5',
                    ],
                    [
                        'nom_equip' => 'Étuve de séchage',
                        'ref_equip' => 'ETUVE-2023-001',
                        'desc_equip' => 'Étuve pour le séchage et la stérilisation d\'échantillons',
                        'etat' => 'en maintenance',
                        'date_achat' => '2022-12-20',
                        'valeur' => 450000,
                        'localisation' => 'Laboratoire de préparation H2',
                    ],
                    [
                        'nom_equip' => 'Microscope optique binoculaire',
                        'ref_equip' => 'MICRO-OPT-2023-001',
                        'desc_equip' => 'Microscope pour l\'observation d\'échantillons biologiques',
                        'etat' => 'disponible',
                        'date_achat' => '2023-02-28',
                        'valeur' => 650000,
                        'localisation' => 'Laboratoire d\'observation I9',
                    ],
                    [
                        'nom_equip' => 'Balance de précision',
                        'ref_equip' => 'BAL-PREC-2023-001',
                        'desc_equip' => 'Balance de précision pour pesées précises en laboratoire',
                        'etat' => 'disponible',
                        'date_achat' => '2023-05-15',
                        'valeur' => 350000,
                        'localisation' => 'Laboratoire de pesée J4',
                    ],
                ];
            }

            foreach ($equipements as $equipement) {
                Equipements::create([
                    'nom_equip' => $equipement['nom_equip'],
                    'ref_equip' => $equipement['ref_equip'],
                    'desc_equip' => $equipement['desc_equip'],
                    'etat' => $equipement['etat'],
                    'date_achat' => $equipement['date_achat'],
                    'valeur' => $equipement['valeur'],
                    'localisation' => $equipement['localisation'],
                    'code_lab' => $laboratoire->code_lab,
                ]);
            }
        }
    }
}
