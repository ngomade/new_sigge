<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Inscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InscriptionControllerApi extends Controller
{
    public function index()
    {
        $inscriptions = Inscription::all();
        return response()->json($inscriptions);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_ins' => 'required|string|max:32|unique:inscription,code_ins',
            'code_user' => 'required|string|max:32|exists:user,code_user',
            'code_annee' => 'required|integer|exists:anneescolaire,code_annee',
            'date_ins' => 'required|date',
            'statut_ins' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();
            $inscription = Inscription::create($validatedData);
            DB::commit();
            return response()->json($inscription);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Inscription: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Inscription'], 500);
        }
    }

    public function show(string $code_ins)
    {
        $inscription = Inscription::findOrFail($code_ins);
        return response()->json($inscription);
    }

    public function update(Request $request, string $code_ins)
    {
        $validatedData = $request->validate([
            'code_ins' => 'sometimes|string|max:32|unique:inscription,code_ins,' . $code_ins . ',code_ins',
            'code_user' => 'sometimes|string|max:32|exists:user,code_user',
            'code_annee' => 'sometimes|integer|exists:anneescolaire,code_annee',
            'date_ins' => 'sometimes|date',
            'statut_ins' => 'sometimes|integer',
        ]);

        $inscription = Inscription::findOrFail($code_ins);

        try {
            DB::beginTransaction();
            $inscription->update($validatedData);
            DB::commit();
            return response()->json($inscription);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Inscription: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Inscription'], 500);
        }
    }

    public function destroy(string $code_ins)
    {
        $inscription = Inscription::findOrFail($code_ins);

        try {
            DB::beginTransaction();
            $inscription->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Inscription: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Inscription'], 500);
        }
    }
}
