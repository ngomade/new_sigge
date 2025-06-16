<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\concours\SessionConcours;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class SessionconcourControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ::with(['personnel', 'candidat'])->orderBy('annee', 'desc')->get();
        $sessions = SessionConcours::all();
        return response()->json($sessions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'code_pers' => 'required|string|exists:personnel,code_pers',
            'annee' => 'required',
            'debut' => 'required|date',
            'cloture' => 'required|date|after:debut',
        ]);

        try {
            $session = SessionConcours::create($validateData);
            return response()->json($session->load('personnel'));
        } catch (Throwable $th) {
            Log::error('Error creating session: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la création de la session'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $session = SessionConcours::with(['personnel', 'candidats'])->findOrFail($id);

        return response()->json($session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'code_pers' => 'sometimes|string|exists:personnel,code_pers',
            'annee' => 'sometimes',
            'debut' => 'sometimes|date|required_with:cloture',
            'cloture' => 'sometimes|date|after:debut|required_with:debut',
        ]);

        $session = SessionConcours::findOrFail($id);
        try {
            $session->update($validateData);
            return response()->json($session->load('personnel'));
        } catch (Throwable $th) {
            Log::error('Error updating session: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la mise à jour de la session: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $session = SessionConcours::findOrFail($id);
        try {
            $session->delete();
            return response()->noContent();
        } catch (Throwable $th) {
            Log::error('Error deleting session: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la suppression de la session: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Récupérer la session active (en cours)
     */
    public function active()
    {
        $today = Carbon::now();
        $session = SessionConcours::where('debut', '<=', $today)
            ->where('cloture', '>=', $today)
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Aucune session active'], 404);
        }

        return response()->json($session);
    }

    /**
     * Récupérer les sessions par année
     */
    public function byYear($year)
    {
        $sessions = SessionConcours::whereYear('annee', $year)
            ->with(['personnel','candidats'])
            ->orderBy('debut')
            ->get();

        return response()->json($sessions);
    }

    /**
     * Récupérer les statistiques d'une session
     */
    public function statistics(string $id)
    {
        $session = SessionConcours::findOrFail($id);

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

        return response()->json($stats);
    }

    /**
     * Récupérer les sessions à venir
     */
    public function upcoming()
    {
        $sessions = SessionConcours::where('debut', '>', Carbon::now())
            ->with(['personnel', 'candidats'])
            ->orderBy('debut')
            ->get();

        return response()->json($sessions);
    }

    /**
     * Récupérer les sessions passées
     */
    public function past()
    {
        $sessions = SessionConcours::where('cloture', '<', Carbon::now())
            ->with(["personnel", "candidats"])
            ->orderBy('cloture', 'desc')
            ->get();

        return response()->json($sessions);
    }
}
