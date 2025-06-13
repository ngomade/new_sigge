<?php

namespace App\Http\Controllers;

use App\Models\concours\Candidat;
use PDF;
use Image;
use App\Models\User;
use App\Models\Quitus;
use App\Models\InfoExtra;
use App\Models\Inscription;
use App\Models\UsersDiplome;
use Illuminate\Http\Request;
use App\Models\FiliereNiveau;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Throwable;
use function App\Helper\get_filiere;
use function App\Helper\update_matricule_pers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class EtudiantController extends Controller
{

    function liste_site_formation(){
        return view("sige_app.backend.etudiant.list_etudiant_ecole");
    }

    public function certificat_index(){
        $fil = 0;
        return view("sige_app.backend.etudiant.certificat", compact("fil"));
    }
    public function carte_index(){
        $fil = 0;
        return view("sige_app.backend.etudiant.carte", compact("fil"));
    }
    function show_candidat_list(){
        $candidats = Candidat::join("serie", "serie.code_serie"."", "candidat.ca_serie_diplome")
            ->where("candidat.filiere_code", "GLTCO")->orderBy("candidat.ca_nom")->get();
        return view("sige_app.backend.etudiant.candidat_list", compact(["candidats"]));
    }

    function search_candidats(Request  $request){
        $candidats = Candidat::join("serie", "serie.code_serie"."", "candidat.ca_serie_diplome")
            ->where("candidat.filiere_code", $request->filiere_code)
            ->where("candidat.code_site", $request->code_site)
            ->orderBy("candidat.ca_nom")->get();
        return view("sige_app.backend.etudiant.candidat_list", compact(["candidats"]));
    }

    function changement_site_save(Request  $request){
        $ecole = $request->ecole_user;
        $selectedUsers = $request->input('selected_users', []);
        if (empty($selectedUsers)) {
            return redirect("liste_site_formation")->with('errors', 'Aucun etudiant sélectionné.');
        }
        try{
            $new_ecole = $ecole ==  "ESTLC"?"ISLAPE": "ESTLC";
            update_matricule_pers($selectedUsers, $new_ecole);
            return redirect("liste_site_formation")->with('success', 'Les étudiants sélectionnés ont été basculés pour le site '. $new_ecole);
        }catch(Throwable $th){
            return redirect("liste_site_formation")->with('errors', 'problème survenu lors du changement de site'.$th);
        }
    }
    function find_candidats(Request  $request){
        $candidats = Candidat::join("serie", "serie.code_serie"."", "candidat.ca_serie_diplome")
            ->where("candidat.ca_nom", "LIKE", "%$request->keyword%")
            ->orWhere("candidat.ca_prenom", "LIKE", "%$request->keyword%")
            ->orWhere("candidat.ca_telephone",  "LIKE","%$request->keyword%")
            ->orderBy("candidat.ca_nom")->get();
        return view("sige_app.backend.etudiant.candidat_list", compact(["candidats"]));
    }

    function find_candidats_site(Request  $request){
        $filiere = $request->filiere;
        $ecole = $request->ecole;
        $annee = $request->annee;
        $level = $request->niveau;
        $users = User::join("inscription", "users.code_user", "inscription.code_user")
                ->join("filiere_niveau", "filiere_niveau.code_ins", "inscription.code_ins")
                ->where("users.code_user", "LIKE", "%$request->keyword%")
                ->orWhere("users.nom_user", "LIKE", "%$request->keyword%")
                ->orWhere("users.first_phone_user",  "LIKE","%$request->keyword%")
                ->orderBy("users.nom_user")
                ->get();
        return view("sige_app.backend.etudiant.list_etudiant_ecole", compact(["users", "filiere", "ecole", "level", "annee"]));
    }

    public function certificat(Request  $request) {
        $selectedUsers = $request->input('selected_users', []);
        if (empty($selectedUsers)) {
            return redirect()->back()->with('errors', 'Aucun etudiant sélectionné.');
        }
        $code_filiere = $request->code_filiere;
        $niveau = $request->niveau;
        $etudiants = collect();
        foreach($selectedUsers as $code){
            $e = User::find($code);
            $etudiants->push($e);
        }
        $pdf = PDF::loadView("sige_app.backend.pdf.certificat_scolarite", compact(["etudiants", "code_filiere", "niveau"]))->setPaper('a4');
        return $pdf->download("CERTIFICAT_".$code_filiere.$niveau.'.pdf');
    }

    public function carte(Request  $request) {
        $selectedUsers = $request->input('selected_users', []);
        if (empty($selectedUsers)) {
            return redirect()->back()->with('errors', 'Aucun etudiant sélectionné.');
        }
        $code_filiere = $request->input("code_filiere");
        $niveau = $request->input("niveau");
        $etudiants = collect();
        foreach($selectedUsers as $code){
            $e = User::find($code);
            $etudiants->push($e);
        }
        $customPaper = array(10,10,595.00,842);
        $pdf = PDF::loadView("sige_app.backend.pdf.carte_scolaire", compact(["etudiants", "code_filiere", "niveau"]))->setPaper($customPaper);
        return $pdf->download("CARTE_".$code_filiere.'.pdf');

    }

    public function valider_paiement_index()
    {

    }

    public function delete_user(Request $request)
    {
        $id = explode("-", $request->code_user)[0];
        $filiere = explode("-", $request->code_user)[1];
        try {
            DB::beginTransaction();
            $user = User::where("code_user", $id);
             $inscription = Inscription::where("code_user", $id);
             FiliereNiveau::where("code_ins",  $inscription->first()->code_ins)->delete();
             //UsersRole::where("code_user",  $id)->delete();
             UsersDiplome::where("code_user", $id)->delete();
             Quitus::where("code_ins", $inscription->first()->code_ins)->delete();
             $inscription->delete();
            $info = $user->first()->code_info_extra;
            $res = $user->delete();
            InfoExtra::where("code_info_extra", $info)->delete();
            DB::commit();
            if ($res){
                $success = "Etudiant supprimé avec success";
                return redirect("/liste_etudiant/{$filiere}")->with(compact(["success", "filiere"]));
            }else{
                $errors = "Echec de suppression de l'étudiant";
                return redirect("/liste_etudiant/{$filiere}")->with(compact(["errors","filiere"]));
            }
        } catch (Throwable $th) {
            $errors = "Echec de suppression de l'étudiant";
            return redirect("/liste_etudiant/{$filiere}")->with(compact(["errors","filiere"]));
        }
    }

    public function search_etudiant(Request $request)
    {
        $filiere = $request->code_filiere;
        $ecole = $request->code_ecole;
        $annee = $request->code_annee;
        $level = $request->level;
        //$inscrit = $request->inscrit;
        $etudiants = User::join("inscription", "users.code_user", "inscription.code_user")
                ->join("filiere_niveau", "filiere_niveau.code_ins", "inscription.code_ins")
                ->where("users.ecole_user", $ecole)
                ->where("inscription.code_annee", (int)$annee)
                //->where("inscription.statut_ins", (int)$inscrit)
                ->where("filiere_niveau.code_filiere", $filiere)
                ->where("filiere_niveau.code_niveau", (int)$level)
                ->orderBy("users.nom_user")
                ->get();
        return view("sige_app.backend.etudiant.liste_etudiant", compact(["etudiants", "filiere", "ecole", "level", "annee"]));
    }

    public function search_etudiant_site(Request $request)
    {
        $filiere = $request->code_filiere;
        $ecole = $request->code_ecole;
        $annee = $request->code_annee;
        $level = $request->code_niveau;
        $users = User::join("inscription", "users.code_user", "inscription.code_user")
                ->join("filiere_niveau", "filiere_niveau.code_ins", "inscription.code_ins")
                ->where("users.ecole_user", $ecole)
                ->where("inscription.code_annee", (int)$annee)
                ->where("filiere_niveau.code_filiere", $filiere)
                ->where("filiere_niveau.code_niveau", (int)$level)
                ->orderBy("users.nom_user")
                ->get();
        return view("sige_app.backend.etudiant.list_etudiant_ecole", compact(["users", "filiere", "ecole", "level", "annee"]));
    }

    /**
     * @throws Throwable
     */
    public function change_filiere(Request $request)
    {
       try {
        $fil = $request->new_filiere;
        DB::beginTransaction();
        $u = User::Where("code_user", $request->code_user)->first();
        //à verifer plutard que l'on est bien inscris à cette année
        $inscription = Inscription::where("code_user", $u->code_user)->first();
        $filiere_niveau = FiliereNiveau::where("code_ins", $inscription->code_ins)->update(["code_filiere"=>$fil]);
        DB::commit();
        $success = "Filière Modifiée avec success";
        return redirect("/update_info/".$u->code_user."-".$fil)->with(compact(["u", "fil", "success"]));
       } catch (Throwable $th) {
        DB::rollback();
            return redirect()->back();
       }
    }


    public function change_info_pers(Request $request)
    {
       try {
        $fil = get_filiere($request->code_user);
        $u = User::firstWhere("code_user", $request->code_user);
        $res = User::firstWhere("code_user", $request->code_user)->update($request->all());
        if($res){
            $success = "Informations Modifiées avec success";
            return redirect("/update_info/".$u->code_user."-".$fil)->with(compact(["u", "fil", "success"]));
        }else{
            $errors= "Echec de mis à jour";
            return redirect("/update_info/".$u->code_user."-".$fil)->with(compact(["u", "fil", "errors"]));
        }
       } catch (Throwable $th) {
        return redirect()->back();
       }
    }

    public function change_photo(Request $request)
    {
       try {
        $fil = get_filiere($request->code_user);
        $u = User::firstWhere("code_user", $request->code_user);
        $image_extension = ["png", "jpg", "gif", "bmp"];
        $path = public_path("cartes/");
        $pictfile = $request->file('photo_user');
        $nom = $u->code_user.".".$pictfile->extension();
        if (!File::exists($path)) {
            File::makeDirectory($path,  0775, true);
        }
        if (($pictfile != null) && (in_array($pictfile->extension(), $image_extension))) {
            $pictfile->move($path, $nom);
            $file = public_path().DIRECTORY_SEPARATOR."cartes".DIRECTORY_SEPARATOR.$nom;
            $img = Image::make($file)->orientate()->fit(152, 145, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($file);
        }
        $res = User::firstWhere("code_user", $request->code_user)->update(["photo_user" => $nom]);
        if($res){
            $success = "Photo Modifiée avec success";
            return redirect()->route("liste_etudiant",['id'=>0]);
        }else{
            $errors= "Echec de mis à jour";
            return redirect("/update_info/".$u->code_user."-".$fil)->with(compact(["u", "fil", "errors"]));
        }
       } catch (Throwable $th) {
        return redirect()->back();
       }
    }

    public function change_info_sup(Request $request)
    {
       try {
        $u = User::where("code_info_extra", $request->code_info_extra)->first();
        $fil = get_filiere($u->code_user);
        $res = InfoExtra::where("code_info_extra", $request->code_info_extra)->update($request->except('_token'));
        if($res){
            $success = "Informations Modifiées avec success";
            return redirect("/update_info/".$u->code_user."-".$fil)->with(compact(["u", "fil", "success"]));
        }else{
            $errors= "Echec de mis à jour";
            return redirect("/update_info/".$u->code_user."-".$fil)->with(compact(["u", "fil", "errors"]));
        }
       } catch (Throwable $th) {
        $errors= "Echec de mis à jour" .$th;
        return redirect()->back()->with(compact(["errors"]));
       }
    }

    public function change_pwd(Request $request)
    {
        try {
            $fil = get_filiere($request->code_user);
            $u = User::firstWhere("code_user", $request->code_user);
            $res = User::where("code_user", $request->code_user)->update([
                "pwd_user"  => md5($request->pwd_user)
            ]);
            if($res){
                $success = "Mot de passe modifié avec success";
                return redirect("/update_info/".$request->code_user."-".$fil)->with(compact(["u", "fil", "success"]));
            }else{
                $errors= "Echec de la mise à jour";
                return redirect("/update_info/".$request->code_user."-".$fil)->with(compact(["u", "fil", "errors"]));
            }
        } catch (Throwable $th) {
            return redirect()->back();
        }
    }

    public function change_pwd_first(Request $request)
    {
        try {
            $u = User::firstWhere("code_user", $request->code_user);
            if ($request->code_user == $request->npwd) {
                $errors= "Echec de mise à jour. Le nouveau mot de passe doit être différent de l'ancien";
                return redirect("/")->with(compact(["errors"]));
            } else {
                $res = User::where("code_user", $request->code_user)->update([
                    "pwd_user"  => md5($request->npwd)
                ]);
                if($res){
                    $success = "Mot de passe modifié avec success";
                    return redirect("/")->with(compact(["success"]));
                }else{
                    $errors= "Echec de la mise à jour";
                    return redirect("/")->with(compact(["errors"]));
                }
            }
        }catch(Throwable $th){
            $errors= "Erreur interne. Bien vouloir ";
            return redirect("/")->with(compact(["errors"]));
        }
    }

    public function update_info($id)
    {
        $code = explode("-", $id)[0];
        $fil = explode("-", $id)[1];
        $u = User::where("code_user", $code)->first();
        return view("sige_app.backend.etudiant.update_etudiant", compact(["u", "fil"]));
    }



    public function show($id)
    {
        $fil =  $id;
        $etudiants = User::join("inscription", "users.code_user", "inscription.code_user")
                ->join("filiere_niveau", "inscription.code_ins", "=", 'filiere_niveau.code_ins')
                ->where("filiere_niveau.code_filiere", $fil)->orderBy("nom_user")->paginate(100);
        return view("sige_app.backend.etudiant.liste_etudiant", compact(["etudiants", "fil"]));
    }
}
