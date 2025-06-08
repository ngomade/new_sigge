<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Semestre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SemestreControllerApi extends Controller
{
    public function index()
    {
        $semestres = Semestre::all();
        return response()->json($semestres);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_sem' => 'required|string|max:10|unique:semestre,code_sem',
            'label_sem' => 'sometimes|nullable|string|max:128',
        ]);

        try {
            DB::beginTransaction();
            $semestre = Semestre::create($validatedData);
            DB::commit();
            return response()->json($semestre);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Semestre: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Semestre'], 500);
        }
    }

    public function show(string $code_sem)
    {
        $semestre = Semestre::findOrFail($code_sem);
        return response()->json($semestre);
    }

    public function update(Request $request, string $code_sem)
    {
        $validatedData = $request->validate([
            'code_sem' => 'sometimes|string|max:10|unique:semestre,code_sem,' . $code_sem . ',code_sem',
            'label_sem' => 'sometimes|nullable|string|max:128',
        ]);

        $semestre = Semestre::findOrFail($code_sem);

        try {
            DB::beginTransaction();
            $semestre->update($validatedData);
            DB::commit();
            return response()->json($semestre);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Semestre: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Semestre'], 500);
        }
    }

    public function destroy(string $code_sem)
    {
        $semestre = Semestre::findOrFail($code_sem);

        try {
            DB::beginTransaction();
            $semestre->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Semestre: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Semestre'], 500);
        }
    }
}
