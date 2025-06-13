<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\InfoExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InfoExtraControllerApi extends Controller
{
    public function index()
    {
        $infoExtras = InfoExtra::all();
        return response()->json($infoExtras);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_pere_user' => 'sometimes|nullable|string',
            'nom_mere_user' => 'sometimes|nullable|string',
            'telephone_tuteur_user' => 'sometimes|nullable|string',
            'email_tuteur_user' => 'sometimes|nullable|email',
            'telephone_mere' => 'sometimes|nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $infoExtra = InfoExtra::create($validatedData);
            DB::commit();
            return response()->json($infoExtra);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating InfoExtra: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating InfoExtra'], 500);
        }
    }

    public function show(int $code_info_extra)
    {
        $infoExtra = InfoExtra::findOrFail($code_info_extra);
        return response()->json($infoExtra);
    }

    public function update(Request $request, int $code_info_extra)
    {
        $validatedData = $request->validate([
            'nom_pere_user' => 'sometimes|nullable|string',
            'nom_mere_user' => 'sometimes|nullable|string',
            'telephone_tuteur_user' => 'sometimes|nullable|string',
            'email_tuteur_user' => 'sometimes|nullable|email',
            'telephone_mere' => 'sometimes|nullable|string',
        ]);

        $infoExtra = InfoExtra::findOrFail($code_info_extra);

        try {
            DB::beginTransaction();
            $infoExtra->update($validatedData);
            DB::commit();
            return response()->json($infoExtra);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating InfoExtra: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating InfoExtra'], 500);
        }
    }

    public function destroy(int $code_info_extra)
    {
        $infoExtra = InfoExtra::findOrFail($code_info_extra);

        try {
            DB::beginTransaction();
            $infoExtra->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting InfoExtra: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting InfoExtra'], 500);
        }
    }
}
