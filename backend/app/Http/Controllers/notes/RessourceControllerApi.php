<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Ressource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RessourceControllerApi extends Controller
{
    public function index()
    {
        $ressources = Ressource::all();
        return response()->json($ressources);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label_res' => 'required|string',
            'code_ec' => 'sometimes|nullable|string',
            'type_res' => 'required|string',
            'desc_res' => 'sometimes|nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $ressource = Ressource::create($validatedData);
            DB::commit();
            return response()->json($ressource);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Ressource: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Ressource'], 500);
        }
    }

    public function show(int $code_res)
    {
        $ressource = Ressource::findOrFail($code_res);
        return response()->json($ressource);
    }

    public function update(Request $request, int $code_res)
    {
        $validatedData = $request->validate([
            'label_res' => 'sometimes|string',
            'code_ec' => 'sometimes|nullable|string',
            'type_res' => 'sometimes|string',
            'desc_res' => 'sometimes|nullable|string',
        ]);

        $ressource = Ressource::findOrFail($code_res);

        try {
            DB::beginTransaction();
            $ressource->update($validatedData);
            DB::commit();
            return response()->json($ressource);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Ressource: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Ressource'], 500);
        }
    }

    public function destroy(int $code_res)
    {
        $ressource = Ressource::findOrFail($code_res);

        try {
            DB::beginTransaction();
            $ressource->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Ressource: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Ressource'], 500);
        }
    }
}
