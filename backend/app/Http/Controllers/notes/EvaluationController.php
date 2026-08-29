<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\AnneeScolaire;
use App\Models\notes\Ec;
use App\Models\notes\Evaluation;
use App\Models\notes\Examen;
use App\Models\notes\Inscription;
use App\Models\notes\SessionExamen;
use App\Models\Semestre;
use App\Models\Ue;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class EvaluationController extends Controller
{
    /**
     * Display a listing of evaluations with filters
     */
    public function index(Request $request)
    {
        try {
            $query = Evaluation::with(['ec.ue.semestre', 'examen.sessionExamen', 'user']);

            // Filtres
            if ($request->filled('session')) {
                $query->whereHas('examen.sessionExamen', function ($q) use ($request) {
                    $q->where('code_session', $request->session);
                });
            }

            if ($request->filled('ec')) {
                $query->where('code_ec', $request->ec);
            }

            if ($request->filled('etudiant')) {
                $query->where('code_user', $request->etudiant);
            }

            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $query->whereBetween('date_evaluation', [
                    $request->date_debut,
                    $request->date_fin,
                ]);
            }

            $evaluations = $query->orderBy('date_evaluation', 'desc')->paginate(20);

            // Données pour les filtres
            $sessions = SessionExamen::with('anneeScolaire')
                ->orderBy('date_debut_session', 'desc')
                ->get();

            $ecs = Ec::with('ue')->orderBy('intitule_ec')->get();
            $etudiants = Users::role('etudiant')
                ->orderBy('nom_user')
                ->get();
            $etudiants = Users::role('etudiant')
                ->orderBy('nom_user')
                ->get();

            // Statistiques rapides
            $stats = [
                'total_evaluations' => Evaluation::count(),
                'evaluations_ce_mois' => Evaluation::whereMonth('date_evaluation', now()->month)
                    ->whereYear('date_evaluation', now()->year)
                    ->count(),
                'moyenne_generale' => round(Evaluation::avg('note_eval') ?? 0, 2),
                'taux_reussite' => round(
                    (Evaluation::where('note_eval', '>=', 10)->count() /
                    max(Evaluation::count(), 1)) * 100, 2
                ),
            ];

            return view('sige_app.backend.gestion_notes.evaluation.index', compact(
                'evaluations', 'sessions', 'ecs', 'etudiants', 'stats'
            ));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage des évaluations: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du chargement des évaluations.');
        }
    }

    /**
     * Show the form for creating a new evaluation
     */
    public function create(Request $request)
    {
        try {
            $ecs = Ec::with(['ue.semestre', 'assignations.classe'])
                ->orderBy('intitule_ec')
                ->get();

            $examens = Examen::with('sessionExamen')
                ->whereHas('sessionExamen', function ($q) {
                    $q->where('statut_session', 1); // Sessions actives uniquement
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Si un EC est pré-sélectionné, récupérer les étudiants inscrits
            $etudiants = collect();
            if ($request->filled('ec')) {
                $etudiants = $this->getEtudiantsByEc($request->ec);
            }

            return view('sige_app.backend.gestion_notes.evaluation.create', compact('ecs', 'examens', 'etudiants'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création d\'évaluation: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('evaluations.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created evaluation in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_ec' => 'required|exists:ec,code_ec',
            'code_examen' => 'required|exists:examen,code_examen',
            'date_evaluation' => 'required|date',
            'evaluations' => 'required|array|min:1',
            'evaluations.*.code_user' => 'required|exists:users,code_user',
            'evaluations.*.note_eval' => 'required|numeric|min:0|max:20',
        ], [
            'code_ec.required' => 'L\'élément constitutif est obligatoire.',
            'code_examen.required' => 'L\'examen est obligatoire.',
            'date_evaluation.required' => 'La date d\'évaluation est obligatoire.',
            'evaluations.required' => 'Au moins une note doit être saisie.',
            'evaluations.min' => 'Au moins une note doit être saisie.',
            'evaluations.*.note_eval.min' => 'La note doit être comprise entre 0 et 20.',
            'evaluations.*.note_eval.max' => 'La note doit être comprise entre 0 et 20.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $successCount = 0;
            $updateCount = 0;
            $errors = [];

            foreach ($request->evaluations as $evalData) {
                try {
                    // Vérifier si une évaluation existe déjà
                    $existingEvaluation = Evaluation::where([
                        'code_ec' => $request->code_ec,
                        'code_examen' => $request->code_examen,
                        'code_user' => $evalData['code_user'],
                    ])->first();

                    if ($existingEvaluation) {
                        // Mettre à jour l'évaluation existante
                        $existingEvaluation->update([
                            'note_eval' => $evalData['note_eval'],
                            'date_evaluation' => $request->date_evaluation,
                            'date_evalu' => now()->toDateString(),
                            'code_ano' => $evalData['code_ano'] ?? null,
                        ]);
                        $updateCount++;
                    } else {
                        // Créer une nouvelle évaluation
                        Evaluation::create([
                            'code_ec' => $request->code_ec,
                            'code_examen' => $request->code_examen,
                            'code_user' => $evalData['code_user'],
                            'note_eval' => $evalData['note_eval'],
                            'date_evaluation' => $request->date_evaluation,
                            'date_evalu' => now()->toDateString(),
                            'code_ano' => $evalData['code_ano'] ?? null,
                        ]);
                        $successCount++;
                    }

                } catch (Throwable $e) {
                    $user = User::find($evalData['code_user']);
                    $errors[] = "Erreur pour l'étudiant ".($user ? $user->name : $evalData['code_user']);

                    Log::error('Erreur lors de la création d\'évaluation pour étudiant: '.$evalData['code_user'], [
                        'error' => $e->getMessage(),
                        'eval_data' => $evalData,
                    ]);
                }
            }

            DB::commit();

            $message = "Évaluations enregistrées : {$successCount} créée(s), {$updateCount} mise(s) à jour";
            if (count($errors) > 0) {
                $message .= ', '.count($errors).' erreur(s)';
            }

            Log::info('Évaluations créées/mises à jour', [
                'user_id' => auth()->id(),
                'code_ec' => $request->code_ec,
                'code_examen' => $request->code_examen,
                'success_count' => $successCount,
                'update_count' => $updateCount,
                'error_count' => count($errors),
            ]);

            $alertType = count($errors) > 0 ? 'warning' : 'success';

            return redirect()->route('evaluations.index')
                ->with($alertType, $message);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création des évaluations: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement des évaluations.')
                ->withInput();
        }
    }

    /**
     * Display the specified evaluation
     */
    public function show($code_ec, $code_examen, $code_user)
    {
        try {
            $evaluation = Evaluation::with([
                'ec.ue.semestre',
                'examen.sessionExamen.anneeScolaire',
                'user',
            ])->where([
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
                'code_user' => $code_user,
            ])->firstOrFail();

            // Autres évaluations de cet étudiant pour cet EC
            $autresEvaluations = Evaluation::with('examen.sessionExamen')
                ->where('code_ec', $code_ec)
                ->where('code_user', $code_user)
                ->where(function ($q) use ($code_examen) {
                    $q->where('code_examen', '!=', $code_examen);
                })
                ->orderBy('date_evaluation', 'desc')
                ->get();

            return view('sige_app.backend.gestion_notes.evaluation.show', compact('evaluation', 'autresEvaluations'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage de l\'évaluation: '.$e->getMessage(), [
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
                'code_user' => $code_user,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('evaluations.index')
                ->with('error', 'Évaluation introuvable ou erreur lors du chargement.');
        }
    }

    /**
     * Show the form for editing evaluations for a specific EC and exam
     */
    public function edit($code_ec, $code_examen)
    {
        try {
            $ec = Ec::with('ue.semestre')->findOrFail($code_ec);
            $examen = Examen::with('sessionExamen')->findOrFail($code_examen);

            // Récupérer toutes les évaluations pour cet EC et cet examen
            $evaluations = Evaluation::with('user')
                ->where('code_ec', $code_ec)
                ->where('code_examen', $code_examen)
                ->get()
                ->keyBy('code_user');

            // Récupérer tous les étudiants inscrits à cet EC
            $etudiants = $this->getEtudiantsByEc($code_ec);

            return view('sige_app.backend.gestion_notes.evaluation.edit', compact('ec', 'examen', 'evaluations', 'etudiants'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification: '.$e->getMessage(), [
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('evaluations.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Update evaluations for a specific EC and exam
     */
    public function update(Request $request, $code_ec, $code_examen)
    {
        $validator = Validator::make($request->all(), [
            'date_evaluation' => 'required|date',
            'evaluations' => 'required|array|min:1',
            'evaluations.*.code_user' => 'required|exists:users,code_user',
            'evaluations.*.note_eval' => 'required|numeric|min:0|max:20',
        ], [
            'date_evaluation.required' => 'La date d\'évaluation est obligatoire.',
            'evaluations.required' => 'Au moins une note doit être saisie.',
            'evaluations.*.note_eval.min' => 'La note doit être comprise entre 0 et 20.',
            'evaluations.*.note_eval.max' => 'La note doit être comprise entre 0 et 20.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errors = [];

            foreach ($request->evaluations as $evalData) {
                try {
                    Evaluation::updateOrCreate([
                        'code_ec' => $code_ec,
                        'code_examen' => $code_examen,
                        'code_user' => $evalData['code_user'],
                    ], [
                        'note_eval' => $evalData['note_eval'],
                        'date_evaluation' => $request->date_evaluation,
                        'date_evalu' => now()->toDateString(),
                        'code_ano' => $evalData['code_ano'] ?? null,
                    ]);

                    $successCount++;

                } catch (Throwable $e) {
                    $user = User::find($evalData['code_user']);
                    $errors[] = "Erreur pour l'étudiant ".($user ? $user->name : $evalData['code_user']);

                    Log::error('Erreur lors de la mise à jour d\'évaluation: '.$evalData['code_user'], [
                        'error' => $e->getMessage(),
                        'code_ec' => $code_ec,
                        'code_examen' => $code_examen,
                    ]);
                }
            }

            DB::commit();

            $message = "Évaluations mises à jour : {$successCount} modifiée(s)";
            if (count($errors) > 0) {
                $message .= ', '.count($errors).' erreur(s)';
            }

            Log::info('Évaluations mises à jour', [
                'user_id' => auth()->id(),
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
                'success_count' => $successCount,
                'error_count' => count($errors),
            ]);

            $alertType = count($errors) > 0 ? 'warning' : 'success';

            return redirect()->route('evaluations.index')
                ->with($alertType, $message);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la mise à jour des évaluations: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des évaluations.')
                ->withInput();
        }
    }

    /**
     * Remove evaluations for a specific EC and exam
     */
    public function destroy($code_ec, $code_examen)
    {
        try {
            DB::beginTransaction();

            $evaluations = Evaluation::where([
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
            ])->get();

            if ($evaluations->isEmpty()) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Aucune évaluation trouvée pour ces critères.');
            }

            $count = $evaluations->count();

            // Sauvegarder pour les logs
            $evaluationData = $evaluations->load(['ec', 'examen', 'user'])->toArray();

            Evaluation::where([
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
            ])->delete();

            DB::commit();

            Log::info('Évaluations supprimées en masse', [
                'user_id' => auth()->id(),
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
                'count' => $count,
                'deleted_data' => $evaluationData,
            ]);

            return redirect()->route('evaluations.index')
                ->with('success', "{$count} évaluation(s) supprimée(s) avec succès.");

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression des évaluations: '.$e->getMessage(), [
                'code_ec' => $code_ec,
                'code_examen' => $code_examen,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression des évaluations.');
        }
    }

    /**
     * Calculate and display averages for UE, semester and year
     */
    public function moyennes(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code_user' => 'required|exists:users,code_user',
                'code_annee' => 'required|exists:anneescolaire,code_annee',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $etudiant = User::findOrFail($request->code_user);
            $annee = AnneeScolaire::findOrFail($request->code_annee);

            // Récupérer les inscriptions de l'étudiant pour cette année
            $inscriptions = Inscription::where([
                'code_user' => $request->code_user,
                'code_annee' => $request->code_annee,
                'statut_ins' => 1,
            ])->with('filiereNiveaux.niveau.semestres.ues.ecs')->get();

            $resultats = [];

            foreach ($inscriptions as $inscription) {
                foreach ($inscription->filiereNiveaux as $filiereNiveau) {
                    $niveau = $filiereNiveau->niveau;

                    foreach ($niveau->semestres as $semestre) {
                        $semestreData = [
                            'semestre' => $semestre,
                            'ues' => [],
                            'moyenne_semestre' => 0,
                            'total_credits' => 0,
                            'credits_obtenus' => 0,
                        ];

                        foreach ($semestre->ues as $ue) {
                            $ueData = [
                                'ue' => $ue,
                                'ecs' => [],
                                'moyenne_ue' => 0,
                                'total_credits_ue' => 0,
                                'credits_obtenus_ue' => 0,
                            ];

                            foreach ($ue->ecs as $ec) {
                                // Récupérer les évaluations pour cet EC
                                $evaluations = Evaluation::where([
                                    'code_ec' => $ec->code_ec,
                                    'code_user' => $request->code_user,
                                ])->with('examen.sessionExamen')->get();

                                if ($evaluations->isNotEmpty()) {
                                    $moyenne_ec = $evaluations->avg('note_eval');
                                    $credits_obtenus = $moyenne_ec >= 10 ? $ec->credit_ec : 0;

                                    $ueData['ecs'][] = [
                                        'ec' => $ec,
                                        'evaluations' => $evaluations,
                                        'moyenne' => round($moyenne_ec, 2),
                                        'credits_obtenus' => $credits_obtenus,
                                    ];

                                    $ueData['total_credits_ue'] += $ec->credit_ec;
                                    $ueData['credits_obtenus_ue'] += $credits_obtenus;
                                }
                            }

                            // Calculer la moyenne de l'UE (pondérée par les crédits)
                            if ($ueData['total_credits_ue'] > 0) {
                                $somme_ponderee = 0;
                                foreach ($ueData['ecs'] as $ecData) {
                                    $somme_ponderee += $ecData['moyenne'] * $ecData['ec']->credit_ec;
                                }
                                $ueData['moyenne_ue'] = round($somme_ponderee / $ueData['total_credits_ue'], 2);
                            }

                            if (! empty($ueData['ecs'])) {
                                $semestreData['ues'][] = $ueData;
                                $semestreData['total_credits'] += $ueData['total_credits_ue'];
                                $semestreData['credits_obtenus'] += $ueData['credits_obtenus_ue'];
                            }
                        }

                        // Calculer la moyenne du semestre
                        if ($semestreData['total_credits'] > 0) {
                            $somme_ponderee_sem = 0;
                            foreach ($semestreData['ues'] as $ueData) {
                                $somme_ponderee_sem += $ueData['moyenne_ue'] * $ueData['total_credits_ue'];
                            }
                            $semestreData['moyenne_semestre'] = round($somme_ponderee_sem / $semestreData['total_credits'], 2);
                        }

                        if (! empty($semestreData['ues'])) {
                            $resultats[] = $semestreData;
                        }
                    }
                }
            }

            // Calculer la moyenne générale de l'année
            $total_credits_annee = array_sum(array_column($resultats, 'total_credits'));
            $moyenne_annee = 0;

            if ($total_credits_annee > 0) {
                $somme_ponderee_annee = 0;
                foreach ($resultats as $semestreData) {
                    $somme_ponderee_annee += $semestreData['moyenne_semestre'] * $semestreData['total_credits'];
                }
                $moyenne_annee = round($somme_ponderee_annee / $total_credits_annee, 2);
            }

            return view('evaluations.moyennes', compact(
                'etudiant', 'annee', 'resultats', 'moyenne_annee', 'total_credits_annee'
            ));

        } catch (Throwable $e) {
            Log::error('Erreur lors du calcul des moyennes: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du calcul des moyennes.')
                ->withInput();
        }
    }

    /**
     * Get students enrolled in a specific EC
     */
    private function getEtudiantsByEc($code_ec)
    {
        try {
            return User::whereHas('etudiantEcs', function ($q) use ($code_ec) {
                $q->where('code_ec', $code_ec);
            })->orderBy('name')->get();

        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des étudiants par EC: '.$e->getMessage(), [
                'code_ec' => $code_ec,
            ]);

            return collect();
        }
    }

    /**
     * API endpoint to get students by EC
     */
    public function getEtudiantsByEcApi($code_ec)
    {
        try {
            $etudiants = $this->getEtudiantsByEc($code_ec);

            return response()->json([
                'success' => true,
                'etudiants' => $etudiants->map(function ($etudiant) {
                    return [
                        'code_user' => $etudiant->code_user,
                        'name' => $etudiant->name,
                        'email' => $etudiant->email,
                    ];
                }),
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur API récupération étudiants par EC: '.$e->getMessage(), [
                'code_ec' => $code_ec,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des étudiants',
            ], 500);
        }
    }

    /**
     * Export evaluations to CSV
     */
    public function export(Request $request)
    {
        try {
            $filename = 'evaluations_'.now()->format('Y-m-d_H-i-s').'.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $query = Evaluation::with(['ec.ue.semestre', 'examen.sessionExamen', 'user']);

            // Appliquer les mêmes filtres que pour l'index
            if ($request->filled('session')) {
                $query->whereHas('examen.sessionExamen', function ($q) use ($request) {
                    $q->where('code_session', $request->session);
                });
            }

            if ($request->filled('ec')) {
                $query->where('code_ec', $request->ec);
            }

            if ($request->filled('etudiant')) {
                $query->where('code_user', $request->etudiant);
            }

            $evaluations = $query->orderBy('date_evaluation', 'desc')->get();

            $callback = function () use ($evaluations) {
                $file = fopen('php://output', 'w');

                // En-têtes CSV
                fputcsv($file, [
                    'Date Évaluation',
                    'Étudiant',
                    'Email',
                    'EC',
                    'UE',
                    'Semestre',
                    'Session',
                    'Type Examen',
                    'Note',
                    'Date Saisie',
                ]);

                // Données
                foreach ($evaluations as $evaluation) {
                    fputcsv($file, [
                        $evaluation->date_evaluation,
                        $evaluation->user->name ?? '',
                        $evaluation->user->email ?? '',
                        $evaluation->ec->intitule_ec ?? '',
                        $evaluation->ec->ue->intitule_ue ?? '',
                        $evaluation->ec->ue->semestre->label_sem ?? '',
                        $evaluation->examen->sessionExamen->label_session ?? '',
                        $evaluation->examen->type_evaluation ?? '',
                        $evaluation->note_eval,
                        $evaluation->date_evalu,
                    ]);
                }

                fclose($file);
            };

            Log::info('Export des évaluations effectué', [
                'user_id' => auth()->id(),
                'count' => $evaluations->count(),
                'filters' => $request->all(),
            ]);

            return response()->stream($callback, 200, $headers);

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'export des évaluations: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'export des évaluations.');
        }
    }
}
