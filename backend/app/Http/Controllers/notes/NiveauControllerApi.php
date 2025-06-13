<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class NiveauControllerApi extends Controller
{
    public function index()
    {
        $niveaux = Niveau::all();
        return response()->json($niveaux);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label_niveau' => 'sometimes|nullable|string',
            'code_class' => 'required|string|exists:classes,code_class',
        ]);

        try {
            DB::beginTransaction();
            $niveau = Niveau::create($validatedData);
            DB::commit();
            return response()->json($niveau);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Niveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Niveau'], 500);
        }
    }

    public function show(string $code_niveau)
    {
        $niveau = Niveau::findOrFail($code_niveau);
        return response()->json($niveau);
    }

    public function update(Request $request, string $code_niveau)
    {
        $validatedData = $request->validate([
            'label_niveau' => 'sometimes|nullable|string',
            'code_class' => 'sometimes|string|exists:classes,code_class',
        ]);

        $niveau = Niveau::findOrFail($code_niveau);

        try {
            DB::beginTransaction();
            $niveau->update($validatedData);
            DB::commit();
            return response()->json($niveau);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Niveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Niveau'], 500);
        }
    }

    public function destroy(string $code_niveau)
    {
        $niveau = Niveau::findOrFail($code_niveau);

        try {
            DB::beginTransaction();
            $niveau->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Niveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Niveau'], 500);
        }
    }
}
