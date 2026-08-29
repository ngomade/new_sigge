<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\Composition;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompositionControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compositions = Composition::all();

        return response()->json($compositions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'code_ecole' => 'required|string|exists:ecole,code_ecole',
            'site_code' => 'required|string|exists:site_composition,site_code',
        ]);
        try {
            $composition = Composition::create($validateData);

            return response()->json($composition);
        } catch (Exception $e) {
            Log::error('Error creating composition: '.$e->getMessage());

            return response()->json(['erreur' => 'erreur lors de l\'enregistrement de la composition'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $composition = Composition::findorFail($id);

        return response()->json($composition);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'code_ecole' => 'required|string|exists:ecole,code_ecole',
            'site_code' => 'required|string|exists:site_composition,site_code',
        ]);
        $composition = Composition::findOrFail($id);
        try {
            $composition->update($validateData);

            return response()->json($composition);
        } catch (Exception $e) {
            Log::error('Error updating composition: '.$e->getMessage());

            return response()->json(['erreur' => 'Erreur lors de la mise à jour de la composition.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $composition = Composition::findOrFail($id);
        try {
            $composition->delete();

            return response()->json(['succes' => 'Composition supprimée.']);
        } catch (Exception $e) {
            Log::error('Error deleting composition: '.$e->getMessage());

            return response()->json(['erreur' => 'Erreur lors de la suppression de la composition.'], 500);
        }
    }
}
