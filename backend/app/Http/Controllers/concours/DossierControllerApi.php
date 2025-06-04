<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DossierControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dossiers = Dossier::all();
        return response()->json($dossiers->load('ecole_elements'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label_el' => 'required|string|max:255',
            'ecoles' => 'required|array',
            'ecoles.*' => 'exists:ecole,code_ecole',
        ]);

        try {
            DB::beginTransaction();
            $dossier = Dossier::create($validatedData);
            $dossier->ecole_elements()->attach($request->ecoles);
            DB::commit();
            return response()->json($dossier);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating dossier: ' . $th->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du dossier'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dossier = Dossier::findorfail($id);
        return response()->json($dossier->load('ecole_elements'));
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'label_el' => 'required|string|max:255',
            'ecoles' => 'sometimes|array',
            'ecoles.*' => 'exists:ecole,code_ecole',
        ]);

        $dossier = Dossier::findOrFail($id);
        try {
            DB::beginTransaction();
            $dossier->ecole_elements()->sync($request->ecoles);
            $dossier->update($validatedData);
            DB::commit();
            return response()->json($dossier);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating dossier: ' . $th->getMessage());
            return response()->json(['error' => 'Erreur lors de la mise à jour du dossier'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(string $id)
    {
        $dossier = Dossier::findOrFail($id);
        try {
            DB::beginTransaction();
            $dossier->delete();
            $dossier->ecole_elements()->detach(); // Detach related ecole_elements
            DB::commit();

            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting dossier: ' . $th->getMessage());
            return response()->json(['error' => 'Erreur lors de la suppression du dossier'], 500);
        }
    }
}
