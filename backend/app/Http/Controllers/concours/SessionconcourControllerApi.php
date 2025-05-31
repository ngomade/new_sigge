<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\concours\Sessionconcour;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class SessionconcourControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = Sessionconcour::with('personnel')->orderBy('annee', 'desc')->get();
        return response()->json($sessions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'code_pers' => 'required|string|exists:personnel,code_pers',
            'annee' => 'required|date',
            'debut' => 'required|date',
            'cloture' => 'required|date|after:debut',
        ]);

        try {
            DB::beginTransaction();
            $session = Sessionconcour::create($validateData);
            DB::commit();
            return response()->json($session->load('personnel'), 201);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la création de la session: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $session = Sessionconcour::with(['personnel', 'candidats'])->find($id);
        
        if (!$session) {
            return response()->json(['erreur' => 'Session non trouvée'], 404);
        }
        
        return response()->json($session, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'code_pers' => 'sometimes|required|string|exists:personnel,code_pers',
            'annee' => 'sometimes|required|date',
            'debut' => 'sometimes|required|date',
            'cloture' => 'sometimes|required|date',
        ]);

        // Validation personnalisée pour s'assurer que cloture est après debut
        if (isset($validateData['debut']) && isset($validateData['cloture'])) {
            if (Carbon::parse($validateData['cloture'])->lte(Carbon::parse($validateData['debut']))) {
                return response()->json(['erreur' => 'La date de clôture doit être après la date de début'], 422);
            }
        }

        try {
            DB::beginTransaction();
            $session = Sessionconcour::findOrFail($id);
            
            // Si seulement cloture est mise à jour, vérifier avec la date de début existante
            if (isset($validateData['cloture']) && !isset($validateData['debut'])) {
                if (Carbon::parse($validateData['cloture'])->lte($session->debut)) {
                    return response()->json(['erreur' => 'La date de clôture doit être après la date de début'], 422);
                }
            }
            
            // Si seulement debut est mis à jour, vérifier avec la date de clôture existante
            if (isset($validateData['debut']) && !isset($validateData['cloture'])) {
                if (Carbon::parse($validateData['debut'])->gte($session->cloture)) {
                    return response()->json(['erreur' => 'La date de début doit être avant la date de clôture'], 422);
                }
            }
            
            $session->update($validateData);
            DB::commit();
            return response()->json($session->load('personnel'), 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la mise à jour de la session: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $session = Sessionconcour::findOrFail($id);
            
            // Vérifier s'il y a des candidats associés
            if ($session->candidats()->count() > 0) {
                return response()->json(['erreur' => 'Impossible de supprimer une session avec des candidats'], 400);
            }
            
            $session->delete();
            DB::commit();
            return response()->json(['succes' => 'Session supprimée avec succès'], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la suppression de la session: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Récupérer la session active (en cours)
     */
    public function active()
    {
        $today = Carbon::now();
        $session = Sessionconcour::where('debut', '<=', $today)
                                ->where('cloture', '>=', $today)
                                ->with('personnel')
                                ->first();
        
        if (!$session) {
            return response()->json(['message' => 'Aucune session active'], 404);
        }
        
        return response()->json($session, 200);
    }

    /**
     * Récupérer les sessions par année
     */
    public function byYear($year)
    {
        $sessions = Sessionconcour::whereYear('annee', $year)
                                 ->with('personnel')
                                 ->orderBy('debut')
                                 ->get();
        
        return response()->json($sessions, 200);
    }

    /**
     * Récupérer les statistiques d'une session
     */
    public function statistics(string $id)
    {
        $session = Sessionconcour::findOrFail($id);
        
        $stats = [
            'total_candidats' => $session->candidats()->count(),
            'candidats_par_sexe' => $session->candidats()
                ->selectRaw('ca_sexe, count(*) as total')
                ->groupBy('ca_sexe')
                ->get(),
            'candidats_par_filiere' => $session->candidats()
                ->join('filiere', 'candidat.filiere_code', '=', 'filiere.filiere_code')
                ->selectRaw('filiere.label_filiere, count(*) as total')
                ->groupBy('filiere.label_filiere')
                ->get(),
            'candidats_par_site' => $session->candidats()
                ->join('site_etude', 'candidat.code_site', '=', 'site_etude.code_site')
                ->selectRaw('site_etude.label_site, count(*) as total')
                ->groupBy('site_etude.label_site')
                ->get(),
        ];
        
        return response()->json($stats, 200);
    }

    /**
     * Récupérer les sessions à venir
     */
    public function upcoming()
    {
        $sessions = Sessionconcour::where('debut', '>', Carbon::now())
                                 ->with('personnel')
                                 ->orderBy('debut')
                                 ->get();
        
        return response()->json($sessions, 200);
    }

    /**
     * Récupérer les sessions passées
     */
    public function past()
    {
        $sessions = Sessionconcour::where('cloture', '<', Carbon::now())
                                 ->with('personnel')
                                 ->orderBy('cloture', 'desc')
                                 ->get();
        
        return response()->json($sessions, 200);
    }
}