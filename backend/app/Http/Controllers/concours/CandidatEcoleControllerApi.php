<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CandidatEcole;
use Illuminate\Support\Facades\DB;
use Throwable;

class CandidatEcoleControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidats = CandidatEcole::all();
        return response()->json($candidats, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'ca_code' => 'required|string|exists:candidat,ca_code',
            'code_ecole' => 'required|string|exists:ecole,code_ecole',
        ]);
        try {
            DB::beginTransaction();
            $candidatEcole = CandidatEcole::create($validateData);
            DB::commit();
            return response()->json($candidatEcole, 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de l\'enregistrement du candidat ecole'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $candidatEcole = CandidatEcole::find($id);
        if (!$candidatEcole) {
            return response()->json(['erreur' => 'candidat ecole non trouvé'], 404);
        }
        return response()->json($candidatEcole, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'ca_code' => 'required|string|exists:candidat,ca_code',
            'code_ecole' => 'required|string|exists:ecole,code_ecole',
        ]);
        try {
            DB::beginTransaction();
            $candidatEcole = CandidatEcole::findOrFail($id);
            $candidatEcole->update($validateData);
            DB::commit();
            return response()->json($candidatEcole, 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de la mise à jour du candidat ecole'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $candidatEcole = CandidatEcole::findOrFail($id);
            $candidatEcole->delete();
            DB::commit();
            return response()->json(['succes' => 'candidat ecole supprimé'], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de la suppression du candidat ecole'], 500);
        }
    }
}
