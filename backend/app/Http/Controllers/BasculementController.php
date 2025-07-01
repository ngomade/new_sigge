<?php

namespace App\Http\Controllers;

use App\Models\Anneescolaire;
use App\Models\notes\FiliereNiveau;
use App\Models\notes\Inscription;
use App\Models\Quitus;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Throwable;
use function App\Helper\generate_inscription;
use function App\Helper\generate_quitus;

class BasculementController extends Controller
{
    public function index()
    {
        return view("sige_app.backend.etudiant.basculement");
    }

    public function search_user(Request $request, string $view)
    {
        $filiere = $request->code_filiere;
        $ecole = $request->code_ecole;
        $annee = $request->code_annee;
        $niveau = $request->niveau;
        $inscrit = $request->inscrit;
        $user = [];
        if($inscrit != null){
            $users = Users::join("inscription", "users.code_user", "inscription.code_user")
                ->join("filiere_niveau", "filiere_niveau.code_ins", "inscription.code_ins")
                ->where("users.ecole_user", $ecole)
                ->where("inscription.code_annee", (int)$annee)
                ->where("inscription.statut_ins", (int)$inscrit)
                ->where("filiere_niveau.code_filiere", $filiere)
                ->where("filiere_niveau.code_niveau", (int)$niveau)
                ->orderBy("users.nom_user")
                ->get();
        }else{
            $users = Users::join("inscription", "users.code_user", "inscription.code_user")
                ->join("filiere_niveau", "filiere_niveau.code_ins", "inscription.code_ins")
                ->where("users.ecole_user", $ecole)
                ->where("inscription.code_annee", (int)$annee)
                ->where("filiere_niveau.code_filiere", $filiere)
                ->where("filiere_niveau.code_niveau", (int)$niveau)
                ->orderBy("users.nom_user")
                ->get();
        }
        return view("sige_app.backend.etudiant.".$view, compact("users"));
    }

    public function store(Request $request)
    {
        $niveau = $request->niveau_c;
        $option_b = $request->option_b;
        $selectedUsers = $request->input('selected_users', []);
        if (empty($selectedUsers)) {
            return redirect("basculement_index")->with('errors', 'Aucun etudiant sélectionné.');
        }
        $annee = Anneescolaire::orderBy("created_at", "desc")->first();
        try{
            foreach($selectedUsers as $code_user){
                if($option_b == "misajour"){
                    $inscription = Inscription::where("code_user", $code_user)
                                        ->where("code_annee", $annee->code_annee)->first();
                    if($inscription != null){
                        $res = FiliereNiveau::where("code_ins", $inscription->code_ins)->update([
                            "code_niveau" => $niveau
                        ]);
                    }
                }else{
                    $code_ins = generate_inscription($annee->debut_annee->year);
                $inscription = Inscription::create([
                    'code_ins'      =>$code_ins ,
                    'code_user'     =>$code_user,
                    'code_annee'    => $annee->code_annee,
                    'date_ins'      => Carbon::now(),
                    'statut_ins'    =>0
                ]);
                $niveau = FiliereNiveau::create([
                    'code_filiere'      =>$request->code_filiere,
                    'code_niveau'       =>$niveau,
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
                }
            }
            return redirect("basculement_index")->with('success', 'Les étudiants sélectionnés ont été basculés au niveau '.$request->niveau_c);
        }catch(Throwable $th){
            return redirect("basculement_index")->with('errors', 'problème survenu lors du basculement. Certains étudiants ont étés basculés'.$th);
        }
    }
}
