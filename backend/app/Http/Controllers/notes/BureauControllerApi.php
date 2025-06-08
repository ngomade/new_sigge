<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Bureau;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BureauControllerApi extends Controller
{
    public function index()
    {
        $bureaus = Bureau::all();
        return response()->json($bureaus);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_bureau' => 'required|string|max:128|unique:bureau,code_bureau',
            'label_bureau' => 'required|string|max:128',
            'desc_bureau' => 'sometimes|nullable|string',
            'type_bureau' => 'required|string|max:128',
        ]);

        try {
            DB::beginTransaction();
            $bureau = Bureau::create($validatedData);
            DB::commit();
            return response()->json($bureau);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Bureau: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Bureau'], 500);
        }
    }

    public function show(string $code_bureau)
    {
        $bureau = Bureau::findOrFail($code_bureau);
        return response()->json($bureau);
    }

    public function update(Request $request, string $code_bureau)
    {
        $validatedData = $request->validate([
            'code_bureau' => 'sometimes|string|max:128|unique:bureau,code_bureau,' . $code_bureau . ',code_bureau',
            'label_bureau' => 'sometimes|string|max:128',
            'desc_bureau' => 'sometimes|nullable|string',
            'type_bureau' => 'sometimes|string|max:128',
        ]);

        $bureau = Bureau::findOrFail($code_bureau);

        try {
            DB::beginTransaction();
            $bureau->update($validatedData);
            DB::commit();
            return response()->json($bureau);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Bureau: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Bureau'], 500);
        }
    }

    public function destroy(string $code_bureau)
    {
        $bureau = Bureau::findOrFail($code_bureau);

        try {
            DB::beginTransaction();
            $bureau->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Bureau: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Bureau'], 500);
        }
    }
}
