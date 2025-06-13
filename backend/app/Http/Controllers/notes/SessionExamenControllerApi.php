<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\SessionExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SessionExamenControllerApi extends Controller
{
    public function index()
    {
        $sessionExamens = SessionExamen::all();
        return response()->json($sessionExamens);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_session' => 'required|string|max:32|unique:session_examen,code_session',
            'code_annee' => 'required|integer|exists:anneescolaire,code_annee',
            'label_session' => 'required|string|max:128',
            'date_debut_session' => 'required|date',
            'date_fin_session' => 'sometimes|nullable|date',
            'statut_session' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();
            $sessionExamen = SessionExamen::create($validatedData);
            DB::commit();
            return response()->json($sessionExamen);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating SessionExamen: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating SessionExamen'], 500);
        }
    }

    public function show(string $code_session)
    {
        $sessionExamen = SessionExamen::findOrFail($code_session);
        return response()->json($sessionExamen);
    }

    public function update(Request $request, string $code_session)
    {
        $validatedData = $request->validate([
            'code_session' => 'sometimes|string|max:32|unique:session_examen,code_session,' . $code_session . ',code_session',
            'code_annee' => 'sometimes|integer|exists:anneescolaire,code_annee',
            'label_session' => 'sometimes|string|max:128',
            'date_debut_session' => 'sometimes|date',
            'date_fin_session' => 'sometimes|nullable|date',
            'statut_session' => 'sometimes|integer',
        ]);

        $sessionExamen = SessionExamen::findOrFail($code_session);

        try {
            DB::beginTransaction();
            $sessionExamen->update($validatedData);
            DB::commit();
            return response()->json($sessionExamen);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating SessionExamen: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating SessionExamen'], 500);
        }
    }

    public function destroy(string $code_session)
    {
        $sessionExamen = SessionExamen::findOrFail($code_session);

        try {
            DB::beginTransaction();
            $sessionExamen->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting SessionExamen: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting SessionExamen'], 500);
        }
    }
}
