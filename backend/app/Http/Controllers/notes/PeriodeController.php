<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\notes\Periode;
use App\Models\notes\Salle;
use App\Models\notes\Ec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource with calendar view
     */
    public function index(Request $request)
    {
        try {
            $query = Periode::with(['salle', 'ec.ue.semestre']);

            // Filtres
            if ($request->filled('salle')) {
                $query->where('code_salle', $request->salle);
            }

            if ($request->filled('ec')) {
                $query->where('code_ec', $request->ec);
            }

            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $query->whereBetween('debut_periode', [
                    $request->date_debut,
                    $request->date_fin
                ]);
            }

            // Vue par défaut : semaine courante
            if (!$request->filled('date_debut') && !$request->filled('date_fin')) {
                $startOfWeek = now()->startOfWeek();
                $endOfWeek = now()->endOfWeek();
                $query->whereBetween('debut_periode', [$startOfWeek, $endOfWeek]);
            }

            $periodes = $query->orderBy('debut_periode')->get();

            // Données pour les filtres
            $salles = Salle::where('etat_salle', true)->orderBy('code_salle')->get();
            $ecs = Ec::with('ue')->orderBy('intitule_ec')->get();

            // Préparer les données pour le calendrier
            $evenements = $periodes->map(function ($periode) {
                return [
                    'id' => $periode->code_salle . '-' . $periode->code_ec,
                    'title' => $periode->ec->intitule_ec ?? 'EC Inconnu',
                    'start' => $periode->debut_periode,
                    'end' => $periode->fin_periode,
                    'backgroundColor' => $this->getColorBySalle($periode->code_salle),
                    'borderColor' => $this->getColorBySalle($periode->code_salle),
                    'extendedProps' => [
                        'salle' => $periode->salle->code_salle ?? '',
                        'ec' => $periode->ec->intitule_ec ?? '',
                        'ue' => $periode->ec->ue->intitule_ue ?? '',
                        'semestre' => $periode->ec->ue->semestre->label_sem ?? '',
                        'duree' => $periode->duree_periode,
                        'capacite' => $periode->salle->nb_place_salle ?? 0
                    ]
                ];
            });

            // Statistiques
            $stats = $this->getStatisticsPeriodes($periodes);

            return view('sige_app.backend.gestion_notes.periode.index', compact(
                'periodes', 'salles', 'ecs', 'evenements', 'stats'
            ));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage des périodes: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du chargement des périodes.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $salles = Salle::where('etat_salle', true)->orderBy('code_salle')->get();
            $ecs = Ec::with(['ue.semestre', 'assignations.classe'])
                ->orderBy('intitule_ec')
                ->get();

            return view('sige_app.backend.gestion_notes.periode.create', compact('salles', 'ecs'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création de période: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('periodes.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_salle' => 'required|exists:salle,code_salle',
            'code_ec' => 'required|exists:ec,code_ec',
            'debut_periode' => 'required|date|after_or_equal:now',
            'fin_periode' => 'required|date|after:debut_periode',
            'jour_periode' => 'required|integer|min:1|max:7',
            'duree_periode' => 'required|integer|min:30|max:480',
        ], [
            'code_salle.required' => 'La salle est obligatoire.',
            'code_salle.exists' => 'La salle sélectionnée n\'existe pas.',
            'code_ec.required' => 'L\'élément constitutif est obligatoire.',
            'code_ec.exists' => 'L\'élément constitutif sélectionné n\'existe pas.',
            'debut_periode.required' => 'La date de début est obligatoire.',
            'debut_periode.after_or_equal' => 'La date de début doit être dans le futur.',
            'fin_periode.required' => 'La date de fin est obligatoire.',
            'fin_periode.after' => 'La date de fin doit être postérieure à la date de début.',
            'jour_periode.required' => 'Le jour de la semaine est obligatoire.',
            'jour_periode.between' => 'Le jour doit être compris entre 1 (lundi) et 7 (dimanche).',
            'duree_periode.required' => 'La durée est obligatoire.',
            'duree_periode.between' => 'La durée doit être comprise entre 30 et 480 minutes.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Vérifier la disponibilité de la salle
            $conflictSalle = $this->checkSalleConflict(
                $request->code_salle,
                $request->debut_periode,
                $request->fin_periode
            );

            if ($conflictSalle) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'La salle est déjà occupée pendant cette période.')
                    ->withInput();
            }

            // Vérifier la capacité de la salle par rapport au nombre d'étudiants
            $salle = Salle::findOrFail($request->code_salle);
            $nombreEtudiants = $this->getNombreEtudiantsEc($request->code_ec);

            if ($nombreEtudiants > $salle->nb_place_salle) {
                return redirect()->back()
                    ->with('warning', 
                        "Attention : La salle a une capacité de {$salle->nb_place_salle} places " .
                        "mais l'EC compte {$nombreEtudiants} étudiants. Voulez-vous continuer ?"
                    )
                    ->withInput();
            }

            // Générer un code de période unique
            $codePeriode = $this->generateCodePeriode();

            Periode::create([
                'code_salle' => $request->code_salle,
                'code_ec' => $request->code_ec,
                'code_periode' => $codePeriode,
                'debut_periode' => $request->debut_periode,
                'fin_periode' => $request->fin_periode,
                'jour_periode' => $request->jour_periode,
                'duree_periode' => $request->duree_periode,
            ]);

            DB::commit();

            Log::info('Période créée avec succès', [
                'code_periode' => $codePeriode,
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);

            return redirect()->route('periodes.index')
                ->with('success', 'Période d\'examen créée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la création de la période: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de la période.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_salle, $code_ec)
    {
        try {
            $periode = Periode::with([
                'salle',
                'ec.ue.semestre',
                'ec.assignations.personnel',
                'ec.evaluations.user'
            ])->where([
                'code_salle' => $code_salle,
                'code_ec' => $code_ec
            ])->firstOrFail();

            // Statistiques de la période
            $stats = [
                'duree_heures' => round($periode->duree_periode / 60, 2),
                'capacite_salle' => $periode->salle->nb_place_salle ?? 0,
                'etudiants_inscrits' => $this->getNombreEtudiantsEc($code_ec),
                'taux_occupation' => $this->calculateTauxOccupation($periode),
                'enseignants_assignes' => $periode->ec->assignations->count()
            ];

            // Autres périodes pour cette salle
            $autresPeriodesSalle = Periode::with('ec')
                ->where('code_salle', $code_salle)
                ->where(function ($q) use ($code_ec) {
                    $q->where('code_ec', '!=', $code_ec);
                })
                ->whereBetween('debut_periode', [
                    now()->startOfWeek(),
                    now()->endOfWeek()->addWeek()
                ])
                ->orderBy('debut_periode')
                ->get();

            return view('sige_app.backend.gestion_notes.periode.show', compact('periode', 'stats', 'autresPeriodesSalle'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage de la période: ' . $e->getMessage(), [
                'code_salle' => $code_salle,
                'code_ec' => $code_ec,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('periodes.index')
                ->with('error', 'Période introuvable ou erreur lors du chargement.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_salle, $code_ec)
    {
        try {
            $periode = Periode::with(['salle', 'ec'])->where([
                'code_salle' => $code_salle,
                'code_ec' => $code_ec
            ])->firstOrFail();

            $salles = Salle::where('etat_salle', true)->orderBy('code_salle')->get();
            $ecs = Ec::with(['ue.semestre'])->orderBy('intitule_ec')->get();

            return view('sige_app.backend.gestion_notes.periode.edit', compact('periode', 'salles', 'ecs'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification: ' . $e->getMessage(), [
                'code_salle' => $code_salle,
                'code_ec' => $code_ec,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('periodes.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_salle, $code_ec)
    {
        $validator = Validator::make($request->all(), [
            'new_code_salle' => 'required|exists:salle,code_salle',
            'new_code_ec' => 'required|exists:ec,code_ec',
            'debut_periode' => 'required|date',
            'fin_periode' => 'required|date|after:debut_periode',
            'jour_periode' => 'required|integer|min:1|max:7',
            'duree_periode' => 'required|integer|min:30|max:480',
        ], [
            'new_code_salle.required' => 'La salle est obligatoire.',
            'new_code_salle.exists' => 'La salle sélectionnée n\'existe pas.',
            'new_code_ec.required' => 'L\'élément constitutif est obligatoire.',
            'new_code_ec.exists' => 'L\'élément constitutif sélectionné n\'existe pas.',
            'debut_periode.required' => 'La date de début est obligatoire.',
            'fin_periode.required' => 'La date de fin est obligatoire.',
            'fin_periode.after' => 'La date de fin doit être postérieure à la date de début.',
            'jour_periode.required' => 'Le jour de la semaine est obligatoire.',
            'jour_periode.between' => 'Le jour doit être compris entre 1 (lundi) et 7 (dimanche).',
            'duree_periode.required' => 'La durée est obligatoire.',
            'duree_periode.between' => 'La durée doit être comprise entre 30 et 480 minutes.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $periode = Periode::where([
                'code_salle' => $code_salle,
                'code_ec' => $code_ec
            ])->firstOrFail();

            // Vérifier les conflits si la salle ou la période change
            if ($request->new_code_salle != $code_salle || 
                $request->debut_periode != $periode->debut_periode || 
                $request->fin_periode != $periode->fin_periode) {
                
                $conflictSalle = $this->checkSalleConflict(
                    $request->new_code_salle,
                    $request->debut_periode,
                    $request->fin_periode,
                    [$code_salle, $code_ec] // Exclure la période actuelle
                );

                if ($conflictSalle) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'La nouvelle salle est déjà occupée pendant cette période.')
                        ->withInput();
                }
            }

            // Sauvegarder les anciennes valeurs pour les logs
            $oldData = $periode->toArray();

            // Si la salle ou l'EC change, supprimer l'ancienne et créer une nouvelle
            if ($request->new_code_salle != $code_salle || $request->new_code_ec != $code_ec) {
                $periode->delete();
                
                Periode::create([
                    'code_salle' => $request->new_code_salle,
                    'code_ec' => $request->new_code_ec,
                    'code_periode' => $periode->code_periode,
                    'debut_periode' => $request->debut_periode,
                    'fin_periode' => $request->fin_periode,
                    'jour_periode' => $request->jour_periode,
                    'duree_periode' => $request->duree_periode,
                ]);
            } else {
                // Sinon, mettre à jour directement
                $periode->update([
                    'debut_periode' => $request->debut_periode,
                    'fin_periode' => $request->fin_periode,
                    'jour_periode' => $request->jour_periode,
                    'duree_periode' => $request->duree_periode,
                ]);
            }

            DB::commit();

            Log::info('Période modifiée avec succès', [
                'old_code_salle' => $code_salle,
                'old_code_ec' => $code_ec,
                'new_code_salle' => $request->new_code_salle,
                'new_code_ec' => $request->new_code_ec,
                'user_id' => auth()->id(),
                'old_data' => $oldData,
                'new_data' => $request->all()
            ]);

            return redirect()->route('periodes.index')
                ->with('success', 'Période d\'examen modifiée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la modification de la période: ' . $e->getMessage(), [
                'code_salle' => $code_salle,
                'code_ec' => $code_ec,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la modification de la période.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_salle, $code_ec)
    {
        try {
            DB::beginTransaction();

            $periode = Periode::with(['salle', 'ec'])->where([
                'code_salle' => $code_salle,
                'code_ec' => $code_ec
            ])->firstOrFail();

            // Vérifier si la période a déjà commencé
            if (Carbon::parse($periode->debut_periode)->isPast()) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer une période qui a déjà commencé.');
            }

            // Sauvegarder les données pour les logs
            $periodeData = $periode->toArray();

            $periode->delete();

            DB::commit();

            Log::info('Période supprimée avec succès', [
                'code_salle' => $code_salle,
                'code_ec' => $code_ec,
                'user_id' => auth()->id(),
                'deleted_data' => $periodeData
            ]);

            return redirect()->route('periodes.index')
                ->with('success', 'Période d\'examen supprimée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la suppression de la période: ' . $e->getMessage(), [
                'code_salle' => $code_salle,
                'code_ec' => $code_ec,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de la période.');
        }
    }

    /**
     * Get periods by date range (API endpoint for calendar)
     */
    public function getPeriodesByDateRange(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start' => 'required|date',
                'end' => 'required|date|after:start',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paramètres invalides'
                ], 400);
            }

            $periodes = Periode::with(['salle', 'ec.ue.semestre'])
                ->whereBetween('debut_periode', [$request->start, $request->end])
                ->get()
                ->map(function ($periode) {
                    return [
                        'id' => $periode->code_salle . '-' . $periode->code_ec,
                        'title' => $periode->ec->intitule_ec ?? 'EC Inconnu',
                        'start' => $periode->debut_periode,
                        'end' => $periode->fin_periode,
                        'backgroundColor' => $this->getColorBySalle($periode->code_salle),
                        'borderColor' => $this->getColorBySalle($periode->code_salle),
                        'extendedProps' => [
                            'salle' => $periode->salle->code_salle ?? '',
                            'ec' => $periode->ec->intitule_ec ?? '',
                            'ue' => $periode->ec->ue->intitule_ue ?? '',
                            'semestre' => $periode->ec->ue->semestre->label_sem ?? '',
                            'duree' => $periode->duree_periode,
                            'capacite' => $periode->salle->nb_place_salle ?? 0,
                            'url' => route('periodes.show', [
                                'code_salle' => $periode->code_salle,
                                'code_ec' => $periode->code_ec
                            ])
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'events' => $periodes
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des périodes par plage de dates: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des périodes'
            ], 500);
        }
    }

    /**
     * Check if a room is available for a specific period
     */
    public function checkDisponibiliteSalle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code_salle' => 'required|exists:salle,code_salle',
                'debut_periode' => 'required|date',
                'fin_periode' => 'required|date|after:debut_periode',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paramètres invalides'
                ], 400);
            }

            $conflict = $this->checkSalleConflict(
                $request->code_salle,
                $request->debut_periode,
                $request->fin_periode
            );

            return response()->json([
                'success' => true,
                'available' => !$conflict,
                'message' => $conflict ? 'Salle occupée pendant cette période' : 'Salle disponible'
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la vérification de disponibilité: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification'
            ], 500);
        }
    }

    /**
     * Mass delete periods
     */
    public function massDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periodes' => 'required|array|min:1',
            'periodes.*.code_salle' => 'required|exists:salle,code_salle',
            'periodes.*.code_ec' => 'required|exists:ec,code_ec',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errorCount = 0;
            $pastPeriods = 0;

            foreach ($request->periodes as $periodeData) {
                try {
                    $periode = Periode::where([
                        'code_salle' => $periodeData['code_salle'],
                        'code_ec' => $periodeData['code_ec']
                    ])->first();

                    if (!$periode) {
                        $errorCount++;
                        continue;
                    }

                    // Vérifier si la période a déjà commencé
                    if (Carbon::parse($periode->debut_periode)->isPast()) {
                        $pastPeriods++;
                        continue;
                    }

                    $periode->delete();
                    $successCount++;

                } catch (Throwable $e) {
                    $errorCount++;
                    Log::error('Erreur lors de la suppression en masse: ', [
                        'periode_data' => $periodeData,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            $message = "Suppression terminée : {$successCount} supprimée(s)";
            if ($pastPeriods > 0) {
                $message .= ", {$pastPeriods} période(s) déjà commencée(s) ignorée(s)";
            }
            if ($errorCount > 0) {
                $message .= ", {$errorCount} erreur(s)";
            }

            Log::info('Suppression en masse de périodes', [
                'user_id' => auth()->id(),
                'success_count' => $successCount,
                'past_periods' => $pastPeriods,
                'error_count' => $errorCount
            ]);

            $alertType = ($pastPeriods + $errorCount) > 0 ? 'warning' : 'success';

            return redirect()->route('periodes.index')
                ->with($alertType, $message);

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la suppression en masse: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression en masse.');
        }
    }

    /**
     * Check for room conflicts
     */
    private function checkSalleConflict($codeSalle, $debutPeriode, $finPeriode, $excludePeriode = null)
    {
        $query = Periode::where('code_salle', $codeSalle)
            ->where(function ($q) use ($debutPeriode, $finPeriode) {
                $q->whereBetween('debut_periode', [$debutPeriode, $finPeriode])
                  ->orWhereBetween('fin_periode', [$debutPeriode, $finPeriode])
                  ->orWhere(function ($subQuery) use ($debutPeriode, $finPeriode) {
                      $subQuery->where('debut_periode', '<=', $debutPeriode)
                               ->where('fin_periode', '>=', $finPeriode);
                  });
            });

        // Exclure une période spécifique (pour les mises à jour)
        if ($excludePeriode) {
            $query->where(function ($q) use ($excludePeriode) {
                $q->where('code_salle', '!=', $excludePeriode[0])
                  ->orWhere('code_ec', '!=', $excludePeriode[1]);
            });
        }

        return $query->exists();
    }

    /**
     * Get number of students for an EC
     */
    private function getNombreEtudiantsEc($codeEc)
    {
        try {
            return DB::table('etudiant_ec')
                ->where('code_ec', $codeEc)
                ->count();
        } catch (Throwable $e) {
            Log::warning('Erreur lors du calcul du nombre d\'étudiants: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Generate unique period code
     */
    private function generateCodePeriode()
    {
        do {
            $code = random_int(100000, 999999);
        } while (Periode::where('code_periode', $code)->exists());

        return $code;
    }

    /**
     * Get color by room for calendar display
     */
    private function getColorBySalle($codeSalle)
    {
        $colors = [
            '#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6',
            '#1abc9c', '#34495e', '#e67e22', '#95a5a6', '#16a085'
        ];
        
        $index = abs(crc32($codeSalle)) % count($colors);
        return $colors[$index];
    }

    /**
     * Calculate room occupation rate
     */
    private function calculateTauxOccupation($periode)
    {
        $capaciteSalle = $periode->salle->nb_place_salle ?? 0;
        $nombreEtudiants = $this->getNombreEtudiantsEc($periode->code_ec);
        
        if ($capaciteSalle == 0) {
            return 0;
        }
        
        return round(($nombreEtudiants / $capaciteSalle) * 100, 2);
    }

    /**
     * Get statistics for periods
     */
    private function getStatisticsPeriodes($periodes)
    {
        return [
            'total_periodes' => $periodes->count(),
            'periodes_aujourdhui' => $periodes->filter(function ($periode) {
                return Carbon::parse($periode->debut_periode)->isToday();
            })->count(),
            'periodes_cette_semaine' => $periodes->filter(function ($periode) {
                return Carbon::parse($periode->debut_periode)->isCurrentWeek();
            })->count(),
            'salles_utilisees' => $periodes->unique('code_salle')->count(),
            'duree_totale' => $periodes->sum('duree_periode'),
            'taux_occupation_moyen' => round($periodes->avg(function ($periode) {
                return $this->calculateTauxOccupation($periode);
            }) ?? 0, 2)
        ];
    }
}