<?php

namespace App\Http\Controllers\concours;

use App\Http\Requests\concours\CandidatRequest;
use App\Models\concours\Candidat;
use App\Models\concours\SessionConcours;
use DateTime;
use App\Mail\InfoMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class InscriptionController extends Controller
{

    /**
     * @throws Throwable
     */
    public function index()
    {
        return view("concours.frontend.update_infos")->render();
    }
    public function create(Request $request)
    {
        try {
            $ca = Candidat::where("ca_nom", "LIKE", "%".$request->ca_name."%")
                  ->where("ca_email", $request->ca_email)->first();
        if ($ca) {
            Mail::to($request->ca_email)
                ->send(new InfoMail($ca));
            $success = "Un mail a été envoyé à l'adresse ". $request->ca_email . " Veuillez consultez votre boite mail.";
            $request->session()->flash('success', $success);
            return redirect()->route('.councours');
        }else {
            $errors = "Les informations données ne correspondent à aucun candidat de notre base";
            $request->session()->flash('errors', $errors);
            return redirect()->route('.councours');
        }
        } catch (Throwable $th) {
            $errors = "Erreur de connexion. Vérifier  l'éffectivité de votre connexion internet.";
            $request->session()->flash('errors', $errors);
            return redirect()->route('.councours');
        }
    }


    /**
     * @throws Throwable
     */
    public function store(CandidatRequest $request)
    {
        try {
            $currentDate = new DateTime();
            $request->merge(['id' => SessionConcours::where("annee", $currentDate->format("Y"))->first()->id]);
            DB::beginTransaction();
            $ca_code = $this->generateId($request->cursus_code);
            $pictfile = $request->file('ca_photo');
            $exist = Candidat::where('ca_telephone', $request->ca_telephone)
                    ->orWhere("ca_num_cni", $request->ca_num_cni)
                    ->count();
           if ($exist == 0) {
            $pays = $request->ca_nationalite=="CMR" ? $request->ca_nationalite: $request->ca_national;
            $res = Candidat::create(array_merge($request->all(), [
                'ca_code'       => $ca_code,
                'ca_handicap'   => $request->ca_handicap.$request->ca_handicap_pre,
                'ca_photo'      => $ca_code.".".$pictfile->extension(),
                'ca_pwd'        => Str::lower(Str::random(7)),
                'ca_nationalite'=>$pays
            ]));
            $image_extension = ["png", "jpg"];
            if($res){
                $path ="/public/cartes/".getdate()['year'];
                if(!Storage::exists($path)){
                    Storage::makeDirectory($path,  0775, true);
                }
                if(($pictfile != null) && (in_array($pictfile->extension() , $image_extension)))
                    $pictPath = $pictfile->storeAs($path, "{$ca_code}.{$pictfile->extension()}");
            }
            DB::commit();
            $success="Votre inscription s'est déroulée avec success.";
            return view("concours.frontend.resultat_inscription", compact(["res","success"]));
           } else {
            return redirect()->back()->withErrors("Vous êtes entrain de faire une double inscription. ce qui est interdit. recuperer vos identifiant et modifier vos informations.");
           }
        } catch (Throwable $th) {
            DB::rollback();
            return redirect()->back()->withErrors("Une érreur s'est produite pendant l'inscription".$th);
        }
    }

    public function show($id)
    {
        $ca = Candidat::find($id);
        return view(("concours.backend.fiche_candidat"), compact("ca"))->render();
    }

    /**
     * @throws Throwable
     */
    public function update(Request $request)
    {
        try {
        DB::beginTransaction();
            $code = $request->ca_code;
            $request->request->remove("_token");
            $request->request->remove("ca_handicap_pre");
            $request->request->remove("confirm");
            $res = Candidat::where("ca_code", $code)->update($request->all());
            $res = Candidat::find($code);
            $request->session()->put('user', Candidat::find($code));
            DB::commit();
            $success="Votre mise à jour s'est déroulée avec success.";
            return view("concours.frontend.index_connexion", compact(["res","success"]));
        } catch (Throwable $th) {
            DB::rollback();
            return redirect()->back()->withErrors("Une érreur s'est produite pendant la mise à jour".$th);
        }
    }

    public function generateId($cursus){
        $id = Str::upper(Str::substr($cursus, 0, 2));
        $time = date("d-m-Y H:i:s");
        //$id .= getdate()['mon'].getdate()['wday'].getdate()['mday'].getdate()['hours'].getdate()['seconds'];
        $id .= rand(1000, 10000);
        if(Candidat::where("ca_code", $id)->count() > 0){
            return $this->generateId($cursus);
        }
        return $id;
    }
}
