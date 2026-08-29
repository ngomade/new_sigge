<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\SiteComposition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SiteCompositionControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sites = SiteComposition::all();

        return response()->json($sites->load('ecoles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'site_code' => 'required|string|max:255|unique:site_composition,site_code',
            'site_ville' => 'required|string|max:255',
            'site_lieu' => 'required|string|max:255',
            'ecoles' => 'sometimes|array',
            'ecoles.*' => 'required|exists:ecole,code_ecole',
        ]);

        try {
            DB::beginTransaction();
            $site = SiteComposition::create($validatedData);
            if ($request->has('ecoles')) {
                $site->ecoles()->attach($request->ecoles);
            }
            DB::commit();

            return response()->json($site);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating site-composition : '.$th->getMessage());

            return response()->json(['error' => 'Erreur lors de l\'enregistrement du site'.$th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $site = SiteComposition::findorFail($id);

        return response()->json($site->load('ecoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws Throwable
     */
    public function update(Request $request, string $site_code)
    {
        $validatedData = $request->validate([
            'site_code' => 'sometimes|string|max:255|unique:site_composition,site_code,'.$site_code.',site_code',
            'site_ville' => 'sometimes|string|max:255',
            'site_lieu' => 'sometimes|string|max:255',
            'ecoles' => 'sometimes|array',
            'ecoles.*' => 'required|exists:ecole,code_ecole',
        ]);

        $site = SiteComposition::findOrFail($site_code);
        try {
            DB::beginTransaction();
            $site->update($validatedData);
            if ($request->has('ecoles')) {
                $site->ecoles()->sync($request->ecoles);
            }
            DB::commit();

            return response()->json($site);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating site-composition : '.$th->getMessage());

            return response()->json(['error' => 'Erreur lors de la mise à jour du site'.$th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws Throwable
     */
    public function destroy(string $id)
    {
        $site = SiteComposition::findOrFail($id);
        try {
            DB::beginTransaction();
            if ($site->ecoles()->exists()) {
                $site->ecoles()->detach();
            }
            $site->delete();
            DB::commit();

            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting site-composition : '.$th->getMessage());

            return response()->json(['error' => 'Erreur lors de la suppression du site'], 500);
        }
    }
}
