<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\SiteComposition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SiteCompositionControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sites = SiteComposition::all();
        return response()->json($sites, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'site_code' => 'required|string|max:255|unique:site_composition,site_code',
            'site_ville' => 'required|string|max:255',
            'site_lieu' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $site = SiteComposition::create($validatedData);
            DB::commit();
            return response()->json($site, 201);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du site: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $site = SiteComposition::find($id);
        if (!$site) {
            return response()->json(['error' => 'Site non trouvé'], 404);
        }
        return response()->json($site, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'site_ville' => 'required|string|max:255',
            'site_lieu' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $site = SiteComposition::findOrFail($id);
            $site->update($validatedData);
            DB::commit();
            return response()->json($site, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la mise à jour du site: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $site = SiteComposition::findOrFail($id);
            $site->delete();
            DB::commit();
            return response()->json(['success' => 'Site supprimé'], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la suppression du site: ' . $th->getMessage()], 500);
        }
    }
}
