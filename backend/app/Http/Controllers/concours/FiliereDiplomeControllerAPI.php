<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\FiliereDiplome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FiliereDiplomeControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filiereDiplomes = FiliereDiplome::with(['filiere', 'diplome', 'serie'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $filiereDiplomes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filiere_code' => 'required|string|exists:filiere,filiere_code',
            'code_dip' => 'required|integer|exists:diplome,code_dip',
            'code_serie' => 'required|integer|exists:serie,code_serie',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if relation already exists
        $exists = FiliereDiplome::where('filiere_code', $request->filiere_code)
            ->where('code_dip', $request->code_dip)
            ->where('code_serie', $request->code_serie)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'This relation already exists'
            ], 409);
        }

        $filiereDiplome = FiliereDiplome::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $filiereDiplome
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $filiereDiplome = FiliereDiplome::with(['filiere', 'diplome', 'serie'])->find($id);

        if (!$filiereDiplome) {
            return response()->json([
                'status' => 'error',
                'message' => 'FiliereDiplome not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $filiereDiplome
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $filiereDiplome = FiliereDiplome::find($id);

        if (!$filiereDiplome) {
            return response()->json([
                'status' => 'error',
                'message' => 'FiliereDiplome not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'filiere_code' => 'string|exists:filiere,filiere_code',
            'code_dip' => 'integer|exists:diplome,code_dip',
            'code_serie' => 'integer|exists:serie,code_serie',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $filiereDiplome->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $filiereDiplome
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $filiereDiplome = FiliereDiplome::find($id);

        if (!$filiereDiplome) {
            return response()->json([
                'status' => 'error',
                'message' => 'FiliereDiplome not found'
            ], 404);
        }

        $filiereDiplome->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'FiliereDiplome deleted successfully'
        ], 200);
    }

    /**
     * Get relations by filiere
     */
    public function byFiliere(string $filiereCode)
    {
        $relations = FiliereDiplome::with(['diplome', 'serie'])
            ->where('filiere_code', $filiereCode)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $relations
        ], 200);
    }

    /**
     * Get relations by diplome
     */
    public function byDiplome(int $diplomeCode)
    {
        $relations = FiliereDiplome::with(['filiere', 'serie'])
            ->where('code_dip', $diplomeCode)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $relations
        ], 200);
    }
}
