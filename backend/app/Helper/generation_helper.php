<?php

namespace App\Helper;

use App\Models\concours\User;
use App\Models\notes\Ec;
use App\Models\notes\FiliereNiveau;
use App\Models\notes\Inscription;
use App\Models\Personnel;
use App\Models\Quitus;
use App\Models\UsersDiplome;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

function generate_matricule($annee, $ecole): string
{
    $annee_f = Carbon::parse($annee)->format('y');
    $nb = rand(1, 999);
    $id = $annee_f . "TLC" . sprintf("%03d", $nb);
    if ($ecole == "ISLAPE") {
        $id .= "I";
    } else {
        $alphabet = 'abcdefghjklmnopqrstuvwxyz';
        $randomChar = $alphabet[rand(0, strlen($alphabet) - 1)];
        $id .= Str::upper($randomChar);
    }
    if (User::where("code_user", $id)->count() > 0) {
        return generate_matricule($annee, $ecole);
    }
    return $id;
}

function get_current_niveau($matricule)
{
    $ins = Inscription::where("code_user", $matricule)->orderBy("date_ins", "desc")->first();
    if ($ins)
        return FiliereNiveau::where("code_ins", $ins->code_ins)->first();
    return null;
}

function generate_matricule_pers(): string
{
    $nb = Personnel::count() + 1;
    $id = Str::substr(date("Y") . "", 2, 2) . "PS" . sprintf("%03d", $nb);
    if (Personnel::where("code_pers", $id)->count() > 0) {
        return generate_matricule_pers();
    }
    return $id;
}

function generate_inscription($annee): string
{
    $nb = Inscription::count() + 1;
    $id = Str::substr($annee . "", 2, 2) . Str::upper(Str::random(1)) . sprintf("%05d", $nb);
    if (Inscription::where("code_ins", $id)->count() > 0) {
        return generate_inscription($annee);
    }
    return $id;
}

function generate_quitus(): string
{
    $annee = Carbon::now()->year;
    $nb = Quitus::count() + 1;
    $id = Str::substr($annee . "", 2, 2) . Str::upper(Str::random(1)) . sprintf("%07d", $nb);
    if (Quitus::where("numero_quitus", $id)->count() > 0) {
        return generate_quitus();
    }
    return $id;
}

function get_filiere($code_user)
{
    $inscription = Inscription::where("code_user", $code_user)->first();
    $filiere_niveau = FiliereNiveau::firstWhere("code_ins", $inscription->code_ins);
    return $filiere_niveau->code_filiere;
}

function get_nb_credit($code_ue)
{
    $ecs = Ec::where("code_ue", $code_ue)->get();
    $nb_credit = 0;
    foreach ($ecs as $ec) {
        $nb_credit += $ec->credit_ec;
    }
    return $nb_credit;
}

/**
 * @throws Throwable
 */
function update_matricule_pers($matricules, $ecole): string
{
    try {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($matricules as $matricule) {
            DB::beginTransaction();
            $new_matricule = generate_matricule(Carbon::now(), $ecole);
            $user = User::find($matricule);
            $user->inscriptions()->update(['code_user' => $new_matricule]);
            UsersDiplome::where("code_user", $user->code_user)->update(["code_user" => $new_matricule]);
            $user->update([
                'code_user' => $new_matricule,
                "ecole_user" => $ecole,
                "login_user" => $new_matricule,
                "pwd_user" => md5($new_matricule)
            ]);
            DB::commit();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return "OK";
    } catch (Throwable $th) {
        DB::rollBack();
        echo 'Hello world' . $th->getMessage();
        return $th->getMessage();
    }
}

