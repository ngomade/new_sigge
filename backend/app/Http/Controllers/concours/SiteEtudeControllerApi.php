<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\SiteEtude;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class SiteEtudeControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sites = SiteEtude::with('candidats')->get();
        return response()->json($sites);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'label_site' => 'required|string|max:255|unique:site_etude,label_site',
            'description_site' => 'required|string',
        ]);

        try {
            $site = SiteEtude::create($validateData);
            return response()->json($site);
        } catch (Throwable $th) {
            Log::error('Error creating site: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la création du site d\'étude'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_site)
    {
        $site = SiteEtude::with('candidats')->find($code_site);

        return response()->json($site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_site)
    {
        $validateData = $request->validate([
            'label_site' => 'sometimes|required|string|max:255|unique:site_etude,label_site,' . $code_site . ',code_site',
            'description_site' => 'sometimes|required|string',
        ]);

            $site = SiteEtude::findOrFail($code_site);
        try {
            $site->update($validateData);
            return response()->json($site);
        } catch (Throwable $th) {
            Log::error('Error updating site: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la mise à jour du site d\'étude'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_site)
    {
        $site = SiteEtude::findOrFail($code_site);
        try {
            $site->delete();
            return response()->json(['succes' => 'Site d\'étude supprimé avec succès']);
        } catch (Throwable $th) {
            Log::error('Error deleting site: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la suppression du site d\'étude'], 500);
        }
    }

    /**
     * Récupérer les statistiques d'un site
     */
    public function statistics(string $code_site)
    {
        $site = SiteEtude::findOrFail($code_site);
        try {
            $stats = [
                'site' => $site,
                'total_candidats' => $site->candidats()->count(),
                'candidats_par_sexe' => $site->candidats()
                    ->selectRaw('ca_sexe, count(*) as total')
                    ->groupBy('ca_sexe')
                    ->get(),
                'candidats_par_filiere' => $site->candidats()
                    ->join('filiere', 'candidat.filiere_code', '=', 'filiere.filiere_code')
                    ->selectRaw('filiere.label_filiere, count(*) as total')
                    ->groupBy('filiere.label_filiere')
                    ->get(),
                'candidats_par_nationalite' => $site->candidats()
                    ->selectRaw('ca_nationalite, count(*) as total')
                    ->groupBy('ca_nationalite')
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->get(),
            ];

            return response()->json($stats);
        } catch (Throwable $th) {
            Log::error('Error getting site statistics: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la récupération des statistiques du site'], 500);
        }
    }

    /**
     * Rechercher des sites
     */
    public function search(Request $request)
    {
        try {
            $query = SiteEtude::query();

            if ($request->has('label')) {
                $query->where('label_site', 'like', '%' . $request->label . '%');
            }

            if ($request->has('description')) {
                $query->where('description_site', 'like', '%' . $request->description . '%');
            }

            $sites = $query->withCount('candidats')->get();

            return response()->json($sites);
        } catch (Throwable $th) {
            Log::error('Error searching sites: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la recherche des sites'], 500);
        }
    }
}
