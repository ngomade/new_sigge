<?php

namespace App\Http\Controllers;

use App\Models\Bureau;
use App\Models\Personnel;
use App\Models\PersRole;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AffectationController extends Controller
{
    /**
     * Afficher la page d'affectation de personnel
     */
    public function index(string $type_bureau)
    {
        $bureaux = Bureau::where('type_bureau', $type_bureau)->get();
        $bureau_code = request()->get('bureau_code');
        $bureau = $bureau_code
            ? $bureaux->where('code_bureau', $bureau_code)->first()
            : $bureaux->first();

        if (!$bureau) {
            return redirect()->back()->withErrors("Aucun bureau trouvé pour ce type.");
        }

        return view("sige_app.backend.administration.affectation_personnel", compact("type_bureau", "bureau", "bureaux"));
    }

    /**
     * Recherche de personnel pour l'affectation avec pagination
     */
    public function searchPersonnel(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perPage = 20;
        $bureau_code = $request->input('bureau_code') || $request->get('bureau_code');

        $query = Personnel::query()
            ->select('code_pers', 'nom_pers', 'prenom_pers', 'cni_pers', 'first_phone_pers', 'second_phone_pers');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nom_pers', 'LIKE', "%{$search}%")
                  ->orWhere('prenom_pers', 'LIKE', "%{$search}%")
                  ->orWhere('code_pers', 'LIKE', "%{$search}%")
                  ->orWhere('cni_pers', 'LIKE', "%{$search}%")
                  ->orWhere('first_phone_pers', 'LIKE', "%{$search}%")
                  ->orWhere('second_phone_pers', 'LIKE', "%{$search}%");
            });
        }

        $personnel = $query->paginate($perPage, ['*'], 'page', $page);

        // Pour chaque personnel, récupérer les rôles déjà attribués dans ce bureau
        $formattedPersonnel = $personnel->getCollection()->map(function($item) use ($bureau_code) {
            $rolesAffectes = [];
            if ($bureau_code) {
                $rolesAffectes = PersRole::where('code_bureau', $bureau_code)
                    ->where('code_pers', $item->code_pers)
                    ->with('role')
                    ->get()
                    ->map(function($pr) {
                        return [
                            'id' => $pr->role ? $pr->role->id : null,
                            'name' => $pr->role ? $pr->role->name : null,
                            'statut' => $pr->statut_role
                        ];
                    })->toArray();
            }
            return [
                'id' => $item->code_pers,
                'nom' => $item->nom_pers,
                'prenom' => $item->prenom_pers,
                'num_cni' => $item->cni_pers,
                'first_phone' => $item->first_phone_pers,
                'second_phone' => $item->second_phone_pers,
                'roles_affectes' => $rolesAffectes
            ];
        });

        return response()->json([
            'data' => $formattedPersonnel,
            'pagination' => [
                'current_page' => $personnel->currentPage(),
                'per_page' => $personnel->perPage(),
                'total' => $personnel->total(),
                'last_page' => $personnel->lastPage()
            ]
        ]);
    }

    /**
     * Récupérer le personnel d'un bureau avec leurs rôles
     */
    public function getPersonnelBureau($code)
    {
        $personnel = PersRole::with([
                'personnel' => function($query) {
                    $query->select('code_pers', 'nom_pers', 'prenom_pers', 'cni_pers', 'first_phone_pers', 'second_phone_pers');
                },
                'role' => function($query) {
                    $query->select('id', 'name');
                }
            ])
            ->where('code_bureau', $code)
            ->get(['code_bureau', 'code_pers', 'id', 'date_debut_role', 'date_fin_role', 'statut_role']);

        // Formater la réponse
        $formattedPersonnel = $personnel->map(function($item) {
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
                'role_id' => $item->id,
                'role_libelle' => $item->role->name ?? 'Inconnu',
                'date_debut' => $item->date_debut_role ? Carbon::parse($item->date_debut_role)->format('d/m/Y') : null,
                'date_fin' => $item->date_fin_role ? Carbon::parse($item->date_fin_role)->format('d/m/Y') : null,
                'statut' => $statut
            ];
        });

        return response()->json($formattedPersonnel);
    }

    /**
     * Affecter plusieurs rôles à plusieurs personnels
     */
    public function affecterPersonnelMultiple(Request $request)
    {
        $request->validate([
            'bureau_code' => 'required|exists:bureau,code_bureau',
            'affectations' => 'required|array',
            'affectations.*.id' => 'required|exists:personnel,code_pers',
            'affectations.*.roles' => 'required|array',
            'affectations.*.roles.*.role_id' => 'required|integer|exists:roles,id',
            'affectations.*.roles.*.date_debut_role' => 'required|date',
            'affectations.*.roles.*.date_fin_role' => 'nullable|date|after:affectations.*.roles.*.date_debut_role',
            'affectations.*.roles.*.statut_role' => 'required|integer|in:0,1'
        ]);

        try {
            DB::beginTransaction();

            $bureauCode = $request->bureau_code;
            $affectations = $request->affectations;
            $totalAffectations = 0;

            foreach ($affectations as $affectation) {
                $personnelId = $affectation['id'];

                foreach ($affectation['roles'] as $roleData) {
                    // Vérifier si le personnel a déjà ce rôle dans ce bureau
                    $existingRole = PersRole::where([
                        'code_bureau' => $bureauCode,
                        'code_pers' => $personnelId,
                        'id' => $roleData['role_id']
                    ])->first();

                    if ($existingRole) {
                        // Mettre à jour le rôle existant
                        $existingRole->update([
                            'date_debut_role' => $roleData['date_debut_role'],
                            'date_fin_role' => $roleData['date_fin_role'] ?? null,
                            'statut_role' => $roleData['statut_role']
                        ]);
                    } else {
                        // Créer un nouveau rôle
                        PersRole::create([
                            'code_bureau' => $bureauCode,
                            'code_pers' => $personnelId,
                            'id' => $roleData['role_id'],
                            'date_debut_role' => $roleData['date_debut_role'],
                            'date_fin_role' => $roleData['date_fin_role'] ?? null,
                            'statut_role' => $roleData['statut_role']
                        ]);
                    }
                    $totalAffectations++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$totalAffectations} affectation(s) enregistrée(s) avec succès"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'affectation multiple du personnel: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'affectation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activer/Désactiver un rôle d'un personnel dans un bureau
     */
    public function toggleRole(Request $request)
    {
        $request->validate([
            'bureau_code' => 'required|exists:bureau,code_bureau',
            'personnel_id' => 'required|exists:personnel,code_pers',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        try {
            DB::beginTransaction();

            $role = PersRole::where([
                'code_bureau' => $request->bureau_code,
                'code_pers' => $request->personnel_id,
                'id' => $request->role_id
            ])->firstOrFail();

            // Inverser le statut actuel
            $nouveauStatut = $role->statut_role === PersRole::STATUT_ACTIF ? PersRole::STATUT_INACTIF : PersRole::STATUT_ACTIF;

            $role->update([
                'statut_role' => $nouveauStatut,
                'date_fin_role' => $nouveauStatut === PersRole::STATUT_INACTIF ? now() : null
            ]);

            DB::commit();

            $action = $nouveauStatut === PersRole::STATUT_ACTIF ? 'activé' : 'désactivé';

            return response()->json([
                'success' => true,
                'message' => "Rôle {$action} avec succès",
                'new_status' => $nouveauStatut
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer complètement une affectation
     */
    public function supprimerAffectation(Request $request)
    {
        $request->validate([
            'bureau_code' => 'required|exists:bureau,code_bureau',
            'personnel_id' => 'required|exists:personnel,code_pers',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        try {
            DB::beginTransaction();

            $role = PersRole::where([
                'code_bureau' => $request->bureau_code,
                'code_pers' => $request->personnel_id,
                'id' => $request->role_id
            ])->firstOrFail();

            $role->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Affectation supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les statistiques d'affectation
     */
    public function getStats($bureauCode)
    {
        try {
            $totalPersonnel = Personnel::count();
            $personnelAffecte = PersRole::where('code_bureau', $bureauCode)
                ->distinct('code_pers')
                ->count();
            $totalRoles = Role::count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_personnel' => $totalPersonnel,
                    'personnel_affecte' => $personnelAffecte,
                    'total_roles' => $totalRoles
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques: ' . $e->getMessage()
            ], 500);
        }
    }
}
