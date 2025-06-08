<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Ec;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EcControllerApi extends Controller
{
    public function index()
    {
        $ecs = Ec::all();
        return response()->json($ecs);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_ec' => 'required|string|max:32|unique:ec,code_ec',
            'code_ue' => 'required|string|max:32|exists:ue,code_ue',
            'intitule_ec' => 'required|string|max:128',
            'credit_ec' => 'required|integer',
            'vh_ec' => 'required|integer',
            'cm_ec' => 'required|integer',
            'td_ec' => 'required|integer',
            'tp_ec' => 'required|integer',
            'tpe_ec' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();
            $ec = Ec::create($validatedData);
            DB::commit();
            return response()->json($ec);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Ec: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Ec'], 500);
        }
    }

    public function show(string $code_ec)
    {
        $ec = Ec::findOrFail($code_ec);
        return response()->json($ec);
    }

    public function update(Request $request, string $code_ec)
    {
        $validatedData = $request->validate([
            'code_ec' => 'sometimes|string|max:32|unique:ec,code_ec,' . $code_ec . ',code_ec',
            'code_ue' => 'sometimes|string|max:32|exists:ue,code_ue',
            'intitule_ec' => 'sometimes|string|max:128',
            'credit_ec' => 'sometimes|integer',
            'vh_ec' => 'sometimes|integer',
            'cm_ec' => 'sometimes|integer',
            'td_ec' => 'sometimes|integer',
            'tp_ec' => 'sometimes|integer',
            'tpe_ec' => 'sometimes|integer',
        ]);

        $ec = Ec::findOrFail($code_ec);

        try {
            DB::beginTransaction();
            $ec->update($validatedData);
            DB::commit();
            return response()->json($ec);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Ec: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Ec'], 500);
        }
    }

    public function destroy(string $code_ec)
    {
        $ec = Ec::findOrFail($code_ec);

        try {
            DB::beginTransaction();
            $ec->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Ec: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Ec'], 500);
        }
    }
}
