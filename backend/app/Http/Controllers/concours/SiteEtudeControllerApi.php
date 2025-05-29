<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\concours\SiteEtude;
use Illuminate\Support\Facades\DB;
use Throwable;

class SiteEtudeControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sites = SiteEtude::withCount('candidats')->get();
        return response()->json($sites, 200);
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
            DB::beginTransaction();
            $site = SiteEtude::create($validateData);
            DB::commit();
            return response()->json($site, 201);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la création du site d\'étude: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_site)
    {
        $site = SiteEtude::withCount('candidats')->find($code_site);
        
        if (!$site) {
            return response()->json(['erreur' => 'Site d\'étude non trouvé'], 404);
        }
        
        return response()->json($site, 200);
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

        try {
            DB::beginTransaction();
            $site = SiteEtude::findOrFail($code_site);
            $site->update($validateData);
            DB::commit();
            return response()->json($site, 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la mise à jour du site d\'étude: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_site)
    {
        try {
            DB::beginTransaction();
            $site = SiteEtude::findOrFail($code_site);
            
            // Vérifier s'il y a des candidats associés
            if ($site->candidats()->count() > 0) {
                return response()->json(['erreur' => 'Impossible de supprimer un site avec des candidats'], 400);
            }
            
            $site->delete();
            DB::commit();
            return response()->json(['succes' => 'Site d\'étude supprimé avec succès'], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la suppression du site d\'étude: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Récupérer les candidats d'un site
     */
    public function candidats(string $code_site)
    {
        $site = SiteEtude::findOrFail($code_site);
        $candidats = $site->candidats()->with(['filiere', 'ecoles'])->get();
        
        return response()->json($candidats, 200);
    }

    /**
     * Récupérer les statistiques d'un site
     */
    public function statistics(string $code_site)
    {
        $site = SiteEtude::findOrFail($code_site);
        
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
        
        return response()->json($stats, 200);
    }

    /**
     * Rechercher des sites
     */
    public function search(Request $request)
    {
        $query = SiteEtude::query();

        if ($request->has('label')) {
            $query->where('label_site', 'like', '%' . $request->label . '%');
        }

        if ($request->has('description')) {
            $query->where('description_site', 'like', '%' . $request->description . '%');
        }

        $sites = $query->withCount('candidats')->get();
        
        return response()->json($sites, 200);
    }
}