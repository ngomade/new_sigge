<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Periode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PeriodeControllerApi extends Controller
{
    public function index()
    {
        $periodes = Periode::all();
        return response()->json($periodes);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_salle' => 'required|string|max:32|exists:salle,code_salle',
            'code_ec' => 'required|string|max:32|exists:ec,code_ec',
            'code_periode' => 'sometimes|nullable|integer',
            'debut_periode' => 'required|date',
            'jour_periode' => 'required|integer',
            'fin_periode' => 'required|date',
            'duree_periode' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();
            $periode = Periode::create($validatedData);
            DB::commit();
            return response()->json($periode);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Periode: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Periode'], 500);
        }
    }

    public function show(string $code_salle, string $code_ec)
    {
        $periode = Periode::where('code_salle', $code_salle)
            ->where('code_ec', $code_ec)
            ->firstOrFail();
        return response()->json($periode);
    }

    public function update(Request $request, string $code_salle, string $code_ec)
    {
        $validatedData = $request->validate([
            'code_salle' => 'sometimes|string|max:32|exists:salle,code_salle',
            'code_ec' => 'sometimes|string|max:32|exists:ec,code_ec',
            'code_periode' => 'sometimes|nullable|integer',
            'debut_periode' => 'sometimes|date',
            'jour_periode' => 'sometimes|integer',
            'fin_periode' => 'sometimes|date',
            'duree_periode' => 'sometimes|integer',
        ]);

        $periode = Periode::where('code_salle', $code_salle)
            ->where('code_ec', $code_ec)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $periode->update($validatedData);
            DB::commit();
            return response()->json($periode);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Periode: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Periode'], 500);
        }
    }

    public function destroy(string $code_salle, string $code_ec)
    {
        $periode = Periode::where('code_salle', $code_salle)
            ->where('code_ec', $code_ec)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $periode->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Periode: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Periode'], 500);
        }
    }
}
