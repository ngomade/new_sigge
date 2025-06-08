<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Ue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UeControllerApi extends Controller
{
    public function index()
    {
        $ues = Ue::all();
        return response()->json($ues);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_ue' => 'required|string|max:32|unique:ue,code_ue',
            'code_sem' => 'required|string|max:10|exists:semestre,code_sem',
            'intitule_ue' => 'required|string|max:128',
            'desc_ue' => 'sometimes|nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $ue = Ue::create($validatedData);
            DB::commit();
            return response()->json($ue);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Ue: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Ue'], 500);
        }
    }

    public function show(string $code_ue)
    {
        $ue = Ue::findOrFail($code_ue);
        return response()->json($ue);
    }

    public function update(Request $request, string $code_ue)
    {
        $validatedData = $request->validate([
            'code_ue' => 'sometimes|string|max:32|unique:ue,code_ue,' . $code_ue . ',code_ue',
            'code_sem' => 'sometimes|string|max:10|exists:semestre,code_sem',
            'intitule_ue' => 'sometimes|string|max:128',
            'desc_ue' => 'sometimes|nullable|string',
        ]);

        $ue = Ue::findOrFail($code_ue);

        try {
            DB::beginTransaction();
            $ue->update($validatedData);
            DB::commit();
            return response()->json($ue);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Ue: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Ue'], 500);
        }
    }

    public function destroy(string $code_ue)
    {
        $ue = Ue::findOrFail($code_ue);

        try {
            DB::beginTransaction();
            $ue->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Ue: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Ue'], 500);
        }
    }
}
