<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Assignation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AssignationControllerApi extends Controller
{
    public function index()
    {
        $assignations = Assignation::all();
        return response()->json($assignations);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_ec' => 'required|string|max:32|exists:ec,code_ec',
            'code_pers' => 'required|string|max:32|exists:personnel,code_pers',
            'code_class' => 'required|string|exists:classes,code_class',
        ]);

        try {
            DB::beginTransaction();
            $assignation = Assignation::create($validatedData);
            DB::commit();
            return response()->json($assignation);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Assignation: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Assignation'], 500);
        }
    }

    public function show(int $code_ass)
    {
        $assignation = Assignation::findOrFail($code_ass);
        return response()->json($assignation);
    }

    public function update(Request $request, int $code_ass)
    {
        $validatedData = $request->validate([
            'code_ec' => 'sometimes|string|max:32|exists:ec,code_ec',
            'code_pers' => 'sometimes|string|max:32|exists:personnel,code_pers',
            'code_class' => 'sometimes|string|exists:classes,code_class',
        ]);

        $assignation = Assignation::findOrFail($code_ass);

        try {
            DB::beginTransaction();
            $assignation->update($validatedData);
            DB::commit();
            return response()->json($assignation);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Assignation: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Assignation'], 500);
        }
    }

    public function destroy(int $code_ass)
    {
        $assignation = Assignation::findOrFail($code_ass);

        try {
            DB::beginTransaction();
            $assignation->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Assignation: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Assignation'], 500);
        }
    }
}
