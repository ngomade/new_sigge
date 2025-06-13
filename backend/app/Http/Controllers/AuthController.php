<?php

namespace App\Http\Controllers;

use App\Models\FiliereNiveau;
use App\Models\Inscription;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function index()
    {
        Session::flush();
        $success = "Vous êtes désormais déconnecté";
        Session::flash('success', $success);
        return redirect("/");
        //return view("sige_app.frontend.index");
    }


    public function store(Request $request)
    {
        $user = User::where("login_user",$request->login_user)
            ->where("pwd_user", md5($request->pwd_user))->first();
        if($user != null){
            $new_password = $request->login_user == $request->pwd_user;
            $success = "Vous êtes désormais connecté.";
            $request->session()->flash('success', $success);
            $request->session()->put('user', $user);
            $ins = Inscription::join("filiere_niveau", "filiere_niveau.code_ins", "inscription.code_ins")
                ->where("code_user", $user->code_user)
                ->orderBy("date_ins", "desc")
                ->first();
            $filiere = FiliereNiveau::where("code_ins", $ins->code_ins)->first();
            $request->session()->put('filiere', $filiere);
            $request->session()->put('inscription', $ins);
            if($new_password){
                return redirect("/")->with(compact(["success", "new_password"]));
            }
            return redirect("/")->with(compact(["success"]));
        }else{
            $personnel = Personnel::where("login_pers",$request->login_user)
                ->where("pwd_pers", md5($request->pwd_user))->first();
            if($personnel != null){
                $success = "Vous êtes désormais connecté.";
                $request->session()->flash('success', $success);
                $request->session()->put('pers', $personnel);
                return  view("sige_app.backend.index", compact("success"));
            }else{
                $errors = "Echec de connexion. Vérifier vos informations de connexion";
                $request->session()->flash('errors', $errors);
                return redirect()->back()->withInput();
            }
        }
    }
}
