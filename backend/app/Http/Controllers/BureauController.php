<?php

namespace App\Http\Controllers;

use App\Models\Bureau;
use App\Models\notes\Document;
use App\Models\Personnel;
use App\Models\PersRole;
use App\Models\Presentation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Image;
use Throwable;

class BureauController extends Controller
{
    /**
     * Générer un code intelligent pour un bureau
     */
    private function generateCodeBureau($type, $label, $parentCode = null)
    {
        // Nettoyer le label pour créer un acronyme
        $words = explode(' ', $label);
        $acronym = '';

        // Prendre les premières lettres de chaque mot significatif
        foreach ($words as $word) {
            if (strlen($word) > 2 && !in_array(strtolower($word), ['de', 'du', 'la', 'le', 'les', 'des', 'et'])) {
                $acronym .= strtoupper(substr($word, 0, 1));
            }
        }

        // Si l'acronyme est trop court, prendre les 3 premières lettres du label
        if (strlen($acronym) < 2) {
            $acronym = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $label), 0, 3));
        }

        // Préfixes selon le type
        $prefixes = [
            'Departement' => 'DEPT',
            'Division' => 'DIV',
            'Cellule' => 'CEL',
            'Service' => 'SERV'
        ];

        $prefix = $prefixes[$type] ?? 'BUR';

        // Si c'est un service avec un parent, inclure une partie du code parent
        if ($type === 'Service' && $parentCode) {
            // Extraire l'acronyme du parent
            $parentAcronym = substr($parentCode, -3);
            $baseCode = $prefix . '_' . $parentAcronym . '_' . $acronym;
        } else {
            $baseCode = $prefix . '_' . $acronym;
        }

        // Vérifier l'unicité et ajouter un numéro si nécessaire
        $code = $baseCode;
        $counter = 1;

        while (Bureau::where('code_bureau', $code)->exists()) {
            $code = $baseCode . '_' . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $code;
    }

    /**
     * Obtenir la hiérarchie complète d'un bureau
     */
    private function getBureauHierarchy($bureau)
    {
        $hierarchy = [];

        // Remonter la hiérarchie
        if ($bureau->type_bureau === 'Service') {
            $parent = $bureau->bureauParents()->first();
            if ($parent) {
                $hierarchy[] = [
                    'niveau' => 'Parent',
                    'type' => $parent->type_bureau,
                    'code' => $parent->code_bureau,
                    'label' => $parent->label_bureau
                ];
            }
        }

        // Descendre la hiérarchie (sous-bureaux)
        $sousBureaux = $bureau->sousBureau()->get();
        if ($sousBureaux->count() > 0) {
            $hierarchy[] = [
                'niveau' => 'Sous-bureaux',
                'bureaux' => $sousBureaux->map(function ($sb) {
                    return [
                        'type' => $sb->type_bureau,
                        'code' => $sb->code_bureau,
                        'label' => $sb->label_bureau
                    ];
                })
            ];
        }

        return $hierarchy;
    }

    public function index(string $type_bureau)
    {
        // Récupérer les bureaux du type demandé avec leurs relations
        $bureaux = Bureau::where('type_bureau', $type_bureau)
            ->with(['bureauParents', 'sousBureau'])
            ->get();

        // Pour les services, récupérer les bureaux parents possibles
        $bureaux_parents = null;
        if ($type_bureau === 'Service') {
            $bureaux_parents = Bureau::whereIn('type_bureau', ['Division', 'Cellule'])
                ->orderBy('type_bureau')
                ->orderBy('label_bureau')
                ->get();
        }

        // Statistiques
        $stats = [
            'total' => $bureaux->count(),
            'avec_personnel' => PersRole::whereIn('code_bureau', $bureaux->pluck('code_bureau'))
                ->distinct('code_bureau')
                ->count(),
            'avec_sous_bureaux' => $bureaux->filter(function ($b) {
                return $b->sousBureau->count() > 0;
            })->count()
        ];

        return view("sige_app.backend.administration.bureau", compact("type_bureau", "bureaux", "bureaux_parents", "stats"));
    }

    /**
     * Générer automatiquement un code pour l'aperçu
     */
    public function generateCode(Request $request)
    {
        $request->validate([
            'type_bureau' => 'required|string',
            'label_bureau' => 'required|string',
            'bureau_parent' => 'nullable|string|exists:bureau,code_bureau'
        ]);

        $code = $this->generateCodeBureau(
            $request->type_bureau,
            $request->label_bureau,
            $request->bureau_parent
        );

        return response()->json(['code' => $code]);
    }

    /**
     * Afficher la page d'affectation de personnel
     */
    public function affectationPersonnel(string $type_bureau, Request $request)
    {
        $bureau = null;

        // Si un code bureau spécifique est fourni
        if ($request->has('bureau_code')) {
            $bureau = Bureau::where('code_bureau', $request->bureau_code)->first();
        } else {
            // Sinon, prendre le premier bureau du type
            $bureau = Bureau::where('type_bureau', $type_bureau)->first();
        }

        if (!$bureau) {
            return redirect()->back()->withErrors("Aucun bureau trouvé pour le type: " . $type_bureau);
        }

        // Récupérer tous les bureaux du même type pour le sélecteur
        $bureaux = Bureau::where('type_bureau', $type_bureau)
            ->orderBy('label_bureau')
            ->get();

        return view("sige_app.backend.administration.affectation_personnel", compact("type_bureau", "bureau", "bureaux"));
    }

    public function store(Request $request)
    {
        try {
            // Validation des données
            $rules = [
                'label_bureau' => 'required|string|max:128',
                'type_bureau' => 'required|string|max:128',
                'desc_bureau' => 'nullable|string',
                'code_bureau' => 'nullable|string|max:128|unique:bureau,code_bureau',
                'bureau_parent' => 'nullable|string|exists:bureau,code_bureau'
            ];

            // Pour les services, le parent est obligatoire
            if ($request->type_bureau === 'Service') {
                $rules['bureau_parent'] = 'required|string|exists:bureau,code_bureau';
            }

            $request->validate($rules);

            DB::beginTransaction();

            // Générer le code si non fourni
            $codeBureau = $request->code_bureau;
            if (empty($codeBureau)) {
                $codeBureau = $this->generateCodeBureau(
                    $request->type_bureau,
                    $request->label_bureau,
                    $request->bureau_parent
                );
            }

            // Créer le bureau
            $bureau = Bureau::create([
                'code_bureau' => $codeBureau,
                'label_bureau' => $request->label_bureau,
                'type_bureau' => $request->type_bureau,
                'desc_bureau' => $request->desc_bureau
            ]);

            // Si c'est un service et qu'un bureau parent est spécifié
            if ($request->type_bureau === 'Service' && $request->bureau_parent) {
                DB::table('sous_bureau')->insert([
                    'code_bureau' => $request->bureau_parent,
                    'code_sous_bureau' => $codeBureau,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            // Log de l'activité
            Log::info("Bureau créé", [
                'type' => $request->type_bureau,
                'code' => $codeBureau,
                'user' => Auth::id()
            ]);

            $success = $request->type_bureau . " créé avec succès (Code: " . $codeBureau . ")";
            return redirect("/bureau/" . $request->type_bureau)->with(compact("success"));
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error("Erreur création bureau", ['error' => $th->getMessage()]);
            return redirect()->back()->withErrors("Echec de création du " . $request->type_bureau . " : " . $th->getMessage())->withInput();
        }
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit($code_bureau)
    {
        $bureau = Bureau::with(['bureauParents', 'sousBureau'])->findOrFail($code_bureau);
        $type_bureau = $bureau->type_bureau;

        // Obtenir la hiérarchie
        $hierarchy = $this->getBureauHierarchy($bureau);

        // Si c'est un service, récupérer les bureaux parents possibles et le parent actuel
        $bureaux_parents = null;
        $parent_actuel = null;

        if ($type_bureau === 'Service') {
            $bureaux_parents = Bureau::whereIn('type_bureau', ['Division', 'Cellule'])
                ->where('code_bureau', '!=', $code_bureau)
                ->orderBy('type_bureau')
                ->orderBy('label_bureau')
                ->get();
            $parent_actuel = $bureau->bureauParents()->first();
        }

        return view("sige_app.backend.administration.edit_bureau", compact(
            "bureau",
            "type_bureau",
            "bureaux_parents",
            "parent_actuel",
            "hierarchy"
        ));
    }

    /**
     * Mettre à jour un bureau
     */
    public function update(Request $request, $code_bureau)
    {
        try {
            $bureau = Bureau::findOrFail($code_bureau);

            // Validation
            $rules = [
                'label_bureau' => 'required|string|max:128',
                'desc_bureau' => 'nullable|string',
                'bureau_parent' => 'nullable|string|exists:bureau,code_bureau'
            ];

            // Pour les services, le parent est obligatoire
            if ($bureau->type_bureau === 'Service') {
                $rules['bureau_parent'] = 'required|string|exists:bureau,code_bureau';
            }

            $request->validate($rules);

            DB::beginTransaction();

            // Mettre à jour le bureau
            $bureau->update([
                'label_bureau' => $request->label_bureau,
                'desc_bureau' => $request->desc_bureau
            ]);

            // Si c'est un service, gérer la relation parent
            if ($bureau->type_bureau === 'Service') {
                // Supprimer l'ancienne relation
                DB::table('sous_bureau')->where('code_sous_bureau', $code_bureau)->delete();

                // Créer la nouvelle relation si un parent est spécifié
                if ($request->bureau_parent) {
                    // Vérifier qu'on ne crée pas de cycle
                    if ($this->wouldCreateCycle($request->bureau_parent, $code_bureau)) {
                        DB::rollBack();
                        return redirect()->back()->withErrors("Impossible : cela créerait une relation circulaire")->withInput();
                    }

                    DB::table('sous_bureau')->insert([
                        'code_bureau' => $request->bureau_parent,
                        'code_sous_bureau' => $code_bureau,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();

            // Log de l'activité
            Log::info("Bureau modifié", [
                'code' => $code_bureau,
                'user' => Auth::id(),
            ]);

            $success = $bureau->type_bureau . " modifié avec succès";
            return redirect("/bureau/" . $bureau->type_bureau)->with(compact("success"));
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error("Erreur modification bureau", ['error' => $th->getMessage()]);
            return redirect()->back()->withErrors("Echec de modification : " . $th->getMessage())->withInput();
        }
    }

    /**
     * Vérifier si une relation parent-enfant créerait un cycle
     */
    private function wouldCreateCycle($parentCode, $childCode)
    {
        // Vérifier si le parent est déjà un descendant de l'enfant
        $descendants = $this->getAllDescendants($childCode);
        return in_array($parentCode, $descendants);
    }

    /**
     * Obtenir tous les descendants d'un bureau
     */
    private function getAllDescendants($bureauCode)
    {
        $descendants = [];
        $toCheck = [$bureauCode];

        while (!empty($toCheck)) {
            $current = array_shift($toCheck);
            $sousBureaux = DB::table('sous_bureau')
                ->where('code_bureau', $current)
                ->pluck('code_sous_bureau')
                ->toArray();

            foreach ($sousBureaux as $sb) {
                if (!in_array($sb, $descendants)) {
                    $descendants[] = $sb;
                    $toCheck[] = $sb;
                }
            }
        }

        return $descendants;
    }

    /**
     * Récupérer les sous-bureaux d'un bureau
     */
    public function getSousBureaux($code_bureau)
    {
        $bureau = Bureau::with(['sousBureau' => function ($query) {
            $query->withCount('sousBureau as nb_sous_bureaux');
        }])->findOrFail($code_bureau);

        $sousBureaux = $bureau->sousBureau->map(function ($sb) {
            return [
                'code_bureau' => $sb->code_bureau,
                'label_bureau' => $sb->label_bureau,
                'type_bureau' => $sb->type_bureau,
                'nb_sous_bureaux' => $sb->nb_sous_bureaux,
                'nb_personnel' => PersRole::where('code_bureau', $sb->code_bureau)
                    ->where('statut_role', PersRole::STATUT_ACTIF)
                    ->count()
            ];
        });

        return response()->json($sousBureaux);
    }

    /**
     * Récupérer les bureaux par type pour les listes déroulantes
     */
    public function getBureauxByType($type)
    {
        $bureaux = Bureau::where('type_bureau', $type)
            ->orderBy('label_bureau')
            ->get(['code_bureau', 'label_bureau']);
        return response()->json($bureaux);
    }

    /**
     * Obtenir l'arborescence complète des bureaux
     */
    public function getArborescence()
    {
        // Récupérer tous les bureaux sans parent (racines)
        $racines = Bureau::whereNotIn('code_bureau', function ($query) {
            $query->select('code_sous_bureau')->from('sous_bureau');
        })->orderBy('type_bureau')->orderBy('label_bureau')->get();

        $arborescence = [];

        foreach ($racines as $racine) {
            $arborescence[] = $this->buildTree($racine);
        }

        return response()->json($arborescence);
    }

    /**
     * Construire l'arbre récursivement
     */
    private function buildTree($bureau)
    {
        $node = [
            'code' => $bureau->code_bureau,
            'label' => $bureau->label_bureau,
            'type' => $bureau->type_bureau,
            'description' => $bureau->desc_bureau,
            'enfants' => []
        ];

        $sousBureaux = $bureau->sousBureau()->orderBy('type_bureau')->orderBy('label_bureau')->get();

        foreach ($sousBureaux as $sb) {
            $node['enfants'][] = $this->buildTree($sb);
        }

        return $node;
    }

    public function destroy(string $type_bureau, string $code_bureau)
    {
        try {
            DB::beginTransaction();

            // Vérifier si le bureau a des sous-bureaux
            $sousBureaux = DB::table('sous_bureau')->where('code_bureau', $code_bureau)->count();
            if ($sousBureaux > 0) {
                DB::rollBack();
                return redirect("/bureau/" . $type_bureau)->withErrors("Impossible de supprimer ce bureau car il contient " . $sousBureaux . " sous-bureau(x)");
            }

            // Vérifier si le bureau a du personnel actif
            $personnelActif = PersRole::where('code_bureau', $code_bureau)
                ->where('statut_role', PersRole::STATUT_ACTIF)
                ->count();

            if ($personnelActif > 0) {
                DB::rollBack();
                return redirect("/bureau/" . $type_bureau)->withErrors("Impossible de supprimer ce bureau car il contient " . $personnelActif . " personnel(s) actif(s)");
            }

            // Supprimer les relations où ce bureau est un sous-bureau
            DB::table('sous_bureau')->where('code_sous_bureau', $code_bureau)->delete();

            // Supprimer les affectations de personnel inactives
            PersRole::where('code_bureau', $code_bureau)->delete();

            // Supprimer les présentations
            Presentation::where('code_bureau', $code_bureau)->delete();

            // Supprimer les documents
            Document::where('code_bureau', $code_bureau)->delete();

            // Supprimer le bureau
            Bureau::destroy($code_bureau);

            DB::commit();

            // Log de l'activité
            Log::info("Bureau supprimé", [
                'code' => $code_bureau,
                'type' => $type_bureau,
                'user' => Auth::id()
            ]);

            $success = $type_bureau . " supprimé avec succès";
            return redirect("/bureau/" . $type_bureau)->with(compact(["success"]));
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error("Erreur suppression bureau", ['error' => $th->getMessage()]);
            $errors = "Echec de suppression : " . $th->getMessage();
            return redirect("/bureau/" . $type_bureau)->withErrors($errors);
        }
    }

    /**
     * Exporter les bureaux en CSV
     */
    public function export($type_bureau)
    {
        $bureaux = Bureau::where('type_bureau', $type_bureau)
            ->with(['bureauParents', 'sousBureau'])
            ->get();

        $csv = "Code;Label;Type;Description;Bureau Parent;Nombre Sous-Bureaux;Personnel Actif\n";

        foreach ($bureaux as $bureau) {
            $parent = $bureau->bureauParents()->first();
            $nbPersonnel = PersRole::where('code_bureau', $bureau->code_bureau)
                ->where('statut_role', PersRole::STATUT_ACTIF)
                ->count();

            $csv .= sprintf(
                "%s;%s;%s;%s;%s;%d;%d\n",
                $bureau->code_bureau,
                $bureau->label_bureau,
                $bureau->type_bureau,
                str_replace(["\n", "\r", ";"], [" ", " ", ","], strip_tags($bureau->desc_bureau)),
                $parent ? $parent->label_bureau : '',
                $bureau->sousBureau->count(),
                $nbPersonnel
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="bureaux_' . $type_bureau . '_' . date('Y-m-d') . '.csv"');
    }

    // Les méthodes existantes pour la gestion du personnel restent inchangées...
    public function searchPersonnel(Request $request)
    {
        $search = $request->input('search', '');

        $query = Personnel::query()
            ->select('code_pers', 'nom_pers', 'prenom_pers', 'cni_pers', 'first_phone_pers', 'second_phone_pers');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nom_pers', 'LIKE', "%{$search}%")
                    ->orWhere('prenom_pers', 'LIKE', "%{$search}%")
                    ->orWhere('code_pers', 'LIKE', "%{$search}%")
                    ->orWhere('cni_pers', 'LIKE', "%{$search}%")
                    ->orWhere('first_phone_pers', 'LIKE', "%{$search}%")
                    ->orWhere('second_phone_pers', 'LIKE', "%{$search}%");
            });
        }

        $personnel = $query->limit(50)->get();

        $formattedPersonnel = $personnel->map(function ($item) {
            return [
                'id' => $item->code_pers,
                'nom' => $item->nom_pers,
                'prenom' => $item->prenom_pers,
                'num_cni' => $item->cni_pers,
                'first_phone' => $item->first_phone_pers,
                'second_phone' => $item->second_phone_pers,
            ];
        });

        return response()->json($formattedPersonnel);
    }

    public function affecterPersonnel(Request $request)
    {
        $request->validate([
            'bureau_code' => 'required|exists:bureau,code_bureau',
            'personnels' => 'required|array',
            'personnels.*.id' => 'required|exists:personnel,code_pers',
            'personnels.*.role_id' => 'required|integer|exists:roles,id',
            'personnels.*.date_fin_role' => 'nullable|date|after:today',
            'personnels.*.statut_role' => 'sometimes|integer|in:0,1'
        ]);

        try {
            DB::beginTransaction();

            $bureauCode = $request->bureau_code;
            $personnels = $request->personnels;

            foreach ($personnels as $personnel) {
                $existingRole = PersRole::where([
                    'code_bureau' => $bureauCode,
                    'code_pers' => $personnel['id'],
                    'code_role' => $personnel['role_id']
                ])->first();

                if ($existingRole) {
                    $existingRole->update([
                        'date_debut_role' => now(),
                        'date_fin_role' => $personnel['date_fin_role'] ?? null,
                        'statut_role' => $personnel['statut_role'] ?? PersRole::STATUT_ACTIF
                    ]);
                } else {
                    PersRole::create([
                        'code_bureau' => $bureauCode,
                        'code_pers' => $personnel['id'],
                        'code_role' => $personnel['role_id'],
                        'date_debut_role' => now(),
                        'date_fin_role' => $personnel['date_fin_role'] ?? null,
                        'statut_role' => $personnel['statut_role'] ?? PersRole::STATUT_ACTIF
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Affectation du personnel enregistrée avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'affectation du personnel: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'affectation du personnel: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPersonnelBureau($code)
    {
        $personnel = PersRole::with([
            'personnel' => function ($query) {
                $query->select('code_pers', 'nom_pers', 'prenom_pers', 'cni_pers', 'first_phone_pers', 'second_phone_pers');
            },
            'role' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->where('code_bureau', $code)
            ->get(['code_bureau', 'code_pers', 'code_role', 'date_debut_role', 'date_fin_role', 'statut_role']);

        $formattedPersonnel = $personnel->map(function ($item) {
            $statut = 'Inactif';
            if ($item->isActif()) {
                $statut = 'Actif';
            } elseif ($item->isExpire()) {
                $statut = 'Expiré';
            }

            return [
                'id' => $item->code_pers,
                'nom' => $item->personnel->nom_pers ?? 'Inconnu',
                'prenom' => $item->personnel->prenom_pers ?? '',
                'num_cni' => $item->personnel->cni_pers ?? '',
                'first_phone' => $item->personnel->first_phone_pers ?? '',
                'second_phone' => $item->personnel->second_phone_pers ?? '',
                'role_id' => $item->code_role,
                'role_libelle' => $item->role->name ?? 'Inconnu',
                'date_debut' => $item->date_debut_role ? Carbon::parse($item->date_debut_role)->format('d/m/Y') : null,
                'date_fin' => $item->date_fin_role ? Carbon::parse($item->date_fin_role)->format('d/m/Y') : null,
                'statut' => $statut
            ];
        });

        return response()->json($formattedPersonnel);
    }

    public function getBureauCodeByType($type)
    {
        try {
            $bureau = Bureau::where('type_bureau', $type)->first();

            if (!$bureau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun bureau trouvé pour ce type'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'code_bureau' => $bureau->code_bureau
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du code du bureau: ' . $e->getMessage()
            ], 500);
        }
    }
}
