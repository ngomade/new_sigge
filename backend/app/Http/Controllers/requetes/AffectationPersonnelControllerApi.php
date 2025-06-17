<?php

namespace App\Http\Controllers\requetes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personnel;


class AffectationPersonnelControllerApi extends Controller
{
    // ===== CREATE - Créer une affectation =====
    public function store(Request $request)
    {
        $personnel = session('pers');
        if (!$personnel) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $validated = $request->validate([
            'id' => 'required|exists:roles,id',
            'code_bureau' => 'required|exists:bureau,code_bureau',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut_role' => 'required|in:actif,inactif,suspendu'
        ]);

        try {
            $personnelModel = Personnel::find($personnel->code_pers);
            
            // Vérifier si l'affectation existe déjà
            $existingAffectation = $personnelModel->roles()
                ->wherePivot('id', $validated['id'])
                ->wherePivot('code_bureau', $validated['code_bureau'])
                ->wherePivot('statut_role', 'actif')
                ->first();

            if ($existingAffectation) {
                return response()->json([
                    'error' => 'Cette affectation existe déjà'
                ], 422);
            }

            // Créer l'affectation
            $personnelModel->roles()->attach($validated['id'], [
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
                'statut_role' => $validated['statut_role'],
                'code_bureau' => $validated['code_bureau'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'message' => 'Affectation créée avec succès',
                'data' => $this->getAffectationDetails($personnel->code_pers, $validated['id'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création de l\'affectation',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // ===== READ - Lire les affectations =====
    
    // Toutes les affectations
    public function index(Request $request)
    {
        $query = Personnel::with([
            'pers_roles' => function($query) {
                $query->with('role', 'bureau');
            }
        ]);

        // Filtres optionnels
        if ($request->has('code_bureau')) {
            $query->whereHas('pers_roles', function($q) use ($request) {
                $q->where('code_bureau', $request->code_bureau);
            });
        }

        if ($request->has('statut_role')) {
            $statutMap = [
                'actif' => \App\Models\PersRole::STATUT_ACTIF,
                'inactif' => \App\Models\PersRole::STATUT_INACTIF,
                'expire' => \App\Models\PersRole::STATUT_EXPIRE,
            ];
            $statutValue = $statutMap[strtolower($request->statut_role)] ?? null;
            if ($statutValue !== null) {
                $query->whereHas('pers_roles', function($q) use ($statutValue) {
                    $q->where('statut_role', $statutValue);
                });
            }
        }

        if ($request->has('id')) {
            $query->whereHas('pers_roles', function($q) use ($request) {
                $q->where('id', $request->id_role);
            });
        }

        $affectations = $query->paginate(15);

        return response()->json([
            'data' => $affectations->items(),
            'pagination' => [
                'current_page' => $affectations->currentPage(),
                'per_page' => $affectations->perPage(),
                'total' => $affectations->total(),
                'last_page' => $affectations->lastPage()
            ]
        ]);
    }

    // Affectations d'un personnel spécifique
    public function show($code_pers)
    {
        $personnelSession = session('pers');
        if (!$personnelSession) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
        if ($personnelSession->code_pers !== $code_pers) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $personnel = Personnel::with([
            'roles' => function($query) {
                $query->withPivot('date_debut', 'date_fin', 'statut_role', 'code_bureau', 'created_at', 'updated_at')
                      ->orderBy('pers_role.date_debut', 'desc');
            },
            'roles.bureau'
        ])->find($code_pers);

        if (!$personnel) {
            return response()->json(['error' => 'Personnel non trouvé'], 404);
        }

        return response()->json([
            'personnel' => $personnel,
            'affectations_count' => $personnel->roles->count(),
            'affectations_actives' => $personnel->roles->where('pivot.statut_role', 'actif')->count()
        ]);
    }

   

    // ===== UPDATE - Modifier une affectation =====
    public function update(Request $request, $code_pers, $id_role)
    {
        $validated = $request->validate([
            'date_debut' => 'sometimes|date',
            'date_fin' => 'nullable|date',
            'statut_role' => 'sometimes|in:actif,inactif,suspendu,termine',
            'code_bureau' => 'sometimes|exists:bureau,code_bureau'
        ]);

        try {
            $personnel = Personnel::find($code_pers);
            if (!$personnel) {
                return response()->json(['error' => 'Personnel non trouvé'], 404);
            }

            // Vérifier que l'affectation existe
            $affectationExists = $personnel->roles()
                ->wherePivot('id', $id_role)
                ->exists();

            if (!$affectationExists) {
                return response()->json(['error' => 'Affectation non trouvée'], 404);
            }

            // Mettre à jour l'affectation
            $updateData = array_merge($validated, ['updated_at' => now()]);
            $personnel->roles()->updateExistingPivot($id_role, $updateData);

            return response()->json([
                'message' => 'Affectation mise à jour avec succès',
                'data' => $this->getAffectationDetails($code_pers, $id_role)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // ===== DELETE - Supprimer une affectation =====
    public function destroy($code_pers, $id_role)
    {
        try {
            $personnel = Personnel::find($code_pers);
            if (!$personnel) {
                return response()->json(['error' => 'Personnel non trouvé'], 404);
            }

            // Récupérer les détails avant suppression
            $affectationDetails = $this->getAffectationDetails($code_pers, $id_role);
            
            if (!$affectationDetails) {
                return response()->json(['error' => 'Affectation non trouvée'], 404);
            }

            // Supprimer l'affectation
            $personnel->roles()->detach($id_role);

            return response()->json([
                'message' => 'Affectation supprimée avec succès',
                'data' => $affectationDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    

   
    

    // Obtenir les détails d'une affectation
    private function getAffectationDetails($code_pers, $id_role)
    {
        return Personnel::with([
            'roles' => function($query) use ($id_role) {
                $query->where('roles.id', $id_role)
                      ->withPivot('date_debut', 'date_fin', 'statut_role', 'code_bureau', 'created_at', 'updated_at');
            },
            'roles.bureau'
        ])->find($code_pers);
    }

    
}

