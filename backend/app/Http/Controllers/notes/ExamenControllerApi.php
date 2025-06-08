<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Examen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExamenControllerApi extends Controller
{
    public function index()
    {
        $examens = Examen::all();
        return response()->json($examens);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_examen' => 'required|string|max:32|unique:examen,code_examen',
            'code_session' => 'required|string|max:32|exists:session_examen,code_session',
            'type_evaluation' => 'required|string|max:32',
        ]);

        try {
            DB::beginTransaction();
            $examen = Examen::create($validatedData);
            DB::commit();
            return response()->json($examen);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Examen: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Examen'], 500);
        }
    }

    public function show(string $code_examen)
    {
        $examen = Examen::findOrFail($code_examen);
        return response()->json($examen);
    }

    public function update(Request $request, string $code_examen)
    {
        $validatedData = $request->validate([
            'code_examen' => 'sometimes|string|max:32|unique:examen,code_examen,' . $code_examen . ',code_examen',
            'code_session' => 'sometimes|string|max:32|exists:session_examen,code_session',
            'type_evaluation' => 'sometimes|string|max:32',
        ]);

        $examen = Examen::findOrFail($code_examen);

        try {
            DB::beginTransaction();
            $examen->update($validatedData);
            DB::commit();
            return response()->json($examen);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Examen: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Examen'], 500);
        }
    }

    public function destroy(string $code_examen)
    {
        $examen = Examen::findOrFail($code_examen);

        try {
            DB::beginTransaction();
            $examen->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Examen: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Examen'], 500);
        }
    }
}
