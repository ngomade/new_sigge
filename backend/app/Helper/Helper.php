<?php

namespace App\Helper;

use App\Models\notes\Ec;
use App\Models\notes\FiliereNiveau;
use App\Models\notes\Inscription;
use App\Models\Personnel;
use App\Models\Quitus;
use App\Models\Users;
use App\Models\UsersDiplome;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class Helper
{
    /**
     * Génère un matricule pour un utilisateur
     */
    public static function generate_matricule($annee, $ecole): string
    {
        $annee_f = Carbon::parse($annee)->format('y');
        $nb = rand(1, 999);
        $id = $annee_f.'TLC'.sprintf('%03d', $nb);

        if ($ecole == 'ISLAPE') {
            $id .= 'I';
        } else {
            $alphabet = 'abcdefghjklmnopqrstuvwxyz';
            $randomChar = $alphabet[rand(0, strlen($alphabet) - 1)];
            $id .= Str::upper($randomChar);
        }

        // Vérifier l'unicité
        if (Users::where('code_user', $id)->exists()) {
            return self::generate_matricule($annee, $ecole);
        }

        return $id;
    }

    /**
     * Récupère le niveau actuel d'un étudiant
     */
    public static function get_current_niveau($matricule)
    {
        $ins = Inscription::where('code_user', $matricule)
            ->orderBy('date_ins', 'desc')
            ->first();

        if ($ins) {
            return FiliereNiveau::where('code_ins', $ins->code_ins)->first();
        }

        return null;
    }

    /**
     * Génère un matricule pour le personnel
     * Format amélioré : 24PS001, 24PS002, etc.
     */
    public static function generate_matricule_pers(): string
    {
        $year = Str::substr(date('Y'), 2, 2); // Année sur 2 chiffres
        $prefix = $year.'PS';

        // Récupérer le dernier matricule de l'année courante
        $lastPersonnel = Personnel::where('code_pers', 'LIKE', $prefix.'%')
            ->orderBy('code_pers', 'desc')
            ->first();

        if ($lastPersonnel) {
            // Extraire le numéro séquentiel
            $lastNumber = (int) substr($lastPersonnel->code_pers, -3);
            $nb = $lastNumber + 1;
        } else {
            $nb = 1;
        }

        $id = $prefix.sprintf('%03d', $nb);

        // Double vérification (au cas où)
        if (Personnel::where('code_pers', $id)->exists()) {
            return self::generate_matricule_pers();
        }

        return $id;
    }

    /**
     * Génère un code d'inscription
     */
    public static function generate_inscription($annee): string
    {
        $year = Str::substr($annee, 2, 2);

        // Compter les inscriptions de cette année
        $count = Inscription::where('code_ins', 'LIKE', $year.'%')->count();
        $nb = $count + 1;

        $id = $year.Str::upper(Str::random(1)).sprintf('%05d', $nb);

        // Vérifier l'unicité
        if (Inscription::where('code_ins', $id)->exists()) {
            return self::generate_inscription($annee);
        }

        return $id;
    }

    /**
     * Génère un numéro de quitus
     */
    public static function generate_quitus(): string
    {
        $annee = Carbon::now()->year;
        $year = Str::substr($annee, 2, 2);

        // Compter les quitus de cette année
        $count = Quitus::where('numero_quitus', 'LIKE', $year.'%')->count();
        $nb = $count + 1;

        $id = $year.Str::upper(Str::random(1)).sprintf('%07d', $nb);

        // Vérifier l'unicité
        if (Quitus::where('numero_quitus', $id)->exists()) {
            return self::generate_quitus();
        }

        return $id;
    }

    /**
     * Récupère la filière d'un utilisateur
     */
    public static function get_filiere($code_user)
    {
        $inscription = Inscription::where('code_user', $code_user)->first();

        if (! $inscription) {
            return null;
        }

        $filiere_niveau = FiliereNiveau::where('code_ins', $inscription->code_ins)->first();

        return $filiere_niveau?->code_filiere;
    }

    /**
     * Calcule le nombre total de crédits pour une UE
     */
    public static function get_nb_credit($code_ue)
    {
        return Ec::where('code_ue', $code_ue)->sum('credit_ec');
    }

    /**
     * Met à jour les matricules du personnel
     *
     * @throws Throwable
     */
    public static function update_matricule_pers($matricules, $ecole): string
    {
        try {
            // Désactiver les contraintes de clés étrangères temporairement
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::beginTransaction();

            foreach ($matricules as $matricule) {
                $new_matricule = self::generate_matricule(Carbon::now(), $ecole);
                $user = Users::find($matricule);

                if (! $user) {
                    continue; // Passer au suivant si l'utilisateur n'existe pas
                }

                // Mettre à jour les inscriptions
                $user->inscriptions()->update(['code_user' => $new_matricule]);

                // Mettre à jour les diplômes
                UsersDiplome::where('code_user', $user->code_user)
                    ->update(['code_user' => $new_matricule]);

                // Mettre à jour l'utilisateur
                $user->update([
                    'code_user' => $new_matricule,
                    'ecole_user' => $ecole,
                    'login_user' => $new_matricule,
                    'pwd_user' => bcrypt($new_matricule), // Utiliser bcrypt au lieu de md5
                ]);
            }

            DB::commit();

            // Réactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return 'OK';

        } catch (Throwable $th) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Réactiver même en cas d'erreur

            // Log l'erreur au lieu de l'afficher
            Log::error('Erreur lors de la mise à jour des matricules: '.$th->getMessage());

            return $th->getMessage();
        }
    }
}

// Fonctions globales pour la compatibilité avec l'ancien code
if (! function_exists('generate_matricule')) {
    function generate_matricule($annee, $ecole): string
    {
        return \App\Helper\Helper::generate_matricule($annee, $ecole);
    }
}

if (! function_exists('get_current_niveau')) {
    function get_current_niveau($matricule)
    {
        return \App\Helper\Helper::get_current_niveau($matricule);
    }
}

if (! function_exists('generate_matricule_pers')) {
    function generate_matricule_pers(): string
    {
        return \App\Helper\Helper::generate_matricule_pers();
    }
}

if (! function_exists('generate_inscription')) {
    function generate_inscription($annee): string
    {
        return \App\Helper\Helper::generate_inscription($annee);
    }
}

if (! function_exists('generate_quitus')) {
    function generate_quitus(): string
    {
        return \App\Helper\Helper::generate_quitus();
    }
}

if (! function_exists('get_filiere')) {
    function get_filiere($code_user)
    {
        return \App\Helper\Helper::get_filiere($code_user);
    }
}

if (! function_exists('get_nb_credit')) {
    function get_nb_credit($code_ue)
    {
        return \App\Helper\Helper::get_nb_credit($code_ue);
    }
}

if (! function_exists('update_matricule_pers')) {
    /**
     * @throws Throwable
     */
    function update_matricule_pers($matricules, $ecole): string
    {
        return \App\Helper\Helper::update_matricule_pers($matricules, $ecole);
    }
}
