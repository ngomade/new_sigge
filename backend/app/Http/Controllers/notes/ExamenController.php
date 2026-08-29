<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\notes\Ec;
use App\Models\notes\Examen;
use App\Models\notes\Periode;
use App\Models\notes\Salle;
use App\Models\notes\SessionExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class ExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Examen::with(['sessionExamen.anneeScolaire', 'evaluations.ec']);

            // Filtres
            if ($request->filled('session')) {
                $query->where('code_session', $request->session);
            }

            if ($request->filled('type_evaluation')) {
                $query->where('type_evaluation', $request->type_evaluation);
            }

            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $query->whereHas('sessionExamen', function ($q) use ($request) {
                    $q->whereBetween('date_debut_session', [
                        $request->date_debut,
                        $request->date_fin,
                    ]);
                });
            }

            $examens = $query->orderBy('created_at', 'desc')->paginate(15);

            // Données pour les filtres
            $sessions = SessionExamen::with('anneeScolaire')
                ->orderBy('date_debut_session', 'desc')
                ->get();

            $typesEvaluation = [
                'CC' => 'Contrôle Continu',
                'TP' => 'Travaux Pratiques',
                'TD' => 'Travaux Dirigés',
                'EXAM' => 'Examen Final',
                'RATTRAPAGE' => 'Rattrapage',
                'PROJET' => 'Projet',
                'STAGE' => 'Stage',
            ];

            // Statistiques
            $stats = [
                'total_examens' => Examen::count(),
                'examens_ce_mois' => Examen::whereHas('sessionExamen', function ($q) {
                    $q->whereMonth('date_debut_session', now()->month)
                        ->whereYear('date_debut_session', now()->year);
                })->count(),
                'examens_actifs' => Examen::whereHas('sessionExamen', function ($q) {
                    $q->where('statut_session', 1);
                })->count(),
                'total_evaluations' => $examens->sum(function ($examen) {
                    return $examen->evaluations->count();
                }),
            ];

            return view('sige_app.backend.gestion_notes.examen.index', compact(
                'examens', 'sessions', 'typesEvaluation', 'stats'
            ));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage des examens: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du chargement des examens.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $sessions = SessionExamen::with('anneeScolaire')
                ->where('statut_session', 1)
                ->orderBy('date_debut_session', 'desc')
                ->get();

            $typesEvaluation = [
                'CC' => 'Contrôle Continu',
                'TP' => 'Travaux Pratiques',
                'TD' => 'Travaux Dirigés',
                'EXAM' => 'Examen Final',
                'RATTRAPAGE' => 'Rattrapage',
                'PROJET' => 'Projet',
                'STAGE' => 'Stage',
            ];

            return view('sige_app.backend.gestion_notes.examen.create', compact('sessions', 'typesEvaluation'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création d\'examen: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('examens.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_session' => 'required|exists:session_examen,code_session',
            'type_evaluation' => 'required|string|max:32',
        ], [
            'code_session.required' => 'La session d\'examen est obligatoire.',
            'code_session.exists' => 'La session d\'examen sélectionnée n\'existe pas.',
            'type_evaluation.required' => 'Le type d\'évaluation est obligatoire.',
            'type_evaluation.max' => 'Le type d\'évaluation ne peut pas dépasser 32 caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Vérifier que la session est active
            $session = SessionExamen::findOrFail($request->code_session);
            if ($session->statut_session != 1) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Impossible de créer un examen dans une session inactive.')
                    ->withInput();
            }

            $examen = Examen::create([
                'code_examen' => Str::uuid(),
                'code_session' => $request->code_session,
                'type_evaluation' => $request->type_evaluation,
            ]);

            DB::commit();

            Log::info('Examen créé avec succès', [
                'examen_id' => $examen->code_examen,
                'user_id' => auth()->id(),
                'data' => $request->all(),
            ]);

            return redirect()->route('examens.show', $examen->code_examen)
                ->with('success', 'Examen créé avec succès. Vous pouvez maintenant planifier les périodes.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création de l\'examen: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'examen.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_examen)
    {
        try {
            $examen = Examen::with([
                'sessionExamen.anneeScolaire',
                'evaluations.ec.ue.semestre',
                'evaluations.user',
                'periodes.salle',
                'periodes.ec',
            ])->findOrFail($code_examen);

            // Statistiques de l'examen
            $stats = [
                'total_evaluations' => $examen->evaluations->count(),
                'etudiants_evalues' => $examen->evaluations->unique('code_user')->count(),
                'ecs_concernes' => $examen->evaluations->unique('code_ec')->count(),
                'moyenne_generale' => round($examen->evaluations->avg('note_eval') ?? 0, 2),
                'taux_reussite' => $this->calculateTauxReussite($examen->evaluations),
                'periodes_planifiees' => $examen->periodes->count(),
            ];

            // Répartition par EC
            $repartitionEc = $examen->evaluations
                ->groupBy('code_ec')
                ->map(function ($evaluations, $code_ec) {
                    $ec = $evaluations->first()->ec;

                    return [
                        'ec' => $ec,
                        'count' => $evaluations->count(),
                        'moyenne' => round($evaluations->avg('note_eval'), 2),
                        'taux_reussite' => $this->calculateTauxReussite($evaluations),
                    ];
                });

            return view('sige_app.backend.gestion_notes.examen.show', compact('examen', 'stats', 'repartitionEc'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage de l\'examen: '.$e->getMessage(), [
                'examen_id' => $code_examen,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('examens.index')
                ->with('error', 'Examen introuvable ou erreur lors du chargement.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_examen)
    {
        try {
            $examen = Examen::with('sessionExamen')->findOrFail($code_examen);

            $sessions = SessionExamen::with('anneeScolaire')
                ->where('statut_session', 1)
                ->orderBy('date_debut_session', 'desc')
                ->get();

            $typesEvaluation = [
                'CC' => 'Contrôle Continu',
                'TP' => 'Travaux Pratiques',
                'TD' => 'Travaux Dirigés',
                'EXAM' => 'Examen Final',
                'RATTRAPAGE' => 'Rattrapage',
                'PROJET' => 'Projet',
                'STAGE' => 'Stage',
            ];

            return view('sige_app.backend.gestion_notes.examen.edit', compact('examen', 'sessions', 'typesEvaluation'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification: '.$e->getMessage(), [
                'examen_id' => $code_examen,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('examens.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_examen)
    {
        $validator = Validator::make($request->all(), [
            'code_session' => 'required|exists:session_examen,code_session',
            'type_evaluation' => 'required|string|max:32',
        ], [
            'code_session.required' => 'La session d\'examen est obligatoire.',
            'code_session.exists' => 'La session d\'examen sélectionnée n\'existe pas.',
            'type_evaluation.required' => 'Le type d\'évaluation est obligatoire.',
            'type_evaluation.max' => 'Le type d\'évaluation ne peut pas dépasser 32 caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $examen = Examen::findOrFail($code_examen);

            // Vérifier que la nouvelle session est active
            $session = SessionExamen::findOrFail($request->code_session);
            if ($session->statut_session != 1) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Impossible d\'assigner un examen à une session inactive.')
                    ->withInput();
            }

            // Sauvegarder les anciennes valeurs pour les logs
            $oldData = $examen->toArray();

            $examen->update([
                'code_session' => $request->code_session,
                'type_evaluation' => $request->type_evaluation,
            ]);

            DB::commit();

            Log::info('Examen modifié avec succès', [
                'examen_id' => $code_examen,
                'user_id' => auth()->id(),
                'old_data' => $oldData,
                'new_data' => $request->all(),
            ]);

            return redirect()->route('examens.show', $code_examen)
                ->with('success', 'Examen modifié avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la modification de l\'examen: '.$e->getMessage(), [
                'examen_id' => $code_examen,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la modification de l\'examen.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_examen)
    {
        try {
            DB::beginTransaction();

            $examen = Examen::with(['evaluations', 'periodes'])->findOrFail($code_examen);

            // Vérifier s'il y a des évaluations liées
            if ($examen->evaluations->isNotEmpty()) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cet examen car des évaluations y sont liées.');
            }

            // Supprimer les périodes associées
            if ($examen->periodes->isNotEmpty()) {
                $examen->periodes()->delete();
            }

            // Sauvegarder les données pour les logs
            $examenData = $examen->toArray();

            $examen->delete();

            DB::commit();

            Log::info('Examen supprimé avec succès', [
                'examen_id' => $code_examen,
                'user_id' => auth()->id(),
                'deleted_data' => $examenData,
            ]);

            return redirect()->route('examens.index')
                ->with('success', 'Examen supprimé avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression de l\'examen: '.$e->getMessage(), [
                'examen_id' => $code_examen,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'examen.');
        }
    }

    /**
     * Show planning form for an exam
     */
    public function planifier($code_examen)
    {
        try {
            $examen = Examen::with('sessionExamen')->findOrFail($code_examen);

            $salles = Salle::where('etat_salle', true)
                ->orderBy('code_salle')
                ->get();

            $ecs = Ec::with(['ue.semestre', 'assignations.classe'])
                ->orderBy('intitule_ec')
                ->get();

            // Récupérer les périodes existantes
            $periodesTourning = Periode::where('code_ec', '!=', null)
                ->with(['salle', 'ec'])
                ->get();

            return view('sige_app.backend.gestion_notes.examen.planifier', compact('examen', 'salles', 'ecs', 'periodesTourning'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de planification: '.$e->getMessage(), [
                'examen_id' => $code_examen,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('examens.show', $code_examen)
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire de planification.');
        }
    }

    /**
     * Store planning for an exam
     */
    public function storePlanification(Request $request, $code_examen)
    {
        $validator = Validator::make($request->all(), [
            'planifications' => 'required|array|min:1',
            'planifications.*.code_salle' => 'required|exists:salle,code_salle',
            'planifications.*.code_ec' => 'required|exists:ec,code_ec',
            'planifications.*.debut_periode' => 'required|date',
            'planifications.*.fin_periode' => 'required|date|after:planifications.*.debut_periode',
            'planifications.*.jour_periode' => 'required|integer|min:1|max:7',
            'planifications.*.duree_periode' => 'required|integer|min:30',
        ], [
            'planifications.required' => 'Au moins une planification doit être définie.',
            'planifications.*.code_salle.required' => 'La salle est obligatoire.',
            'planifications.*.code_ec.required' => 'L\'EC est obligatoire.',
            'planifications.*.debut_periode.required' => 'La date de début est obligatoire.',
            'planifications.*.fin_periode.required' => 'La date de fin est obligatoire.',
            'planifications.*.fin_periode.after' => 'La date de fin doit être postérieure à la date de début.',
            'planifications.*.jour_periode.required' => 'Le jour est obligatoire.',
            'planifications.*.duree_periode.required' => 'La durée est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $examen = Examen::findOrFail($code_examen);
            $successCount = 0;
            $conflictCount = 0;
            $errors = [];

            foreach ($request->planifications as $planifData) {
                try {
                    // Vérifier les conflits de salle
                    $conflictSalle = Periode::where('code_salle', $planifData['code_salle'])
                        ->where(function ($query) use ($planifData) {
                            $query->whereBetween('debut_periode', [
                                $planifData['debut_periode'],
                                $planifData['fin_periode'],
                            ])
                                ->orWhereBetween('fin_periode', [
                                    $planifData['debut_periode'],
                                    $planifData['fin_periode'],
                                ])
                                ->orWhere(function ($q) use ($planifData) {
                                    $q->where('debut_periode', '<=', $planifData['debut_periode'])
                                        ->where('fin_periode', '>=', $planifData['fin_periode']);
                                });
                        })
                        ->exists();

                    if ($conflictSalle) {
                        $conflictCount++;
                        $salle = Salle::find($planifData['code_salle']);
                        $errors[] = 'Conflit de salle pour '.($salle ? $salle->code_salle : 'salle inconnue');

                        continue;
                    }

                    // Créer la période
                    Periode::create([
                        'code_salle' => $planifData['code_salle'],
                        'code_ec' => $planifData['code_ec'],
                        'code_periode' => $this->generateCodePeriode(),
                        'debut_periode' => $planifData['debut_periode'],
                        'fin_periode' => $planifData['fin_periode'],
                        'jour_periode' => $planifData['jour_periode'],
                        'duree_periode' => $planifData['duree_periode'],
                    ]);

                    $successCount++;

                } catch (Throwable $e) {
                    $ec = Ec::find($planifData['code_ec']);
                    $errors[] = "Erreur pour l'EC ".($ec ? $ec->intitule_ec : 'inconnu');

                    Log::error('Erreur lors de la planification: ', [
                        'error' => $e->getMessage(),
                        'planif_data' => $planifData,
                    ]);
                }
            }

            DB::commit();

            $message = "Planification terminée : {$successCount} créée(s)";
            if ($conflictCount > 0) {
                $message .= ", {$conflictCount} conflit(s) de salle";
            }
            if (count($errors) > 0) {
                $message .= ', '.count($errors).' erreur(s)';
            }

            Log::info('Planification d\'examen effectuée', [
                'user_id' => auth()->id(),
                'examen_id' => $code_examen,
                'success_count' => $successCount,
                'conflict_count' => $conflictCount,
                'error_count' => count($errors),
            ]);

            $alertType = ($conflictCount + count($errors)) > 0 ? 'warning' : 'success';

            return redirect()->route('examens.show', $code_examen)
                ->with($alertType, $message);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la planification de l\'examen: '.$e->getMessage(), [
                'examen_id' => $code_examen,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la planification de l\'examen.')
                ->withInput();
        }
    }

    /**
     * Get examens by session (API endpoint)
     */
    public function getExamensBySession($code_session)
    {
        try {
            $examens = Examen::where('code_session', $code_session)
                ->with('evaluations')
                ->get()
                ->map(function ($examen) {
                    return [
                        'code_examen' => $examen->code_examen,
                        'type_evaluation' => $examen->type_evaluation,
                        'created_at' => $examen->created_at->format('d/m/Y'),
                        'evaluations_count' => $examen->evaluations->count(),
                    ];
                });

            return response()->json([
                'success' => true,
                'examens' => $examens,
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des examens par session: '.$e->getMessage(), [
                'code_session' => $code_session,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des examens',
            ], 500);
        }
    }

    /**
     * Get available rooms for a specific period
     */
    public function getSallesDisponibles(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'debut_periode' => 'required|date',
                'fin_periode' => 'required|date|after:debut_periode',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paramètres invalides',
                ], 400);
            }

            // Récupérer les salles occupées pendant cette période
            $sallesOccupees = Periode::where(function ($query) use ($request) {
                $query->whereBetween('debut_periode', [
                    $request->debut_periode,
                    $request->fin_periode,
                ])
                    ->orWhereBetween('fin_periode', [
                        $request->debut_periode,
                        $request->fin_periode,
                    ])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('debut_periode', '<=', $request->debut_periode)
                            ->where('fin_periode', '>=', $request->fin_periode);
                    });
            })->pluck('code_salle');

            // Récupérer les salles disponibles
            $sallesDisponibles = Salle::where('etat_salle', true)
                ->whereNotIn('code_salle', $sallesOccupees)
                ->orderBy('code_salle')
                ->get()
                ->map(function ($salle) {
                    return [
                        'code_salle' => $salle->code_salle,
                        'nb_place_salle' => $salle->nb_place_salle,
                        'desc_salle' => $salle->desc_salle,
                    ];
                });

            return response()->json([
                'success' => true,
                'salles' => $sallesDisponibles,
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des salles disponibles: '.$e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des salles disponibles',
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
     * Generate unique code for periode
     */
    private function generateCodePeriode()
    {
        do {
            $code = random_int(100000, 999999);
        } while (Periode::where('code_periode', $code)->exists());

        return $code;
    }

    /**
     * Export exam planning to PDF or CSV
     */
    public function exportPlanning($code_examen, $format = 'pdf')
    {
        try {
            $examen = Examen::with([
                'sessionExamen.anneeScolaire',
                'periodes.salle',
                'periodes.ec.ue.semestre',
            ])->findOrFail($code_examen);

            if ($format === 'csv') {
                return $this->exportPlanningCSV($examen);
            }

            // Pour le PDF, vous devrez implémenter avec une librairie comme DomPDF
            // return $this->exportPlanningPDF($examen);

            return redirect()->back()
                ->with('info', 'Export PDF non encore implémenté. Utilisez le format CSV.');

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'export du planning: '.$e->getMessage(), [
                'examen_id' => $code_examen,
                'format' => $format,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'export du planning.');
        }
    }

    /**
     * Export planning to CSV
     */
    private function exportPlanningCSV($examen)
    {
        $filename = 'planning_examen_'.$examen->code_examen.'_'.now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($examen) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'Session',
                'Type Examen',
                'Salle',
                'Capacité',
                'EC',
                'UE',
                'Semestre',
                'Date Début',
                'Date Fin',
                'Jour',
                'Durée (min)',
            ]);

            // Données
            foreach ($examen->periodes as $periode) {
                fputcsv($file, [
                    $examen->sessionExamen->label_session,
                    $examen->type_evaluation,
                    $periode->salle->code_salle ?? '',
                    $periode->salle->nb_place_salle ?? '',
                    $periode->ec->intitule_ec ?? '',
                    $periode->ec->ue->intitule_ue ?? '',
                    $periode->ec->ue->semestre->label_sem ?? '',
                    $periode->debut_periode,
                    $periode->fin_periode,
                    $periode->jour_periode,
                    $periode->duree_periode,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
