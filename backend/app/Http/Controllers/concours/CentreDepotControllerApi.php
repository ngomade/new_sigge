<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\CentreDepot;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CentreDepotControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centre = CentreDepot::all();
        return response()->json($centre);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'centre_depot_label' => 'required|string|max:255'
        ]);
        try {
            $res = CentreDepot::create($validateData);
            return response()->json([
                'message' => 'centre de depot cree avec succes',
                'data' => $res
            ]);

        } catch (Exception $e) {
            Log::error('Error creating centre depot: ' . $e->getMessage());
            return response()->json(['erreur ' => 'erreur lors de l\'enregistrement du centre'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $centre = CentreDepot::findOrfail($id);
        return response()->json($centre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $centre_depot_code)
    {
        $validateData = $request->validate([
            'centre_depot_label' => 'required|string|max:255'
        ]);
        $centre = CentreDepot::findOrfail($centre_depot_code);
        try {
            $centre->update($validateData);
            return response()->json($centre);

        } catch (Exception $e) {
            Log::error('Error updating centre depot: ' . $e->getMessage());
            return response()->json(['error ' => 'erreur de la mise à jour du centre.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $centre_depot_code)
    {
        $centre = CentreDepot::findOrfail($centre_depot_code);
        try {
            $centre->delete();
            return response()->json(['message' => 'CentreDepot supprimé avec succès.']);
        } catch (Exception $e) {
            Log::error('Error deleting centre depot: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur de suppression.'], 500);
        }
    }
}
