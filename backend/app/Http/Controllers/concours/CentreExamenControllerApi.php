<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\CentreExaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CentreExamenControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centres = CentreExaman::all();
        return response()->json($centres, 200);
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
            DB::beginTransaction();
            $centre = CentreExaman::create($validatedData);
            DB::commit();
            return response()->json($centre, 201);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du centre d\'examen: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $centre = CentreExaman::find($id);
        if (!$centre) {
            return response()->json(['error' => 'Centre d\'examen non trouvé'], 404);
        }
        return response()->json($centre, 200);
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

        try {
            DB::beginTransaction();
            $centre = CentreExaman::findOrFail($id);
            $centre->update($validatedData);
            DB::commit();
            return response()->json($centre, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la mise à jour du centre d\'examen: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $centre = CentreExaman::findOrFail($id);
            $centre->delete();
            DB::commit();
            return response()->json(['success' => 'Centre d\'examen supprimé'], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la suppression du centre d\'examen: ' . $th->getMessage()], 500);
        }
    }
}
