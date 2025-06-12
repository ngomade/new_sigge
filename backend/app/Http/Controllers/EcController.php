<?php

namespace App\Http\Controllers;

use App\Models\notes\Semestre;
use Response;
use App\Models\notes\Ec;
use App\Models\notes\Ue;
use App\Models\notes\Ressource;
use App\Models\notes\EcRessource;
use App\Models\notes\Inscription;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\notes\FiliereNiveau;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EcController extends Controller
{
    public function index()
    {
        $semestres = Semestre::with("ues.ecs")->get();
        return view('sige_app.backend.ecs.gestion_ecs', ["semestres" => $semestres]);
    }


    public function maintenance()
    {
        return view("sige_app.maintenance");
    }

    public function show_download_ec()
    {

        $filiere = FiliereNiveau::where("code_filiere", Session::get('filiere')->code_filiere)->first();
        $inscription = Inscription::firstWhere("code_user", Session::get('user')->code_user);
        $code_filiere = $filiere->code_filiere;
        $annee = Str::startsWith(Session::get('user')->code_user, '23');
        $semestre = $annee ? "S4" : "S2";
        //$semestre = $annee?"S3":"S1";
        $ues = Ue::with("ecs")->where("code_ue", "LIKE", "%{$code_filiere}%")
            ->where("code_sem", $semestre)->get();
        return view("sige_app.frontend.ecs.download_cours", compact(["filiere", "inscription", "ues"]));
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ec = Ec::create(array_merge($request->all(), [

            ]));
            if ($ec != null) {
                $res = Ressource::create(array_merge($request->all(),
                    [
                        "code_ec" => $ec->code_ec,
                        "label_res" => $ec->code_ec . ".pdf"
                    ]));
                if ($res) {
                    $file = $request->file('label_res');
                    $path = "public" . DIRECTORY_SEPARATOR . "ecs" . DIRECTORY_SEPARATOR;
                    if (!Storage::exists($path)) {
                        Storage::makeDirectory($path, 0775, true);
                    }
                    $file->storeAs($path, "{$ec->code_ec}" . ".{$file->extension()}");
                }
                DB::commit();
                $success = "Ec créer avec success";
                $request->session()->flash('success', $success);
                EcRessource::create([
                    "code_ec" => $ec->code_ec,
                    "code_res" => $res->code_res,
                    "code_pers" => Session::get("pers")->code_pers
                ]);
                return redirect()->route("gestion_ec");
            }
            return redirect()->back()->withErrors("Echec de création de l'EC")->withInput();
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de création de l'EC" . $th)->withInput();
        }
    }

    public function download_ec($id)
    {
        return Response::download(storage_path("app" . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "ecs" . DIRECTORY_SEPARATOR . $id . ".pdf"));
    }


    public function destroy($id)
    {

        try {
            DB::beginTransaction();
            $res = Ressource::destroy(Ressource::where("code_ec", $id)->first()->code_res);
            $ue = Ec::destroy($id);
            DB::commit();
            Session::flash("success", "EC suprrimé avec success");
            return redirect()->route("gestion_ec");
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de suppression de l'EC" . $th);
        }
    }
}
