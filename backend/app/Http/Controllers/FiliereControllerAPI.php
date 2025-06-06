<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class FiliereControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filieres = Filiere::with(['diplomes', 'candidats'])->get();
        return response()->json($filieres);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'filiere_code' => 'required|string|unique:filiere,filiere_code',
            'filiere_label' => 'required|string',
            'filiere_description' => 'nullable|string',
            'diplomes' => "sometimes|array",
            "diplomes.*" => "required_with:diplomes|integer|exists:diplome,code_dip",
        ]);
        try {
            DB::beginTransaction();
            $filiere = Filiere::create($validator);
            if (isset($validator['diplomes']) && count($validator['diplomes']) > 0) {
                $filiere->diplomes()->attach($validator['diplomes']);
            }
            DB::commit();

            return response()->json([
                'data' => $filiere
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error("Error creating filiere: " . $th->getMessage());
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de la filière',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public
    function show(string $id)
    {
        $filiere = Filiere::with(['diplomes', 'candidats'])->findOrFail($id);

        return response()->json([
            'data' => $filiere
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public
    function update(Request $request, string $id)
    {

        $validator = $request->validate([
            'filiere_code' => 'string|unique:filiere,filiere_code,' . $id . ',filiere_code',
            'filiere_label' => 'string',
            'filiere_description' => 'nullable|string',
            "diplomes" => "sometimes|array",
            "diplomes.*" => "required_with:diplomes|integer|exists:diplome,code_dip",
        ]);

        $filiere = Filiere::findOrFail($id);
        try {
            DB::beginTransaction();
            $filiere->update($validator);
            if ($validator["diplomes"] && count($validator['diplomes']) > 0) {
                $filiere->diplomes()->sync($validator['diplomes']);
            }
            DB::commit();

            return response()->json([
                'data' => $filiere
            ]);

        } catch (Throwable $th) {
            DB::rollBack();
            Log::error("Error updating filiere: " . $th->getMessage());
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de la filière',
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public
    function destroy(string $id)
    {
        $filiere = Filiere::findOrFail($id);

        try {
            DB::beginTransaction();
            // Detach all diplome relations
            $filiere->diplomes()->detach();
            $filiere->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error("Error deleting filiere: " . $th->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Filiere non trouve'
            ], 404);
        }

        $filiere->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Filiere supprime aev succes.'
        ], 200);
    }

    /**
     * Attach a diplome to filiere
     */
    public function attachDiplome(Request $request, string $filiereCode)
    {
        $validator = Validator::make($request->all(), [
            'code_dip' => 'required|integer|exists:diplome,code_dip',
            'code_serie' => 'required|integer|exists:serie,code_serie',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $filiere = Filiere::find($filiereCode);

        if (!$filiere) {
            return response()->json([
                'status' => 'error',
                'message' => 'Filiere not found'
            ], 404);
        }

        // Check if relation already exists
        $exists = $filiere->diplomes()
            ->where('code_dip', $request->code_dip)
            ->wherePivot('code_serie', $request->code_serie)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce dplome est deja attache a cette filiere avec ce meme nom de serie '
            ], 409);
        }

        $filiere->diplomes()->attach($request->code_dip, [
            'code_serie' => $request->code_serie
        ]);

        return response()->json([
            'status' => 'success',
            'message' => ' Le diplome a ete attacher avec succes'
        ], 200);
    }

    /**
     * Detach a diplome from filiere
     */
    public function detachDiplome(Request $request, string $filiereCode)
    {
        $validator = Validator::make($request->all(), [
            'code_dip' => 'required|integer|exists:diplome,code_dip',
            'code_serie' => 'required|integer|exists:serie,code_serie',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $filiere = Filiere::find($filiereCode);

        if (!$filiere) {
            return response()->json([
                'status' => 'error',
                'message' => 'Filiere non retrouve'
            ], 404);
        }

        $filiere->diplomes()->detach($request->code_dip, [
            'code_serie' => $request->code_serie
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Diplome detache avec succes'
        ], 200);
    }
}
