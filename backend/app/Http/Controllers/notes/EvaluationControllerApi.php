<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Evaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EvaluationControllerApi extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::all();
        return response()->json($evaluations);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_ec' => 'required|string|max:32|exists:ec,code_ec',
            'code_examen' => 'required|string|max:32|exists:examen,code_examen',
            'code_user' => 'required|string|max:32|exists:user,code_user',
            'date_evaluation' => 'required|date',
            'code_ano' => 'sometimes|nullable|string|max:32',
            'note_eval' => 'required|numeric',
            'date_evalu' => 'required|date',
        ]);

        try {
            DB::beginTransaction();
            $evaluation = Evaluation::create($validatedData);
            DB::commit();
            return response()->json($evaluation);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Evaluation: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Evaluation'], 500);
        }
    }

    public function show(string $code_ec, string $code_examen, string $code_user)
    {
        $evaluation = Evaluation::where('code_ec', $code_ec)
            ->where('code_examen', $code_examen)
            ->where('code_user', $code_user)
            ->firstOrFail();
        return response()->json($evaluation);
    }

    public function update(Request $request, string $code_ec, string $code_examen, string $code_user)
    {
        $validatedData = $request->validate([
            'code_ec' => 'sometimes|string|max:32|exists:ec,code_ec',
            'code_examen' => 'sometimes|string|max:32|exists:examen,code_examen',
            'code_user' => 'sometimes|string|max:32|exists:user,code_user',
            'date_evaluation' => 'sometimes|date',
            'code_ano' => 'sometimes|nullable|string|max:32',
            'note_eval' => 'sometimes|numeric',
            'date_evalu' => 'sometimes|date',
        ]);

        $evaluation = Evaluation::where('code_ec', $code_ec)
            ->where('code_examen', $code_examen)
            ->where('code_user', $code_user)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $evaluation->update($validatedData);
            DB::commit();
            return response()->json($evaluation);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Evaluation: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Evaluation'], 500);
        }
    }

    public function destroy(string $code_ec, string $code_examen, string $code_user)
    {
        $evaluation = Evaluation::where('code_ec', $code_ec)
            ->where('code_examen', $code_examen)
            ->where('code_user', $code_user)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $evaluation->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Evaluation: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Evaluation'], 500);
        }
    }
}
