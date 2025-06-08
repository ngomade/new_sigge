<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Anneescolaire;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnneescolaireControllerApi extends Controller
{
    public function index()
    {
        $anneescolaires = Anneescolaire::all();
        return response()->json($anneescolaires);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_annee' => 'required|integer|unique:anneescolaire,code_annee',
            'debut_annee' => 'required|date',
            'fin_annee' => 'required|date',
        ]);

        try {
            DB::beginTransaction();
            $anneescolaire = Anneescolaire::create($validatedData);
            DB::commit();
            return response()->json($anneescolaire);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Anneescolaire: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Anneescolaire'], 500);
        }
    }

    public function show(int $code_annee)
    {
        $anneescolaire = Anneescolaire::findOrFail($code_annee);
        return response()->json($anneescolaire);
    }

    public function update(Request $request, int $code_annee)
    {
        $validatedData = $request->validate([
            'code_annee' => 'sometimes|integer|unique:anneescolaire,code_annee,' . $code_annee . ',code_annee',
            'debut_annee' => 'sometimes|date',
            'fin_annee' => 'sometimes|date',
        ]);

        $anneescolaire = Anneescolaire::findOrFail($code_annee);

        try {
            DB::beginTransaction();
            $anneescolaire->update($validatedData);
            DB::commit();
            return response()->json($anneescolaire);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Anneescolaire: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Anneescolaire'], 500);
        }
    }

    public function destroy(int $code_annee)
    {
        $anneescolaire = Anneescolaire::findOrFail($code_annee);

        try {
            DB::beginTransaction();
            $anneescolaire->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Anneescolaire: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Anneescolaire'], 500);
        }
    }
}
