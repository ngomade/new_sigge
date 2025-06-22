<?php

namespace App\Http\Controllers;

use App\Models\concours\Candidat;
use App\Models\notes\FiliereNiveau;
use App\Models\notes\Inscription;
use App\Models\notes\InscriptionUe;
use App\Models\notes\Ue;
use App\Models\Users;
use PDF;
use App\Models\Quitus;
use App\Models\Diplome;
use App\Models\Filiere;
use App\Models\Tranche;
use App\Models\InfoExtra;
use Illuminate\Support\Str;
use App\Models\UsersDiplome;
use Illuminate\Http\Request;
use App\Models\Anneescolaire;
use Illuminate\Support\Carbon;
use Throwable;
use function App\Helper\get_current_niveau;
use App\Mail\PassWordRecoverMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use function App\Helper\generate_quitus;
use function App\Helper\generate_matricule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function App\Helper\generate_inscription;

class InscriptionAcademiqueController extends Controller
{

    public function index()
    {
        return view("sige_app.frontend.inscriptions.inscription_academique");
    }

    public function reproduction_document($code_user)
    {   $user = Users::where("code_user", $code_user)->first();
        $code_ins = Inscription::where("code_user", $code_user)->first()->code_ins;
        $code_filiere = FiliereNiveau::where("code_ins", $code_ins)->first()->code_filiere;
        $quitus = Quitus::where("code_ins", $code_ins)->orderBy("code_tranche")->get();
        $nb_q1 = $quitus[0]->numero_quitus;
        $nb_q2 = $quitus[1]->numero_quitus;
        $nb_q3 = $quitus[2]->numero_quitus;
        return view("sige_app.frontend.inscriptions.fiche_administrative_quitus", compact(["user", "code_filiere", "nb_q1", "nb_q2", "nb_q3", "code_ins"]));
    }

    /**
     * @throws Throwable
     */
    public function recherche(Request $request)
    {

        $motif = $request->motif;
        $user = "0";
        try {
            DB::beginTransaction();
            $user = Candidat::where("ca_telephone", "LIKE", "%$motif%")
                      ->orWhere("ca_num_cni", "LIKE", "%$motif%")->first();
            $us = Users::where("code_user", $motif)->first();
            if ($us != null) {
                $exist = "Votre inscription a déja été faites. Connectez-vous à votre espace pour télécharger à nouveau vos documents";
                    return view("sige_app.frontend.inscriptions.inscription_academique", compact("exist"));
            }else {
                if ($user != null) {
                    $utilisateurs = Users::where("numero_cni_user", "LIKE", "%$user->ca_num_cni%")
                                            ->orWhere("first_phone_user", "LIKE", "%$user->ca_telephone%")
                                            ->count();
                    if ($utilisateurs > 0) {
                        $exist = "Votre inscription a déja été faites. Connectez-vous à votre espace pour télécharger à nouveau vos documents";
                        return view("sige_app.frontend.inscriptions.inscription_academique", compact("exist"));
                    }
                }else{
                    $user = 0;
                }
            }

            DB::commit();
        } catch (Throwable $th) {
            DB::rollback();
        }
        return view("sige_app.frontend.inscriptions.inscription_academique", compact("user"));
    }

    public function production_fiche($code_ins)
    {
        $c_ins = explode("_", $code_ins)[0];
        $code_filiere = explode("_", $code_ins)[1];
        $ins = Inscription::find($c_ins);
        if ($ins != null) {
            $user = Users::where("code_user", $ins->code_user)->first();
            $info_extra = InfoExtra::where("code_info_extra", $user->code_info_extra)->first();
            $pdf = PDF::loadView("sige_app.pdf.fiche_administrative", compact(["user", "info_extra", "code_filiere"]))->setPaper('a4');
            return $pdf->download("Fiche_Administrative_".$user->code_user.'.pdf');
        }else{
            return redirect()->back();
        }
    }

    public function academie_download($code_ins)
    {
        $ins = Inscription::find($code_ins);
        if ($ins != null) {
            $user = Users::where("code_user", $ins->code_user)->first();
            $filiere = FiliereNiveau::where("code_filiere", Session::get('filiere')->code_filiere)->first();
            $code_filiere = $filiere->code_filiere;
            $annee = Str::startsWith(Session::get('user')->code_user, '23');
            $ues = $annee? Ue::join("inscription_ue", "ue.code_ue", "inscription_ue.code_ue")
                        ->where("inscription_ue.code_ins", $code_ins)->orderBy("ue.code_sem")
                        ->get("ue.*"):
                        Ue::with("ecs")->where("code_ue","LIKE", "%{$code_filiere}%")
                        ->where(function($query) {
                            $query->where("code_sem", "S1")
                            ->orWhere("code_sem", "S2");
                        })
                        ->get();
            $pdf = PDF::loadView("sige_app.pdf.fiche_academique", compact(["user", "filiere", "ues"]))->setPaper('a4');
            return $pdf->download("Fiche_Academique_".$user->code_user.'.pdf');
        }else{
            return redirect()->back();
        }
    }

    public function inscription_academique_index(){
        $user = Session::get("user");
        $filiere_niveau = get_current_niveau($user->code_user);
        //dd($filiere_niveau);
        $code_filiere = $filiere_niveau->code_filiere;
        $annee = Str::startsWith(Session::get('user')->code_user, '23');
        $ues = $annee? Ue::with("ecs")->where("code_ue","LIKE", "%{$code_filiere}%")->orderBy("code_sem")
                            // ->where(function($query) {
                            //     $query->where("code_sem", "S3")
                            //     ->orWhere("code_sem", "S4");
                            // })
                            ->get():
                    Ue::with("ecs")->where("code_ue","LIKE", "%{$code_filiere}%")
                    ->where(function($query) {
                        $query->where("code_sem", "S1")
                        ->orWhere("code_sem", "S2");
                    })->orderBy("code_sem")
                    ->get();
        return view("sige_app.frontend.inscriptions.fiche_inscription_academique", compact(["user", "filiere_niveau", "ues"]));
    }


    public function production_quitus(string $code_quitus){
        $c_quitus = explode("_", $code_quitus)[0];
        $code_user = explode("_", $code_quitus)[1];
        $code_filiere = explode("_", $code_quitus)[2];
        $quitus = Quitus::where("numero_quitus", $c_quitus)
        ->first();
        if ($quitus != null) {
            $ins = Inscription::where("code_ins", $quitus->code_ins);
            $user = Users::where("code_user", $code_user)->first();
            $tranche = Tranche::where("code_tranche", $quitus->code_tranche)->first();
            $aca = Anneescolaire::orderBy("debut_annee", "desc")->first();
            $filiere = Filiere::where("code_filiere", $code_filiere)->first();
            $qrcode = base64_encode(QrCode::size(100)->format("svg")->generate(urlencode($user->code_user."-".$user->nom_user."-". $user->prenom_user)));
            $type_tranche = "";
            if ($tranche->code_tranche <3 ) {
                $type_tranche = "INSCRIPTION";
            }else{
                $type_tranche = "FRAIS MEDICAUX";
            }
            $pdf = PDF::loadView("sige_app.pdf.quitus", compact(["user", "ins", "quitus", "tranche", "type_tranche", "aca", "qrcode", "filiere"]))->setPaper('a4', 'landscape');
            return $pdf->download("Quitus_".$c_quitus.'.pdf');
        }else{
            return redirect()->back();
        }
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $annee = Anneescolaire::orderBy("created_at", "desc")->first();
            $type = $request->type;
            $code_user = generate_matricule($annee->debut_annee, $request->ecole_user);
            $info_extra = InfoExtra::create($request->all());
            $pwd_user = $type=="backend"?$code_user:$request->pwd_user;
            $user = Users::create(array_merge($request->all(),[
               'code_user'          =>$code_user,
               'code_info_extra'    =>$info_extra->code_info_extra,
               'login_user'         =>$code_user,
               'pwd_user'           =>md5($pwd_user)
            ]));
            $dip = $serie = "";
            if ($type == "backend") {
                $dip = explode(" ", $request->label_di)[0] == "BACC"? "BACCALUAREAT":"GCE";
                $serie =  explode(" ", $request->label_di)[1];
            }else{
                $dip = $request->label_dip;
                $serie = $request->specialite_dip;
            }
            $diplome = Diplome::where("label_dip", "LIKE", "%$dip%")
                                ->where("specialtite_dip", "LIKE", "%$serie%")->first();
            if ($diplome == null) {
                $diplome = Diplome::create([
                    'label_dip'         =>$dip,
		            'specialtite_dip'   =>$serie
                ]);
            }
            $user_dip = UsersDiplome::create(array_merge($request->all(),[
                "code_user" =>$user->code_user,
                "code_dip"  =>$diplome->code_dip
            ]));
            $code_ins = generate_inscription($annee->debut_annee->year);
            $inscription = Inscription::create([
                'code_ins'      =>$code_ins ,
                'code_user'     =>$user->code_user,
                'code_annee'    => $annee->code_annee,
                'date_ins'      => Carbon::now(),
                'statut_ins'    =>0
            ]);
            $niveau = FiliereNiveau::create([
                'code_filiere'      =>$request->code_filiere,
                'code_niveau'       =>2,
                'code_ins'          =>$code_ins
            ]);
            $nb_q1 = generate_quitus();
            $nb_q2 = generate_quitus();
            $nb_q3 = generate_quitus();
            $data = [
                [
                    'numero_quitus'     =>$nb_q1,
                    'date_paiement'     =>Carbon::now(),
                    'statut_quitus'     =>0,
                    'code_ins'          =>$inscription->code_ins,
                    'code_tranche'      =>1,
                    'code_mode'         =>1
                ],
                [
                    'numero_quitus'     =>$nb_q2,
                    'date_paiement'     =>Carbon::now(),
                    'statut_quitus'     =>0,
                    'code_ins'          =>$inscription->code_ins,
                    'code_tranche'      =>2,
                    'code_mode'         =>1
                ],
                [
                    'numero_quitus'     =>$nb_q3,
                    'date_paiement'     =>Carbon::now(),
                    'statut_quitus'     =>0,
                    'code_ins'          =>$inscription->code_ins,
                    'code_tranche'      =>3,
                    'code_mode'         =>1
                ]
            ];
            $quitus = Quitus::insert($data);
            $code_filiere = $niveau->code_filiere;
            if ($type == "backend") {
                $code_cand = $request->code_cand;
                $res = Candidat::where("ca_code", $code_cand)->update(["ca_email_pere"=>"inscrit@estlc"]);
                DB::commit();
                return redirect()->route("show_candidat_list")->with("user",$user);
            }
            DB::commit();
            return view("sige_app.frontend.inscriptions.fiche_administrative_quitus", compact(["user", "code_filiere", "nb_q1", "nb_q2", "nb_q3", "code_ins"]));
        } catch (Throwable $th) {
            DB::rollback();
        }
    }

    public function inscription_academique(Request $request)
    {
        try {
            $selectedUes1 = $request->input('selected_ues1', []);
            $selectedUes2 = $request->input('selected_ues2', []);
            $user = Session::get("user");
            DB::beginTransaction();
            $res = Inscription::firstWhere("code_user", $user->code_user);
            $res->statut_ins = 1;
            foreach($selectedUes1 as $ue){
                try {
                    InscriptionUE::create([
                        'code_ins' => $res->code_ins,
                        'code_ue' => $ue,
                        'etat'  =>1
                    ]);
                } catch (Throwable $th) {

                }
            }
            foreach($selectedUes2 as $ue){
                try {
                    InscriptionUE::create([
                        'code_ins' => $res->code_ins,
                        'code_ue' => $ue,
                        'etat'  =>1
                    ]);
                } catch (Throwable $th) {

                }
            }
            $res->save();
            DB::commit();
            return view("sige_app.frontend.inscriptions.fiche_academique_resultat", compact("res"));
        } catch (Throwable $th) {
            DB::rollback();
        }
    }

    public function recuperation_pwd(Request $request){
        try {
            $user = Users::where("code_user",$request->nom_user)
                  ->where("email_user", $request->email_user)->first();
        if ($user) {
            $pwd = Str::random(8);
            $user->pwd_user = md5($pwd);
            $user->save();
            Mail::to($request->email_user)
                ->send(new PassWordRecoverMail($user, $pwd));
            $success = "Un mail a été envoyé à l'adresse ".$request->email_user . " Veuillez consulter votre boite mail.";
            $request->session()->flash('success', $success);
            return view("sige_app.frontend.index");
        }else {
            $errors = "Les informations données ne correspondent à aucun étudiant inscris de notre école";
            $request->session()->flash('errors', $errors);
            return view("sige_app.frontend.index");
        }
        } catch (Throwable $th) {
            $errors = "Erreur de connexion. Vérifier  l'éffectivité de votre connexion internet.".$th;
            $request->session()->flash('errors', $errors);
            return view("sige_app.frontend.index");
        }
    }
}
