<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Salle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SalleControllerApi extends Controller
{
    public function index()
    {
        $salles = Salle::all();
        return response()->json($salles);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_salle' => 'required|string|max:32|unique:salle,code_salle',
            'nb_place_salle' => 'required|integer',
            'etat_salle' => 'required|boolean',
            'desc_salle' => 'sometimes|nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $salle = Salle::create($validatedData);
            DB::commit();
            return response()->json($salle);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Salle: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Salle'], 500);
        }
    }

    public function show(string $code_salle)
    {
        $salle = Salle::findOrFail($code_salle);
        return response()->json($salle);
    }

    public function update(Request $request, string $code_salle)
    {
        $validatedData = $request->validate([
            'code_salle' => 'sometimes|string|max:32|unique:salle,code_salle,' . $code_salle . ',code_salle',
            'nb_place_salle' => 'sometimes|integer',
            'etat_salle' => 'sometimes|boolean',
            'desc_salle' => 'sometimes|nullable|string',
        ]);

        $salle = Salle::findOrFail($code_salle);

        try {
            DB::beginTransaction();
            $salle->update($validatedData);
            DB::commit();
            return response()->json($salle);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Salle: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Salle'], 500);
        }
    }

    public function destroy(string $code_salle)
    {
        $salle = Salle::findOrFail($code_salle);

        try {
            DB::beginTransaction();
            $salle->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Salle: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Salle'], 500);
        }
    }
}
