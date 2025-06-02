<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FiliereControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filieres = Filiere::with(['diplomes', 'candidats'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $filieres
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filiere_code' => 'required|string|unique:filiere,filiere_code',
            'filiere_label' => 'required|string',
            'filiere_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $filiere = Filiere::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $filiere
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $filiere = Filiere::with(['diplomes', 'candidats'])->find($id);

        if (!$filiere) {
            return response()->json([
                'status' => 'error',
                'message' => 'Filiere not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $filiere
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $filiere = Filiere::find($id);

        if (!$filiere) {
            return response()->json([
                'status' => 'error',
                'message' => 'Filiere not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'filiere_code' => 'string|unique:filiere,filiere_code,'.$id.',filiere_code',
            'filiere_label' => 'string',
            'filiere_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $filiere->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $filiere
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $filiere = Filiere::find($id);

        if (!$filiere) {
            return response()->json([
                'status' => 'error',
                'message' => 'Filiere not found'
            ], 404);
        }

        $filiere->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Filiere deleted successfully'
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
                'message' => 'This diplome is already attached to this filiere with the same serie'
            ], 409);
        }

        $filiere->diplomes()->attach($request->code_dip, [
            'code_serie' => $request->code_serie
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Diplome attached successfully'
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
                'message' => 'Filiere not found'
            ], 404);
        }

        $filiere->diplomes()->detach($request->code_dip, [
            'code_serie' => $request->code_serie
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Diplome detached successfully'
        ], 200);
    }
}