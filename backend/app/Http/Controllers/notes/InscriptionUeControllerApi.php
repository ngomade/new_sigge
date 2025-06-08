<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\InscriptionUe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InscriptionUeControllerApi extends Controller
{
    public function index()
    {
        $inscriptionUes = InscriptionUe::all();
        return response()->json($inscriptionUes);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_ins' => 'required|string|max:32|exists:inscription,code_ins',
            'code_ue' => 'required|string|max:32|exists:ue,code_ue',
            'etat' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();
            $inscriptionUe = InscriptionUe::create($validatedData);
            DB::commit();
            return response()->json($inscriptionUe);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating InscriptionUe: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating InscriptionUe'], 500);
        }
    }

    public function show(string $code_ins, string $code_ue)
    {
        $inscriptionUe = InscriptionUe::where('code_ins', $code_ins)
            ->where('code_ue', $code_ue)
            ->firstOrFail();
        return response()->json($inscriptionUe);
    }

    public function update(Request $request, string $code_ins, string $code_ue)
    {
        $validatedData = $request->validate([
            'code_ins' => 'sometimes|string|max:32|exists:inscription,code_ins',
            'code_ue' => 'sometimes|string|max:32|exists:ue,code_ue',
            'etat' => 'sometimes|integer',
        ]);

        $inscriptionUe = InscriptionUe::where('code_ins', $code_ins)
            ->where('code_ue', $code_ue)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $inscriptionUe->update($validatedData);
            DB::commit();
            return response()->json($inscriptionUe);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating InscriptionUe: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating InscriptionUe'], 500);
        }
    }

    public function destroy(string $code_ins, string $code_ue)
    {
        $inscriptionUe = InscriptionUe::where('code_ins', $code_ins)
            ->where('code_ue', $code_ue)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $inscriptionUe->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting InscriptionUe: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting InscriptionUe'], 500);
        }
    }
}
