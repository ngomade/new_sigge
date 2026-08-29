<?php

namespace App\Http\Controllers\requetes;

use App\Http\Controllers\Controller;
use App\Models\Bureau;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BureauControllerApi extends Controller
{
    /**
     * Afficher la liste des bureaux
     */
    public function index(): JsonResponse
    {
        try {
            $bureaux = Bureau::with(['documents', 'presentations', 'sousBureau', 'bureauParents'])
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Liste des bureaux récupérée avec succès',
                'data' => $bureaux,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des bureaux',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Créer un nouveau bureau
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'code_bureau' => 'required|string|max:255|unique:bureau,code_bureau',
                'label_bureau' => 'required|string|max:255',
                'desc_bureau' => 'nullable|string',
                'type_bureau' => 'required|string|max:100',
                'sous_bureaux' => 'nullable|array',
                'sous_bureaux.*' => 'exists:bureau,code_bureau',
            ]);

            $bureau = Bureau::create([
                'code_bureau' => $validated['code_bureau'],
                'label_bureau' => $validated['label_bureau'],
                'desc_bureau' => $validated['desc_bureau'] ?? null,
                'type_bureau' => $validated['type_bureau'],
            ]);

            // Attacher les sous-bureaux si fournis
            if (isset($validated['sous_bureaux'])) {
                $bureau->sousBureau()->attach($validated['sous_bureaux']);
            }

            $bureau->load(['documents', 'presentations', 'sousBureau', 'bureauParents']);

            return response()->json([
                'success' => true,
                'message' => 'Bureau créé avec succès',
                'data' => $bureau,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du bureau',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Afficher un bureau spécifique
     */
    public function show($code_bureau): JsonResponse
    {
        try {
            $bureau = Bureau::with(['documents', 'presentations', 'sousBureau', 'bureauParents'])
                ->where('code_bureau', $code_bureau)
                ->first();

            if (! $bureau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bureau non trouvé',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Bureau récupéré avec succès',
                'data' => $bureau,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du bureau',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mettre à jour un bureau
     */
    public function update(Request $request, $code_bureau): JsonResponse
    {
        try {
            $bureau = Bureau::where('code_bureau', $code_bureau)->first();

            if (! $bureau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bureau non trouvé',
                ], 404);
            }

            $validated = $request->validate([
                'label_bureau' => 'sometimes|required|string|max:255',
                'desc_bureau' => 'nullable|string',
                'type_bureau' => 'sometimes|required|string|max:100',
                'sous_bureaux' => 'nullable|array',
                'sous_bureaux.*' => 'exists:bureau,code_bureau',
            ]);

            $bureau->update($validated);

            // Synchroniser les sous-bureaux si fournis
            if (isset($validated['sous_bureaux'])) {
                $bureau->sousBureau()->sync($validated['sous_bureaux']);
            }

            $bureau->load(['documents', 'presentations', 'sousBureau', 'bureauParents']);

            return response()->json([
                'success' => true,
                'message' => 'Bureau mis à jour avec succès',
                'data' => $bureau,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du bureau',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Supprimer un bureau
     */
    public function destroy($code_bureau): JsonResponse
    {
        try {
            $bureau = Bureau::where('code_bureau', $code_bureau)->first();

            if (! $bureau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bureau non trouvé',
                ], 404);
            }

            // Détacher les relations many-to-many
            $bureau->sousBureau()->detach();
            $bureau->bureauParents()->detach();

            $bureau->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bureau supprimé avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du bureau',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Rechercher des bureaux
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            $type = $request->get('type');

            $bureaux = Bureau::query();

            if ($query) {
                $bureaux->where(function ($q) use ($query) {
                    $q->where('label_bureau', 'LIKE', "%{$query}%")
                        ->orWhere('desc_bureau', 'LIKE', "%{$query}%")
                        ->orWhere('code_bureau', 'LIKE', "%{$query}%");
                });
            }

            if ($type) {
                $bureaux->where('type_bureau', $type);
            }

            $results = $bureaux->with(['documents', 'presentations', 'sousBureau', 'bureauParents'])
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Recherche effectuée avec succès',
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les sous-bureaux d'un bureau
     */
    public function getSousBureaux($code_bureau): JsonResponse
    {
        try {
            $bureau = Bureau::where('code_bureau', $code_bureau)->first();

            if (! $bureau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bureau non trouvé',
                ], 404);
            }

            $sousBureaux = $bureau->sousBureau()->get();

            return response()->json([
                'success' => true,
                'message' => 'Sous-bureaux récupérés avec succès',
                'data' => $sousBureaux,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sous-bureaux',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les bureaux parents d'un bureau
     */
    public function getBureauParents($code_bureau): JsonResponse
    {
        try {
            $bureau = Bureau::where('code_bureau', $code_bureau)->first();

            if (! $bureau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bureau non trouvé',
                ], 404);
            }

            $bureauParents = $bureau->bureauParents()->get();

            return response()->json([
                'success' => true,
                'message' => 'Bureaux parents récupérés avec succès',
                'data' => $bureauParents,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des bureaux parents',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
