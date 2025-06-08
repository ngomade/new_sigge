<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\EcRessource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EcRessourceControllerApi extends Controller
{
    public function index()
    {
        $ecRessources = EcRessource::all();
        return response()->json($ecRessources);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_ec' => 'required|string|max:32|exists:ec,code_ec',
            'code_res' => 'required|integer|exists:ressource,code_res',
            'code_pers' => 'required|string|max:32|exists:personnel,code_pers',
        ]);

        try {
            DB::beginTransaction();
            $ecRessource = EcRessource::create($validatedData);
            DB::commit();
            return response()->json($ecRessource);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating EcRessource: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating EcRessource'], 500);
        }
    }

    public function show(string $code_ec, int $code_res, string $code_pers)
    {
        $ecRessource = EcRessource::where('code_ec', $code_ec)
            ->where('code_res', $code_res)
            ->where('code_pers', $code_pers)
            ->firstOrFail();
        return response()->json($ecRessource);
    }

    public function update(Request $request, string $code_ec, int $code_res, string $code_pers)
    {
        $validatedData = $request->validate([
            'code_ec' => 'sometimes|string|max:32|exists:ec,code_ec',
            'code_res' => 'sometimes|integer|exists:ressource,code_res',
            'code_pers' => 'sometimes|string|max:32|exists:personnel,code_pers',
        ]);

        $ecRessource = EcRessource::where('code_ec', $code_ec)
            ->where('code_res', $code_res)
            ->where('code_pers', $code_pers)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $ecRessource->update($validatedData);
            DB::commit();
            return response()->json($ecRessource);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating EcRessource: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating EcRessource'], 500);
        }
    }

    public function destroy(string $code_ec, int $code_res, string $code_pers)
    {
        $ecRessource = EcRessource::where('code_ec', $code_ec)
            ->where('code_res', $code_res)
            ->where('code_pers', $code_pers)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $ecRessource->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting EcRessource: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting EcRessource'], 500);
        }
    }
}
