<?php

namespace App\Http\Controllers;

use App\Models\Grille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Throwable;

class GrilleController extends Controller
{
    public function index()
    {
        return view("sige_app.backend.grille.gestion_grille");
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $sem = Grille::create($request->all());
            DB::commit();
            if($sem != null){
                $success = "Semestre créer avec success";
                return view("sige_app.backend.grille.gestion_grille",compact("success"));
            }
            return redirect()->back()->withErrors("Echec de création de la grille")->withInput();
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de création de la grille")->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $sem = Grille::destroy($id);
            DB::commit();
            return redirect()->back();
        } catch (Throwable $th) {
            dd($th);
        }
    }
}
