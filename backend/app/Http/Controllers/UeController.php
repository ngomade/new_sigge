<?php

namespace App\Http\Controllers;

use App\Models\notes\Semestre;
use App\Models\notes\Ue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Throwable;

class UeController extends Controller
{
    public function index()
    {
        $semestres = Semestre::with('ues')->get();

        // dd($semestres);
        return view('sige_app.backend.ues.gestion_ues', ['semestres' => $semestres]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $sem = Ue::create($request->all());
            DB::commit();
            if ($sem != null) {
                $success = 'UE créer avec success';
                $request->session()->flash('success', $success);

                return redirect()->route('gestion_ue');
            }

            return redirect()->back()->withErrors("Echec de création de l'UE")->withInput();
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de création de l'UE")->withInput();
        }
    }

    public function destroy($id)
    {

        try {
            DB::beginTransaction();
            $ue = Ue::destroy($id);
            DB::commit();
            Session::flash('success', 'UE Supprimée avec success');

            return redirect()->route('gestion_ue');
        } catch (Throwable $th) {
            return redirect()->route('gestion_ue')->withErrors('Echec de suppression');
        }
    }
}
