<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\concours\User;
use App\Models\Filiere;
use App\Models\notes\Classe;
use App\Models\notes\Evaluation;
use App\Models\notes\Inscription;
use App\Models\notes\Niveau;
use App\Models\notes\Ue;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConsultationNotesController extends Controller
{
    /**
     * 2.7.1 - Visualisation des notes par étudiant
     */

    /**
     * Obtenir toutes les notes d'un étudiant
     */
    public function getNotesEtudiant($codeUser)
    {
        try {
            $etudiant = User::findOrFail($codeUser);

            $notes = Evaluation::with([
                'ec.ue.semestre',
                'examen.sessionExamen',
                'user',
            ])
                ->where('code_user', $codeUser)
                ->orderBy('date_evaluation', 'desc')
                ->get();

            // Organiser les notes par semestre et UE
            $notesOrganisees = $this->organiserNotesParSemestre($notes);

            return response()->json([
                'success' => true,
                'data' => [
                    'etudiant' => $etudiant->only(['code_user', 'name', 'email']),
                    'notes' => $notesOrganisees,
                    'statistiques' => $this->calculerStatistiquesEtudiant($notes),
                ],
                'message' => 'Notes de l\'étudiant récupérées avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des notes',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les notes d'un étudiant pour un semestre spécifique
     */
    public function getNotesEtudiantSemestre($codeUser, $codeSemestre)
    {
        try {
            $notes = Evaluation::with([
                'ec.ue',
                'examen.sessionExamen',
            ])
                ->whereHas('ec.ue', function ($query) use ($codeSemestre) {
                    $query->where('code_sem', $codeSemestre);
                })
                ->where('code_user', $codeUser)
                ->orderBy('date_evaluation', 'desc')
                ->get();

            $moyennes = $this->calculerMoyennesSemestre($notes, $codeUser, $codeSemestre);

            return response()->json([
                'success' => true,
                'data' => [
                    'notes' => $notes,
                    'moyennes' => $moyennes,
                ],
                'message' => 'Notes du semestre récupérées avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des notes du semestre',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 2.7.2 - Consultation des bulletins et des relevés de notes
     */

    /**
     * Générer le bulletin d'un étudiant pour un semestre
     */
    public function getBulletinEtudiant($codeUser, $codeSemestre, $codeAnnee)
    {
        try {
            $etudiant = User::findOrFail($codeUser);

            // Vérifier l'inscription de l'étudiant
            $inscription = Inscription::where('code_user', $codeUser)
                ->where('code_annee', $codeAnnee)
                ->first();

            if (! $inscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant non inscrit pour cette année',
                ], 404);
            }

            // Récupérer les UE du semestre avec les notes
            $ues = Ue::with([
                'ecs.evaluations' => function ($query) use ($codeUser) {
                    $query->where('code_user', $codeUser);
                },
                'ecs.evaluations.examen.sessionExamen',
            ])
                ->where('code_sem', $codeSemestre)
                ->get();

            $bulletin = $this->construireBulletin($etudiant, $ues, $codeSemestre, $codeAnnee);

            return response()->json([
                'success' => true,
                'data' => $bulletin,
                'message' => 'Bulletin généré avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du bulletin',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Générer le relevé de notes complet d'un étudiant
     */
    public function getReleveNotesEtudiant($codeUser, $codeAnnee)
    {
        try {
            $etudiant = User::findOrFail($codeUser);

            // Récupérer toutes les notes de l'année
            $evaluations = Evaluation::with([
                'ec.ue.semestre',
                'examen.sessionExamen',
            ])
                ->where('code_user', $codeUser)
                ->whereHas('examen.sessionExamen', function ($query) use ($codeAnnee) {
                    $query->where('code_annee', $codeAnnee);
                })
                ->get();

            $releve = $this->construireReleveNotes($etudiant, $evaluations, $codeAnnee);

            return response()->json([
                'success' => true,
                'data' => $releve,
                'message' => 'Relevé de notes généré avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du relevé',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 2.7.3 - Accès aux résultats par niveau, par classe et par filière
     */

    /**
     * Obtenir les résultats par classe
     */
    public function getResultatsClasse($codeClasse, $codeSemestre, $codeAnnee)
    {
        try {
            $classe = Classe::with('user')->findOrFail($codeClasse);

            // Récupérer les étudiants de la classe
            $etudiants = User::whereHas('inscriptions', function ($query) use ($codeClasse, $codeAnnee) {
                $query->where('code_annee', $codeAnnee)
                    ->whereHas('filiereNiveaux.niveau', function ($q) use ($codeClasse) {
                        $q->where('code_class', $codeClasse);
                    });
            })->get();

            $resultats = [];
            foreach ($etudiants as $etudiant) {
                $moyennes = $this->calculerMoyennesSemestre(
                    $this->getNotesEtudiantPourSemestre($etudiant->code_user, $codeSemestre),
                    $etudiant->code_user,
                    $codeSemestre
                );

                $resultats[] = [
                    'etudiant' => $etudiant->only(['code_user', 'name', 'email']),
                    'moyennes' => $moyennes,
                ];
            }

            // Statistiques de la classe
            $statistiques = $this->calculerStatistiquesClasse($resultats);

            return response()->json([
                'success' => true,
                'data' => [
                    'classe' => $classe,
                    'resultats' => $resultats,
                    'statistiques' => $statistiques,
                ],
                'message' => 'Résultats de la classe récupérés avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des résultats de classe',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les résultats par niveau
     */
    public function getResultatsNiveau($codeNiveau, $codeSemestre, $codeAnnee)
    {
        try {
            $niveau = Niveau::with('classe')->findOrFail($codeNiveau);

            $resultats = DB::table('users as u')
                ->join('inscription as i', 'u.code_user', '=', 'i.code_user')
                ->join('filiere_niveau as fn', 'i.code_ins', '=', 'fn.code_ins')
                ->join('evaluation as e', 'u.code_user', '=', 'e.code_user')
                ->join('ec', 'e.code_ec', '=', 'ec.code_ec')
                ->join('ue', 'ec.code_ue', '=', 'ue.code_ue')
                ->where('fn.code_niveau', $codeNiveau)
                ->where('i.code_annee', $codeAnnee)
                ->where('ue.code_sem', $codeSemestre)
                ->select([
                    'u.code_user',
                    'u.name',
                    'u.email',
                    DB::raw('AVG(e.note_eval) as moyenne_generale'),
                    DB::raw('COUNT(DISTINCT ec.code_ec) as nb_ec_evaluees'),
                ])
                ->groupBy('u.code_user', 'u.name', 'u.email')
                ->orderBy('moyenne_generale', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'niveau' => $niveau,
                    'resultats' => $resultats,
                    'statistiques' => $this->calculerStatistiquesNiveau($resultats),
                ],
                'message' => 'Résultats du niveau récupérés avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des résultats de niveau',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les résultats par filière
     */
    public function getResultatsFiliere($codeFiliere, $codeSemestre, $codeAnnee)
    {
        try {
            $filiere = Filiere::findOrFail($codeFiliere);

            $resultats = DB::table('users as u')
                ->join('inscription as i', 'u.code_user', '=', 'i.code_user')
                ->join('filiere_niveau as fn', 'i.code_ins', '=', 'fn.code_ins')
                ->join('evaluation as e', 'u.code_user', '=', 'e.code_user')
                ->join('ec', 'e.code_ec', '=', 'ec.code_ec')
                ->join('ue', 'ec.code_ue', '=', 'ue.code_ue')
                ->where('fn.code_filiere', $codeFiliere)
                ->where('i.code_annee', $codeAnnee)
                ->where('ue.code_sem', $codeSemestre)
                ->select([
                    'u.code_user',
                    'u.name',
                    'u.email',
                    'fn.code_niveau',
                    DB::raw('AVG(e.note_eval) as moyenne_generale'),
                    DB::raw('COUNT(DISTINCT ec.code_ec) as nb_ec_evaluees'),
                ])
                ->groupBy('u.code_user', 'u.name', 'u.email', 'fn.code_niveau')
                ->orderBy('moyenne_generale', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'filiere' => $filiere,
                    'resultats' => $resultats,
                    'statistiques' => $this->calculerStatistiquesFiliere($resultats),
                ],
                'message' => 'Résultats de la filière récupérés avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des résultats de filière',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir le classement général d'un semestre
     */
    public function getClassementSemestre($codeSemestre, $codeAnnee, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_classe' => 'nullable|string|exists:classes,code_class',
            'code_niveau' => 'nullable|string|exists:niveau,code_niveau',
            'code_filiere' => 'nullable|string|exists:filiere,code_filiere',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Paramètres invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $query = DB::table('users as u')
                ->join('inscription as i', 'u.code_user', '=', 'i.code_user')
                ->join('filiere_niveau as fn', 'i.code_ins', '=', 'fn.code_ins')
                ->join('evaluation as e', 'u.code_user', '=', 'e.code_user')
                ->join('ec', 'e.code_ec', '=', 'ec.code_ec')
                ->join('ue', 'ec.code_ue', '=', 'ue.code_ue')
                ->where('i.code_annee', $codeAnnee)
                ->where('ue.code_sem', $codeSemestre);

            // Appliquer les filtres
            if ($request->code_classe) {
                $query->join('niveau as n', 'fn.code_niveau', '=', 'n.code_niveau')
                    ->where('n.code_class', $request->code_classe);
            }

            if ($request->code_niveau) {
                $query->where('fn.code_niveau', $request->code_niveau);
            }

            if ($request->code_filiere) {
                $query->where('fn.code_filiere', $request->code_filiere);
            }

            $classement = $query->select([
                'u.code_user',
                'u.name',
                'u.email',
                'fn.code_niveau',
                'fn.code_filiere',
                DB::raw('AVG(e.note_eval) as moyenne_generale'),
                DB::raw('COUNT(DISTINCT ec.code_ec) as nb_ec_evaluees'),
                DB::raw('ROW_NUMBER() OVER (ORDER BY AVG(e.note_eval) DESC) as rang'),
            ])
                ->groupBy('u.code_user', 'u.name', 'u.email', 'fn.code_niveau', 'fn.code_filiere')
                ->orderBy('moyenne_generale', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'classement' => $classement,
                    'total_etudiants' => $classement->count(),
                    'filtres_appliques' => $request->only(['code_classe', 'code_niveau', 'code_filiere']),
                ],
                'message' => 'Classement récupéré avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du classement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * MÉTHODES UTILITAIRES PRIVÉES
     */

    /**
     * Organiser les notes par semestre et UE
     */
    private function organiserNotesParSemestre($notes)
    {
        $notesOrganisees = [];

        foreach ($notes as $note) {
            $codeSem = $note->ec->ue->code_sem;
            $codeUe = $note->ec->code_ue;

            if (! isset($notesOrganisees[$codeSem])) {
                $notesOrganisees[$codeSem] = [
                    'semestre' => $note->ec->ue->semestre,
                    'ues' => [],
                ];
            }

            if (! isset($notesOrganisees[$codeSem]['ues'][$codeUe])) {
                $notesOrganisees[$codeSem]['ues'][$codeUe] = [
                    'ue' => $note->ec->ue,
                    'ecs' => [],
                ];
            }

            $notesOrganisees[$codeSem]['ues'][$codeUe]['ecs'][] = $note;
        }

        return $notesOrganisees;
    }

    /**
     * Calculer les moyennes d'un semestre
     */
    private function calculerMoyennesSemestre($notes, $codeUser, $codeSemestre)
    {
        $moyennesUE = [];
        $totalCredits = 0;
        $sommeNotesPonderees = 0;

        // Grouper par UE
        $notesParUE = $notes->groupBy('ec.code_ue');

        foreach ($notesParUE as $codeUe => $notesUE) {
            $totalNotesEC = 0;
            $totalCreditsUE = 0;

            foreach ($notesUE as $note) {
                $totalNotesEC += $note->note_eval * $note->ec->credit_ec;
                $totalCreditsUE += $note->ec->credit_ec;
            }

            $moyenneUE = $totalCreditsUE > 0 ? $totalNotesEC / $totalCreditsUE : 0;

            $moyennesUE[$codeUe] = [
                'moyenne' => round($moyenneUE, 2),
                'credits' => $totalCreditsUE,
                'statut' => $moyenneUE >= 10 ? 'Validé' : 'Non validé',
            ];

            $sommeNotesPonderees += $moyenneUE * $totalCreditsUE;
            $totalCredits += $totalCreditsUE;
        }

        $moyenneGenerale = $totalCredits > 0 ? $sommeNotesPonderees / $totalCredits : 0;

        return [
            'moyennes_ue' => $moyennesUE,
            'moyenne_generale' => round($moyenneGenerale, 2),
            'total_credits' => $totalCredits,
            'statut_semestre' => $moyenneGenerale >= 10 ? 'Validé' : 'Non validé',
        ];
    }

    /**
     * Calculer les statistiques d'un étudiant
     */
    private function calculerStatistiquesEtudiant($notes)
    {
        if ($notes->isEmpty()) {
            return null;
        }

        $notesMoyennes = $notes->pluck('note_eval');

        return [
            'nombre_evaluations' => $notes->count(),
            'note_maximale' => $notesMoyennes->max(),
            'note_minimale' => $notesMoyennes->min(),
            'moyenne_generale' => round($notesMoyennes->avg(), 2),
            'nombre_ue_validees' => $this->compterUEValidees($notes),
            'taux_reussite' => round(($notesMoyennes->filter(function ($note) {
                return $note >= 10;
            })->count() / $notes->count()) * 100, 2),
        ];
    }

    /**
     * Construire le bulletin d'un étudiant
     */
    private function construireBulletin($etudiant, $ues, $codeSemestre, $codeAnnee)
    {
        $bulletin = [
            'etudiant' => $etudiant->only(['code_user', 'name', 'email']),
            'semestre' => $codeSemestre,
            'annee' => $codeAnnee,
            'date_generation' => now()->format('Y-m-d H:i:s'),
            'ues' => [],
            'moyenne_generale' => 0,
            'total_credits' => 0,
            'credits_obtenus' => 0,
        ];

        $sommeNotesPonderees = 0;
        $totalCredits = 0;

        foreach ($ues as $ue) {
            $ueData = [
                'code_ue' => $ue->code_ue,
                'intitule_ue' => $ue->intitule_ue,
                'ecs' => [],
                'moyenne_ue' => 0,
                'credits_ue' => 0,
                'statut' => 'Non validé',
            ];

            $totalNotesUE = 0;
            $totalCreditsUE = 0;

            foreach ($ue->ecs as $ec) {
                if ($ec->evaluations->isNotEmpty()) {
                    $moyenneEC = $ec->evaluations->avg('note_eval');
                    $ueData['ecs'][] = [
                        'code_ec' => $ec->code_ec,
                        'intitule_ec' => $ec->intitule_ec,
                        'credit_ec' => $ec->credit_ec,
                        'moyenne_ec' => round($moyenneEC, 2),
                        'evaluations' => $ec->evaluations,
                    ];

                    $totalNotesUE += $moyenneEC * $ec->credit_ec;
                    $totalCreditsUE += $ec->credit_ec;
                }
            }

            if ($totalCreditsUE > 0) {
                $ueData['moyenne_ue'] = round($totalNotesUE / $totalCreditsUE, 2);
                $ueData['credits_ue'] = $totalCreditsUE;
                $ueData['statut'] = $ueData['moyenne_ue'] >= 10 ? 'Validé' : 'Non validé';

                $sommeNotesPonderees += $ueData['moyenne_ue'] * $totalCreditsUE;
                $totalCredits += $totalCreditsUE;

                if ($ueData['statut'] === 'Validé') {
                    $bulletin['credits_obtenus'] += $totalCreditsUE;
                }
            }

            $bulletin['ues'][] = $ueData;
        }

        $bulletin['moyenne_generale'] = $totalCredits > 0 ? round($sommeNotesPonderees / $totalCredits, 2) : 0;
        $bulletin['total_credits'] = $totalCredits;

        return $bulletin;
    }

    /**
     * Construire le relevé de notes complet
     */
    private function construireReleveNotes($etudiant, $evaluations, $codeAnnee)
    {
        $releve = [
            'etudiant' => $etudiant->only(['code_user', 'name', 'email']),
            'annee_scolaire' => $codeAnnee,
            'date_generation' => now()->format('Y-m-d H:i:s'),
            'semestres' => [],
        ];

        $evaluationsParSemestre = $evaluations->groupBy('ec.ue.code_sem');

        foreach ($evaluationsParSemestre as $codeSem => $notesSemestre) {
            $moyennes = $this->calculerMoyennesSemestre($notesSemestre, $etudiant->code_user, $codeSem);

            $releve['semestres'][$codeSem] = [
                'code_semestre' => $codeSem,
                'evaluations' => $notesSemestre,
                'moyennes' => $moyennes,
            ];
        }

        return $releve;
    }

    /**
     * Obtenir les notes d'un étudiant pour un semestre
     */
    private function getNotesEtudiantPourSemestre($codeUser, $codeSemestre)
    {
        return Evaluation::with(['ec.ue'])
            ->whereHas('ec.ue', function ($query) use ($codeSemestre) {
                $query->where('code_sem', $codeSemestre);
            })
            ->where('code_user', $codeUser)
            ->get();
    }

    /**
     * Compter les UE validées
     */
    private function compterUEValidees($notes)
    {
        $notesParUE = $notes->groupBy('ec.code_ue');
        $ueValidees = 0;

        foreach ($notesParUE as $notesUE) {
            $moyenneUE = $notesUE->avg('note_eval');
            if ($moyenneUE >= 10) {
                $ueValidees++;
            }
        }

        return $ueValidees;
    }

    /**
     * Calculer les statistiques d'une classe
     */
    private function calculerStatistiquesClasse($resultats)
    {
        if (empty($resultats)) {
            return null;
        }

        $moyennes = collect($resultats)->pluck('moyennes.moyenne_generale');

        return [
            'nombre_etudiants' => count($resultats),
            'moyenne_classe' => round($moyennes->avg(), 2),
            'note_maximale' => $moyennes->max(),
            'note_minimale' => $moyennes->min(),
            'taux_reussite' => round(($moyennes->filter(function ($moyenne) {
                return $moyenne >= 10;
            })->count() / count($resultats)) * 100, 2),
        ];
    }

    /**
     * Calculer les statistiques d'un niveau
     */
    private function calculerStatistiquesNiveau($resultats)
    {
        if ($resultats->isEmpty()) {
            return null;
        }

        $moyennes = $resultats->pluck('moyenne_generale');

        return [
            'nombre_etudiants' => $resultats->count(),
            'moyenne_niveau' => round($moyennes->avg(), 2),
            'note_maximale' => $moyennes->max(),
            'note_minimale' => $moyennes->min(),
            'taux_reussite' => round(($moyennes->filter(function ($moyenne) {
                return $moyenne >= 10;
            })->count() / $resultats->count()) * 100, 2),
        ];
    }

    /**
     * Calculer les statistiques d'une filière
     */
    private function calculerStatistiquesFiliere($resultats)
    {
        if ($resultats->isEmpty()) {
            return null;
        }

        $moyennes = $resultats->pluck('moyenne_generale');
        $resultatsParNiveau = $resultats->groupBy('code_niveau');

        return [
            'nombre_etudiants' => $resultats->count(),
            'nombre_niveaux' => $resultatsParNiveau->count(),
            'moyenne_filiere' => round($moyennes->avg(), 2),
            'note_maximale' => $moyennes->max(),
            'note_minimale' => $moyennes->min(),
            'taux_reussite' => round(($moyennes->filter(function ($moyenne) {
                return $moyenne >= 10;
            })->count() / $resultats->count()) * 100, 2),
            'repartition_par_niveau' => $resultatsParNiveau->map(function ($niveauResultats) {
                return [
                    'nombre_etudiants' => $niveauResultats->count(),
                    'moyenne' => round($niveauResultats->pluck('moyenne_generale')->avg(), 2),
                ];
            }),
        ];
    }
}
