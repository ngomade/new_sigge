<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DossierControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dossiers = Dossier::all();
        return response()->json($dossiers, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_el' => 'required|integer|unique:dossier,code_el',
            'label_el' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $dossier = Dossier::create($validatedData);
            DB::commit();
            return response()->json($dossier, 201);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du dossier: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dossier = Dossier::find($id);
        if (!$dossier) {
            return response()->json(['error' => 'Dossier non trouvé'], 404);
        }
        return response()->json($dossier, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'label_el' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $dossier = Dossier::findOrFail($id);
            $dossier->update($validatedData);
            DB::commit();
            return response()->json($dossier, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la mise à jour du dossier: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $dossier = Dossier::findOrFail($id);
            $dossier->delete();
            DB::commit();
            return response()->json(['success' => 'Dossier supprimé'], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la suppression du dossier: ' . $th->getMessage()], 500);
        }
    }
}
