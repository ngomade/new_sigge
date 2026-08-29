<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\notes\Assignation;
use App\Models\notes\Classe;
use App\Models\notes\Ec;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AssignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Assignation::with(['ec.ue.semestre', 'classe', 'personnel']);

            // Filtres
            if ($request->filled('classe')) {
                $query->where('code_class', $request->classe);
            }

            if ($request->filled('personnel')) {
                $query->where('code_pers', $request->personnel);
            }

            if ($request->filled('ec')) {
                $query->where('code_ec', $request->ec);
            }

            $assignations = $query->paginate(15);

            // Données pour les filtres
            $classes = Classe::orderBy('label_class')->get();
            $personnels = Personnel::orderBy('nom_pers')->get();
            $ecs = Ec::with('ue')->orderBy('intitule_ec')->get();

            return view('sige_app.backend.assignation.assignation_index', compact('assignations', 'classes', 'personnels', 'ecs'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage des assignations: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du chargement des assignations.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $classes = Classe::orderBy('label_class')->get();
            $personnels = Personnel::orderBy('nom_pers')->get();
            $ecs = Ec::with(['ue.semestre'])->orderBy('intitule_ec')->get();

            return view('sige_app.backend.assignation.assignation_create', compact('classes', 'personnels', 'ecs'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création d\'assignation: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('assignations.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_ec' => 'required|exists:ec,code_ec',
            'code_pers' => 'required|exists:personnel,code_pers',
            'code_class' => 'required|exists:classes,code_class',
        ], [
            'code_ec.required' => 'L\'élément constitutif est obligatoire.',
            'code_ec.exists' => 'L\'élément constitutif sélectionné n\'existe pas.',
            'code_pers.required' => 'L\'enseignant est obligatoire.',
            'code_pers.exists' => 'L\'enseignant sélectionné n\'existe pas.',
            'code_class.required' => 'La classe est obligatoire.',
            'code_class.exists' => 'La classe sélectionnée n\'existe pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Vérifier si l'assignation existe déjà
            $existingAssignation = Assignation::where([
                'code_ec' => $request->code_ec,
                'code_pers' => $request->code_pers,
                'code_class' => $request->code_class,
            ])->first();

            if ($existingAssignation) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Cette assignation existe déjà.')
                    ->withInput();
            }

            // Vérifier la disponibilité de l'enseignant pour cette classe
            $conflictingAssignations = Assignation::where([
                'code_pers' => $request->code_pers,
                'code_class' => $request->code_class,
            ])->with('ec.ue.semestre')->get();

            // Récupérer le semestre de l'EC à assigner
            $newEc = Ec::with('ue.semestre')->findOrFail($request->code_ec);

            foreach ($conflictingAssignations as $assignation) {
                if ($assignation->ec->ue->semestre->code_sem === $newEc->ue->semestre->code_sem) {
                    // Permettre l'assignation dans le même semestre
                    continue;
                }
            }

            $assignation = Assignation::create([
                'code_ec' => $request->code_ec,
                'code_pers' => $request->code_pers,
                'code_class' => $request->code_class,
            ]);

            DB::commit();

            Log::info('Assignation créée avec succès', [
                'assignation_id' => $assignation->code_ass,
                'user_id' => auth()->id(),
                'data' => $request->only(['code_ec', 'code_pers', 'code_class']),
            ]);

            return redirect()->route('assignations.index')
                ->with('success', 'Assignation créée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création de l\'assignation: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'assignation.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_ass)
    {
        try {
            $assignation = Assignation::with([
                'ec.ue.semestre',
                'classe',
                'personnel',
                'ec.evaluations.examen.sessionExamen',
            ])->findOrFail($code_ass);

            // Statistiques pour cette assignation
            $stats = [
                'total_evaluations' => $assignation->ec->evaluations->count(),
                'evaluations_recentes' => $assignation->ec->evaluations()
                    ->where('date_evaluation', '>=', now()->subMonth())
                    ->count(),
                'moyenne_generale' => $assignation->ec->evaluations()
                    ->avg('note_eval') ?? 0,
            ];

            return view('sige_app.backend.assignation.assignation_show', compact('assignation', 'stats'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage de l\'assignation: '.$e->getMessage(), [
                'assignation_id' => $code_ass,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('assignations.index')
                ->with('error', 'Assignation introuvable ou erreur lors du chargement.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_ass)
    {
        try {
            $assignation = Assignation::with(['ec', 'classe', 'personnel'])->findOrFail($code_ass);

            $classes = Classe::orderBy('label_class')->get();
            $personnels = Personnel::where('statut_pers', 1)->orderBy('nom_pers')->get();
            $ecs = Ec::with(['ue.semestre'])->orderBy('intitule_ec')->get();

            return view('sige_app.backend.assignation.assignation_edit', compact('assignation', 'classes', 'personnels', 'ecs'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification: '.$e->getMessage(), [
                'assignation_id' => $code_ass,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('assignations.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_ass)
    {
        $validator = Validator::make($request->all(), [
            'code_ec' => 'required|exists:ec,code_ec',
            'code_pers' => 'required|exists:personnel,code_pers',
            'code_class' => 'required|exists:classes,code_class',
        ], [
            'code_ec.required' => 'L\'élément constitutif est obligatoire.',
            'code_ec.exists' => 'L\'élément constitutif sélectionné n\'existe pas.',
            'code_pers.required' => 'L\'enseignant est obligatoire.',
            'code_pers.exists' => 'L\'enseignant sélectionné n\'existe pas.',
            'code_class.required' => 'La classe est obligatoire.',
            'code_class.exists' => 'La classe sélectionnée n\'existe pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $assignation = Assignation::findOrFail($code_ass);

            // Vérifier si une autre assignation existe avec ces nouvelles données
            $existingAssignation = Assignation::where([
                'code_ec' => $request->code_ec,
                'code_pers' => $request->code_pers,
                'code_class' => $request->code_class,
            ])->where('code_ass', '!=', $code_ass)->first();

            if ($existingAssignation) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Une assignation avec ces paramètres existe déjà.')
                    ->withInput();
            }

            // Sauvegarder les anciennes valeurs pour les logs
            $oldData = $assignation->toArray();

            $assignation->update([
                'code_ec' => $request->code_ec,
                'code_pers' => $request->code_pers,
                'code_class' => $request->code_class,
            ]);

            DB::commit();

            Log::info('Assignation modifiée avec succès', [
                'assignation_id' => $code_ass,
                'user_id' => auth()->id(),
                'old_data' => $oldData,
                'new_data' => $request->only(['code_ec', 'code_pers', 'code_class']),
            ]);

            return redirect()->route('assignations.index')
                ->with('success', 'Assignation modifiée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la modification de l\'assignation: '.$e->getMessage(), [
                'assignation_id' => $code_ass,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la modification de l\'assignation.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_ass)
    {
        try {
            DB::beginTransaction();

            $assignation = Assignation::findOrFail($code_ass);

            // Vérifier s'il y a des évaluations liées à cette assignation
            $hasEvaluations = $assignation->ec->evaluations()->exists();

            if ($hasEvaluations) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette assignation car des évaluations y sont liées.');
            }

            // Sauvegarder les données pour les logs
            $assignationData = $assignation->load(['ec', 'classe', 'personnel'])->toArray();

            $assignation->delete();

            DB::commit();

            Log::info('Assignation supprimée avec succès', [
                'assignation_id' => $code_ass,
                'user_id' => auth()->id(),
                'deleted_data' => $assignationData,
            ]);

            return redirect()->route('assignations.index')
                ->with('success', 'Assignation supprimée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression de l\'assignation: '.$e->getMessage(), [
                'assignation_id' => $code_ass,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'assignation.');
        }
    }

    /**
     * Assignation en masse d'un enseignant à plusieurs EC d'une classe
     */
    public function massAssign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_pers' => 'required|exists:personnel,code_pers',
            'code_class' => 'required|exists:classes,code_class',
            'code_ecs' => 'required|array|min:1',
            'code_ecs.*' => 'exists:ec,code_ec',
        ], [
            'code_pers.required' => 'L\'enseignant est obligatoire.',
            'code_class.required' => 'La classe est obligatoire.',
            'code_ecs.required' => 'Au moins un EC doit être sélectionné.',
            'code_ecs.min' => 'Au moins un EC doit être sélectionné.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $successCount = 0;
            $duplicateCount = 0;
            $errors = [];

            foreach ($request->code_ecs as $code_ec) {
                try {
                    // Vérifier si l'assignation existe déjà
                    $exists = Assignation::where([
                        'code_ec' => $code_ec,
                        'code_pers' => $request->code_pers,
                        'code_class' => $request->code_class,
                    ])->exists();

                    if ($exists) {
                        $duplicateCount++;

                        continue;
                    }

                    Assignation::create([
                        'code_ec' => $code_ec,
                        'code_pers' => $request->code_pers,
                        'code_class' => $request->code_class,
                    ]);

                    $successCount++;

                } catch (Throwable $e) {
                    $ec = Ec::find($code_ec);
                    $errors[] = "Erreur pour l'EC ".($ec ? $ec->intitule_ec : $code_ec);

                    Log::error('Erreur lors de l\'assignation en masse pour EC: '.$code_ec, [
                        'error' => $e->getMessage(),
                        'code_pers' => $request->code_pers,
                        'code_class' => $request->code_class,
                    ]);
                }
            }

            DB::commit();

            $message = "Assignation en masse terminée : {$successCount} créée(s)";
            if ($duplicateCount > 0) {
                $message .= ", {$duplicateCount} existait(ent) déjà";
            }
            if (count($errors) > 0) {
                $message .= ', '.count($errors).' erreur(s)';
            }

            Log::info('Assignation en masse effectuée', [
                'user_id' => auth()->id(),
                'code_pers' => $request->code_pers,
                'code_class' => $request->code_class,
                'success_count' => $successCount,
                'duplicate_count' => $duplicateCount,
                'error_count' => count($errors),
            ]);

            $alertType = count($errors) > 0 ? 'warning' : 'success';

            return redirect()->route('assignations.index')
                ->with($alertType, $message);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de l\'assignation en masse: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'assignation en masse.')
                ->withInput();
        }
    }

    /**
     * Obtenir les EC disponibles pour une classe donnée
     */
    public function getEcsByClasse($code_class)
    {
        try {
            $classe = Classe::findOrFail($code_class);

            // Récupérer les niveaux de la classe
            $niveaux = $classe->niveaux()->with('semestres.ues.ecs')->get();

            $ecs = collect();
            foreach ($niveaux as $niveau) {
                foreach ($niveau->semestres as $semestre) {
                    foreach ($semestre->ues as $ue) {
                        $ecs = $ecs->merge($ue->ecs);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'ecs' => $ecs->unique('code_ec')->values()->map(function ($ec) {
                    return [
                        'code_ec' => $ec->code_ec,
                        'intitule_ec' => $ec->intitule_ec,
                        'ue_intitule' => $ec->ue->intitule_ue ?? '',
                        'semestre_label' => $ec->ue->semestre->label_sem ?? '',
                    ];
                }),
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des EC par classe: '.$e->getMessage(), [
                'code_class' => $code_class,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des EC',
            ], 500);
        }
    }

    /**
     * Obtenir les assignations d'un enseignant
     */
    public function getAssignationsByPersonnel($code_pers)
    {
        try {
            $assignations = Assignation::with(['ec.ue.semestre', 'classe'])
                ->where('code_pers', $code_pers)
                ->get()
                ->map(function ($assignation) {
                    return [
                        'code_ass' => $assignation->code_ass,
                        'ec_intitule' => $assignation->ec->intitule_ec,
                        'ue_intitule' => $assignation->ec->ue->intitule_ue ?? '',
                        'semestre_label' => $assignation->ec->ue->semestre->label_sem ?? '',
                        'classe_label' => $assignation->classe->label_class,
                        'created_at' => $assignation->created_at->format('d/m/Y'),
                    ];
                });

            return response()->json([
                'success' => true,
                'assignations' => $assignations,
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des assignations par personnel: '.$e->getMessage(), [
                'code_pers' => $code_pers,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des assignations',
            ], 500);
        }
    }
}
