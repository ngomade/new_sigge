<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\CentreExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CentreExamenControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centres = CentreExamen::with("ecole")->get();
        return response()->json($centres);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'centre_exam_code' => 'required|integer|unique:centre_examen,centre_exam_code',
            'code_ecole' => 'required|string|exists:ecole,code_ecole',
            'centre_exam_label' => 'required|string|max:255',
        ]);

        try {
            $centre = CentreExamen::create($validatedData);
            return response()->json($centre);
        } catch (Exception $e) {
            Log::error('Error creating centre examen: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du centre d\'examen'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $centre = CentreExamen::findorfail($id);
        return response()->json($centre->load("ecole"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'code_ecole' => 'required|string|exists:ecole,code_ecole',
            'centre_exam_label' => 'required|string|max:255',
        ]);

        $centre = CentreExamen::findOrFail($id);
        try {
            $centre->update($validatedData);
            return response()->json($centre);
        } catch (Exception $e) {
            Log::error('Error updating centre examen: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la mise à jour du centre d\'examen'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $centre = CentreExamen::findOrFail($id);
        try {
            $centre->delete();
            return response()->json(['message' => 'CentreExamen supprimé avec succès'], 200);
        } catch (Exception $e) {
            Log::error('Error deleting centre examen: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la suppression du centre d\'examen'], 500);
        }
    }
}
