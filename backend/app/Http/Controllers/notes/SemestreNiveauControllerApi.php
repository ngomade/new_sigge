<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\SemestreNiveau;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SemestreNiveauControllerApi extends Controller
{
    public function index()
    {
        $semestreNiveaux = SemestreNiveau::all();
        return response()->json($semestreNiveaux);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_niveau' => 'required|string|exists:niveau,code_niveau',
            'code_sem' => 'required|string|exists:semestre,code_sem',
        ]);

        try {
            DB::beginTransaction();
            $semestreNiveau = SemestreNiveau::create($validatedData);
            DB::commit();
            return response()->json($semestreNiveau);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating SemestreNiveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating SemestreNiveau'], 500);
        }
    }

    public function show(string $code_niveau, string $code_sem)
    {
        $semestreNiveau = SemestreNiveau::where('code_niveau', $code_niveau)
            ->where('code_sem', $code_sem)
            ->firstOrFail();
        return response()->json($semestreNiveau);
    }

    public function update(Request $request, string $code_niveau, string $code_sem)
    {
        $validatedData = $request->validate([
            'code_niveau' => 'sometimes|string|exists:niveau,code_niveau',
            'code_sem' => 'sometimes|string|exists:semestre,code_sem',
        ]);

        $semestreNiveau = SemestreNiveau::where('code_niveau', $code_niveau)
            ->where('code_sem', $code_sem)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $semestreNiveau->update($validatedData);
            DB::commit();
            return response()->json($semestreNiveau);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating SemestreNiveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating SemestreNiveau'], 500);
        }
    }

    public function destroy(string $code_niveau, string $code_sem)
    {
        $semestreNiveau = SemestreNiveau::where('code_niveau', $code_niveau)
            ->where('code_sem', $code_sem)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $semestreNiveau->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting SemestreNiveau: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting SemestreNiveau'], 500);
        }
    }
}
