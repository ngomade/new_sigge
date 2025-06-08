<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Classe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClasseControllerApi extends Controller
{
    public function index()
    {
        $classes = Classe::all();
        return response()->json($classes);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_class' => 'required|string|max:32|unique:classes,code_class',
            'label_class' => 'required|string|max:100',
            'code_user' => 'required|string|max:32|exists:user,code_user',
        ]);

        try {
            DB::beginTransaction();
            $classe = Classe::create($validatedData);
            DB::commit();
            return response()->json($classe);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Classe: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Classe'], 500);
        }
    }

    public function show(string $code_class)
    {
        $classe = Classe::findOrFail($code_class);
        return response()->json($classe);
    }

    public function update(Request $request, string $code_class)
    {
        $validatedData = $request->validate([
            'code_class' => 'sometimes|string|max:32|unique:classes,code_class,' . $code_class . ',code_class',
            'label_class' => 'sometimes|string|max:100',
            'code_user' => 'sometimes|string|max:32|exists:user,code_user',
        ]);

        $classe = Classe::findOrFail($code_class);

        try {
            DB::beginTransaction();
            $classe->update($validatedData);
            DB::commit();
            return response()->json($classe);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Classe: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Classe'], 500);
        }
    }

    public function destroy(string $code_class)
    {
        $classe = Classe::findOrFail($code_class);

        try {
            DB::beginTransaction();
            $classe->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Classe: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Classe'], 500);
        }
    }
}
