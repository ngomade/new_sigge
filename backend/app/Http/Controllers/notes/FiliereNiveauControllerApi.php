<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\FiliereNiveau;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class FiliereNiveauControllerApi extends Controller
{
    public function index()
    {
        $filiereNiveaux = FiliereNiveau::all();
        return response()->json($filiereNiveaux);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'filiere_code' => 'required|string|max:32|exists:filiere,filiere_code',
            'code_niveau' => 'required|string|max:32|exists:niveau,code_niveau',
            'code_ins' => 'required|string|max:32|exists:inscription,code_ins',
        ]);

        try {
            DB::beginTransaction();
            $filiereNiveau = FiliereNiveau::create($validatedData);
            DB::commit();
            return response()->json($filiereNiveau);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating FiliereNiveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating FiliereNiveau'], 500);
        }
    }

    public function show(string $filiere_code, string $code_niveau, string $code_ins)
    {
        $filiereNiveau = FiliereNiveau::where('filiere_code', $filiere_code)
            ->where('code_niveau', $code_niveau)
            ->where('code_ins', $code_ins)
            ->firstOrFail();
        return response()->json($filiereNiveau);
    }

    public function update(Request $request, string $filiere_code, string $code_niveau, string $code_ins)
    {
        $validatedData = $request->validate([
            'filiere_code' => 'sometimes|string|max:32|exists:filiere,filiere_code',
            'code_niveau' => 'sometimes|string|max:32|exists:niveau,code_niveau',
            'code_ins' => 'sometimes|string|max:32|exists:inscription,code_ins',
        ]);

        $filiereNiveau = FiliereNiveau::where('filiere_code', $filiere_code)
            ->where('code_niveau', $code_niveau)
            ->where('code_ins', $code_ins)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $filiereNiveau->update($validatedData);
            DB::commit();
            return response()->json($filiereNiveau);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating FiliereNiveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating FiliereNiveau'], 500);
        }
    }

    public function destroy(string $filiere_code, string $code_niveau, string $code_ins)
    {
        $filiereNiveau = FiliereNiveau::where('filiere_code', $filiere_code)
            ->where('code_niveau', $code_niveau)
            ->where('code_ins', $code_ins)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $filiereNiveau->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting FiliereNiveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting FiliereNiveau'], 500);
        }
    }
}
