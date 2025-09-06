<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\notes\SessionExamen;
use App\Models\notes\Anneescolaire;
use App\Models\notes\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Throwable;

class SessionExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sessions = SessionExamen::with('anneeScolaire')
                ->orderBy('date_debut_session', 'desc')
                ->paginate(15);

            // Statistiques
            $stats = [
                'total_sessions' => SessionExamen::count(),
                'sessions_actives' => SessionExamen::where('statut_session', 1)->count(),
                'sessions_en_cours' => SessionExamen::where('date_debut_session', '<=', now())
                    ->where('date_fin_session', '>=', now())
                    ->count(),
                'examens_total' => Examen::count()
            ];

            return view('sige_app.backend.gestion_notes.session_examen.index', compact('sessions', 'stats'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage des sessions d\'examen: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du chargement des sessions d\'examen.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $annees = Anneescolaire::orderBy('code_annee', 'desc')
                ->get();

            return view('sige_app.backend.gestion_notes.session_examen.create', compact('annees'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création de session: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('sessionsExamen.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_annee' => 'required|exists:anneescolaire,code_annee',
            'label_session' => 'required|string|max:128',
            'date_debut_session' => 'required|date|after_or_equal:today',
            'date_fin_session' => 'nullable|date|after:date_debut_session',
            'statut_session' => 'required|integer|in:0,1',
        ], [
            'code_annee.required' => 'L\'année scolaire est obligatoire.',
            'code_annee.exists' => 'L\'année scolaire sélectionnée n\'existe pas.',
            'label_session.required' => 'Le libellé de la session est obligatoire.',
            'label_session.max' => 'Le libellé ne peut pas dépasser 128 caractères.',
            'date_debut_session.required' => 'La date de début est obligatoire.',
            'date_debut_session.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'date_fin_session.after' => 'La date de fin doit être postérieure à la date de début.',
            'statut_session.required' => 'Le statut de la session est obligatoire.',
            'statut_session.in' => 'Le statut doit être 0 (inactive) ou 1 (active).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Vérifier les chevauchements de sessions actives pour la même année
            if ($request->statut_session == 1) {
                $chevauchement = SessionExamen::where('code_annee', $request->code_annee)
                    ->where('statut_session', 1)
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('date_debut_session', [
                            $request->date_debut_session,
                            $request->date_fin_session ?? $request->date_debut_session
                        ])
                        ->orWhereBetween('date_fin_session', [
                            $request->date_debut_session,
                            $request->date_fin_session ?? $request->date_debut_session
                        ])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('date_debut_session', '<=', $request->date_debut_session)
                              ->where('date_fin_session', '>=', $request->date_fin_session ?? $request->date_debut_session);
                        });
                    })
                    ->exists();

                if ($chevauchement) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Il existe déjà une session active qui chevauche avec ces dates.')
                        ->withInput();
                }
            }

            $session = SessionExamen::create([
                'code_session' => Str::uuid(),
                'code_annee' => $request->code_annee,
                'label_session' => $request->label_session,
                'date_debut_session' => $request->date_debut_session,
                'date_fin_session' => $request->date_fin_session,
                'statut_session' => $request->statut_session,
            ]);

            DB::commit();

            Log::info('Session d\'examen créée avec succès', [
                'session_id' => $session->code_session,
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);

            return redirect()->route('sessionsExamen.index')
                ->with('success', 'Session d\'examen créée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la création de la session d\'examen: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de la session d\'examen.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_session)
    {
        try {
            $session = SessionExamen::with(['anneeScolaire', 'examens.evaluations'])
                ->findOrFail($code_session);

            // Statistiques de la session
            $stats = [
                'total_examens' => $session->examens->count(),
                'total_evaluations' => $session->examens->sum(function ($examen) {
                    return $examen->evaluations->count();
                }),
                'moyenne_generale' => round($session->examens->flatMap->evaluations->avg('note_eval') ?? 0, 2),
                'taux_reussite' => $this->calculateTauxReussite($session->examens->flatMap->evaluations)
            ];

            return view('sige_app.backend.gestion_notes.session_examen.show', compact('session', 'stats'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage de la session d\'examen: ' . $e->getMessage(), [
                'session_id' => $code_session,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('sessionsExamen.index')
                ->with('error', 'Session d\'examen introuvable ou erreur lors du chargement.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_session)
    {
        try {
            $session = SessionExamen::with('anneescolaire')->findOrFail($code_session);
            $annees = Anneescolaire::where('statut_annee', 1)
                ->orderBy('code_annee', 'desc')
                ->get();

            return view('sige_app.backend.gestion_notes.session_examen.edit', compact('session', 'annees'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification: ' . $e->getMessage(), [
                'session_id' => $code_session,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('sessionsExamen.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_session)
    {
        $validator = Validator::make($request->all(), [
            'code_annee' => 'required|exists:anneescolaire,code_annee',
            'label_session' => 'required|string|max:128',
            'date_debut_session' => 'required|date',
            'date_fin_session' => 'nullable|date|after:date_debut_session',
            'statut_session' => 'required|integer|in:0,1',
        ], [
            'code_annee.required' => 'L\'année scolaire est obligatoire.',
            'code_annee.exists' => 'L\'année scolaire sélectionnée n\'existe pas.',
            'label_session.required' => 'Le libellé de la session est obligatoire.',
            'label_session.max' => 'Le libellé ne peut pas dépasser 128 caractères.',
            'date_debut_session.required' => 'La date de début est obligatoire.',
            'date_fin_session.after' => 'La date de fin doit être postérieure à la date de début.',
            'statut_session.required' => 'Le statut de la session est obligatoire.',
            'statut_session.in' => 'Le statut doit être 0 (inactive) ou 1 (active).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $session = SessionExamen::findOrFail($code_session);

            // Vérifier les chevauchements si la session devient active
            if ($request->statut_session == 1) {
                $chevauchement = SessionExamen::where('code_annee', $request->code_annee)
                    ->where('statut_session', 1)
                    ->where('code_session', '!=', $code_session)
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('date_debut_session', [
                            $request->date_debut_session,
                            $request->date_fin_session ?? $request->date_debut_session
                        ])
                        ->orWhereBetween('date_fin_session', [
                            $request->date_debut_session,
                            $request->date_fin_session ?? $request->date_debut_session
                        ])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('date_debut_session', '<=', $request->date_debut_session)
                              ->where('date_fin_session', '>=', $request->date_fin_session ?? $request->date_debut_session);
                        });
                    })
                    ->exists();

                if ($chevauchement) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Il existe déjà une session active qui chevauche avec ces dates.')
                        ->withInput();
                }
            }

            // Sauvegarder les anciennes valeurs pour les logs
            $oldData = $session->toArray();

            $session->update([
                'code_annee' => $request->code_annee,
                'label_session' => $request->label_session,
                'date_debut_session' => $request->date_debut_session,
                'date_fin_session' => $request->date_fin_session,
                'statut_session' => $request->statut_session,
            ]);

            DB::commit();

            Log::info('Session d\'examen modifiée avec succès', [
                'session_id' => $code_session,
                'user_id' => auth()->id(),
                'old_data' => $oldData,
                'new_data' => $request->all()
            ]);

            return redirect()->route('sessionsExamen.index')
                ->with('success', 'Session d\'examen modifiée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la modification de la session d\'examen: ' . $e->getMessage(), [
                'session_id' => $code_session,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la modification de la session d\'examen.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_session)
    {
        try {
            DB::beginTransaction();

            $session = SessionExamen::with('examens.evaluations')->findOrFail($code_session);

            // Vérifier s'il y a des examens et évaluations liés
            $hasExamens = $session->examens->isNotEmpty();
            $hasEvaluations = $session->examens->flatMap->evaluations->isNotEmpty();

            if ($hasEvaluations) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette session car des évaluations y sont liées.');
            }

            if ($hasExamens) {
                return redirect()->back()
                    ->with('warning', 'Cette session contient des examens. Veuillez d\'abord les supprimer.');
            }

            // Sauvegarder les données pour les logs
            $sessionData = $session->toArray();

            $session->delete();

            DB::commit();

            Log::info('Session d\'examen supprimée avec succès', [
                'session_id' => $code_session,
                'user_id' => auth()->id(),
                'deleted_data' => $sessionData
            ]);

            return redirect()->route('sessionsExamen.index')
                ->with('success', 'Session d\'examen supprimée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la suppression de la session d\'examen: ' . $e->getMessage(), [
                'session_id' => $code_session,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de la session d\'examen.');
        }
    }

    /**
     * Activate/Deactivate a session
     */
    public function toggleStatus($code_session)
    {
        try {
            DB::beginTransaction();

            $session = SessionExamen::findOrFail($code_session);
            $newStatus = $session->statut_session == 1 ? 0 : 1;

            // Si on active la session, vérifier les chevauchements
            if ($newStatus == 1) {
                $chevauchement = SessionExamen::where('code_annee', $session->code_annee)
                    ->where('statut_session', 1)
                    ->where('code_session', '!=', $code_session)
                    ->where(function ($query) use ($session) {
                        $query->whereBetween('date_debut_session', [
                            $session->date_debut_session,
                            $session->date_fin_session ?? $session->date_debut_session
                        ])
                        ->orWhereBetween('date_fin_session', [
                            $session->date_debut_session,
                            $session->date_fin_session ?? $session->date_debut_session
                        ])
                        ->orWhere(function ($q) use ($session) {
                            $q->where('date_debut_session', '<=', $session->date_debut_session)
                              ->where('date_fin_session', '>=', $session->date_fin_session ?? $session->date_debut_session);
                        });
                    })
                    ->exists();

                if ($chevauchement) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Impossible d\'activer : il existe une session active qui chevauche avec ces dates.');
                }
            }

            $session->update(['statut_session' => $newStatus]);

            DB::commit();

            $statusText = $newStatus == 1 ? 'activée' : 'désactivée';

            Log::info('Statut de session modifié', [
                'session_id' => $code_session,
                'user_id' => auth()->id(),
                'old_status' => $session->statut_session,
                'new_status' => $newStatus
            ]);

            return redirect()->back()
                ->with('success', "Session {$statusText} avec succès.");

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors du changement de statut de la session: ' . $e->getMessage(), [
                'session_id' => $code_session,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du changement de statut.');
        }
    }

    /**
     * Get sessions for a specific year (API endpoint)
     */
    public function getSessionsByAnnee($code_annee)
    {
        try {
            $sessions = SessionExamen::where('code_annee', $code_annee)
                ->where('statut_session', 1)
                ->orderBy('date_debut_session', 'desc')
                ->get()
                ->map(function ($session) {
                    return [
                        'code_session' => $session->code_session,
                        'label_session' => $session->label_session,
                        'date_debut_session' => $session->date_debut_session,
                        'date_fin_session' => $session->date_fin_session,
                        'statut_session' => $session->statut_session
                    ];
                });

            return response()->json([
                'success' => true,
                'sessions' => $sessions
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des sessions par année: ' . $e->getMessage(), [
                'code_annee' => $code_annee,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sessions'
            ], 500);
        }
    }

    /**
     * Calculate success rate
     */
    private function calculateTauxReussite($evaluations)
    {
        if ($evaluations->isEmpty()) {
            return 0;
        }

        $totalEvaluations = $evaluations->count();
        $evaluationsReussies = $evaluations->where('note_eval', '>=', 10)->count();

        return round(($evaluationsReussies / $totalEvaluations) * 100, 2);
    }

    /**
     * Duplicate a session
     */
    public function duplicate($code_session)
    {
        try {
            DB::beginTransaction();

            $originalSession = SessionExamen::findOrFail($code_session);

            // Créer une nouvelle session basée sur l'originale
            $newSession = SessionExamen::create([
                'code_session' => Str::uuid(),
                'code_annee' => $originalSession->code_annee,
                'label_session' => $originalSession->label_session . ' (Copie)',
                'date_debut_session' => $originalSession->date_debut_session,
                'date_fin_session' => $originalSession->date_fin_session,
                'statut_session' => 0, // Créer inactive par défaut
            ]);

            DB::commit();

            Log::info('Session d\'examen dupliquée avec succès', [
                'original_session_id' => $code_session,
                'new_session_id' => $newSession->code_session,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('sige_app.backend.gestion_notes.session_examen.edit', $newSession->code_session)
                ->with('success', 'Session dupliquée avec succès. Vous pouvez maintenant la modifier.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la duplication de la session: ' . $e->getMessage(), [
                'session_id' => $code_session,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la duplication de la session.');
        }
    }
}