<?php

namespace App\Http\Controllers;

use App\Models\notes\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Throwable;

class SemestreController extends Controller
{
    public function index()
    {
        return view("sige_app.backend.semestre.gestion_semestre");
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $sem = Semestre::create(array_merge($request->all(),["code_sem"=>$this->generateCode()]));
            DB::commit();
            if($sem != null){
                $success = "Semestre créer avec success";
                $request->session()->flash('success', $success);
                return view("sige_app.backend.semestre.gestion_semestre");
            }
            return redirect()->back()->withErrors("Echec de création du semestre")->withInput();
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de création du semestre".$th)->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $sem = Semestre::destroy($id);
            DB::commit();
            return redirect()->route("gestion_semestre");
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de supression du semestre".$th)->withInput();
        }
    }

    function generateCode(){
        $count = Semestre::count()+1;
        $code = "S".$count;
        if(Semestre::where("code_sem", $code)->count() >0)
            return $this->generateCode();
        return $code;
    }
}
