<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\notes\Document;
use App\Models\notes\Salle;
use App\Models\notes\SessionExamen;
use App\Models\Bureau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RessourceController extends Controller
{
    /**
     * GESTION DES DOCUMENTS
     */
    
    /**
     * Afficher la liste des documents
     */
    public function indexDocuments()
    {
        try {
            $documents = Document::with(['sessionExamen', 'bureau'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $documents,
                'message' => 'Liste des documents récupérée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau document
     */
    public function storeDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_doc' => 'required|string|max:128',
            'type_doc' => 'required|string|max:128',
            'fichier' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240', // 10MB max
            'code_session' => 'nullable|string|exists:session_examen,code_session',
            'code_bureau' => 'nullable|string|exists:bureau,code_bureau',
            'description_doc' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Upload du fichier
            $file = $request->file('fichier');
            $nomFichier = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $cheminFichier = $file->storeAs('documents', $nomFichier, 'public');

            $document = Document::create([
                'code_session' => $request->code_session,
                'code_bureau' => $request->code_bureau,
                'label_doc' => $request->label_doc,
                'description_doc' => $request->description_doc,
                'type_doc' => $request->type_doc,
                'nom_fichier' => $nomFichier
            ]);

            return response()->json([
                'success' => true,
                'data' => $document->load(['sessionExamen', 'bureau']),
                'message' => 'Document créé avec succès'
            ], 201);

        } catch (\Exception $e) {
            // Supprimer le fichier en cas d'erreur
            if (isset($cheminFichier)) {
                Storage::disk('public')->delete($cheminFichier);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un document spécifique
     */
    public function showDocument($id)
    {
        try {
            $document = Document::with(['sessionExamen', 'bureau'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Document récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Document non trouvé',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mettre à jour un document
     */
    public function updateDocument(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'label_doc' => 'required|string|max:128',
            'type_doc' => 'required|string|max:128',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'code_session' => 'nullable|string|exists:session_examen,code_session',
            'code_bureau' => 'nullable|string|exists:bureau,code_bureau',
            'description_doc' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $document = Document::findOrFail($id);
            $ancienFichier = $document->nom_fichier;

            // Si un nouveau fichier est fourni
            if ($request->hasFile('fichier')) {
                $file = $request->file('fichier');
                $nomFichier = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('documents', $nomFichier, 'public');

                // Supprimer l'ancien fichier
                if ($ancienFichier && Storage::disk('public')->exists('documents/' . $ancienFichier)) {
                    Storage::disk('public')->delete('documents/' . $ancienFichier);
                }

                $document->nom_fichier = $nomFichier;
            }

            $document->update([
                'code_session' => $request->code_session,
                'code_bureau' => $request->code_bureau,
                'label_doc' => $request->label_doc,
                'description_doc' => $request->description_doc,
                'type_doc' => $request->type_doc,
                'nom_fichier' => $document->nom_fichier
            ]);

            return response()->json([
                'success' => true,
                'data' => $document->load(['sessionExamen', 'bureau']),
                'message' => 'Document mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un document
     */
    public function destroyDocument($id)
    {
        try {
            $document = Document::findOrFail($id);
            $nomFichier = $document->nom_fichier;

            $document->delete();

            // Supprimer le fichier physique
            if ($nomFichier && Storage::disk('public')->exists('documents/' . $nomFichier)) {
                Storage::disk('public')->delete('documents/' . $nomFichier);
            }

            return response()->json([
                'success' => true,
                'message' => 'Document supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharger un document
     */
    public function downloadDocument($id)
    {
        try {
            $document = Document::findOrFail($id);
            $cheminFichier = 'documents/' . $document->nom_fichier;

            if (!Storage::disk('public')->exists($cheminFichier)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier non trouvé'
                ], 404);
            }

            return Storage::disk('public')->download($cheminFichier, $document->label_doc . '.' . pathinfo($document->nom_fichier, PATHINFO_EXTENSION));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les documents par session
     */
    public function getDocumentsBySession($codeSession)
    {
        try {
            $documents = Document::where('code_session', $codeSession)
                ->with(['sessionExamen', 'bureau'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $documents,
                'message' => 'Documents de la session récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GESTION DES SALLES
     */

    /**
     * Afficher la liste des salles
     */
    public function indexSalles()
    {
        try {
            $salles = Salle::orderBy('code_salle')->get();

            return response()->json([
                'success' => true,
                'data' => $salles,
                'message' => 'Liste des salles récupérée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des salles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer une nouvelle salle
     */
    public function storeSalle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_salle' => 'required|string|max:32|unique:salle,code_salle',
            'nb_place_salle' => 'required|integer|min:1',
            'etat_salle' => 'required|boolean',
            'desc_salle' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $salle = Salle::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $salle,
                'message' => 'Salle créée avec succès'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la salle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher une salle spécifique
     */
    public function showSalle($codeSalle)
    {
        try {
            $salle = Salle::findOrFail($codeSalle);

            return response()->json([
                'success' => true,
                'data' => $salle,
                'message' => 'Salle récupérée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Salle non trouvée',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mettre à jour une salle
     */
    public function updateSalle(Request $request, $codeSalle)
    {
        $validator = Validator::make($request->all(), [
            'code_salle' => 'required|string|max:32|unique:salle,code_salle,' . $codeSalle . ',code_salle',
            'nb_place_salle' => 'required|integer|min:1',
            'etat_salle' => 'required|boolean',
            'desc_salle' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $salle = Salle::findOrFail($codeSalle);
            $salle->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $salle,
                'message' => 'Salle mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la salle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une salle
     */
    public function destroySalle($codeSalle)
    {
        try {
            $salle = Salle::findOrFail($codeSalle);
            
            // Vérifier si la salle est utilisée dans des périodes
            if ($salle->periodes()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cette salle car elle est utilisée dans des planifications'
                ], 400);
            }

            $salle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Salle supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la salle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les salles disponibles
     */
    public function getSallesDisponibles()
    {
        try {
            $salles = Salle::where('etat_salle', true)
                ->orderBy('code_salle')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $salles,
                'message' => 'Salles disponibles récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des salles disponibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier la disponibilité d'une salle pour une période donnée
     */
    public function verifierDisponibiliteSalle(Request $request, $codeSalle)
    {
        $validator = Validator::make($request->all(), [
            'debut_periode' => 'required|date',
            'fin_periode' => 'required|date|after:debut_periode',
            'jour_periode' => 'required|integer|between:1,7'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $salle = Salle::findOrFail($codeSalle);

            if (!$salle->etat_salle) {
                return response()->json([
                    'success' => false,
                    'disponible' => false,
                    'message' => 'La salle n\'est pas disponible (état désactivé)'
                ]);
            }

            // Vérifier les conflits avec les périodes existantes
            $conflits = $salle->periodes()
                ->where('jour_periode', $request->jour_periode)
                ->where(function($query) use ($request) {
                    $query->whereBetween('debut_periode', [$request->debut_periode, $request->fin_periode])
                          ->orWhereBetween('fin_periode', [$request->debut_periode, $request->fin_periode])
                          ->orWhere(function($q) use ($request) {
                              $q->where('debut_periode', '<=', $request->debut_periode)
                                ->where('fin_periode', '>=', $request->fin_periode);
                          });
                })
                ->exists();

            return response()->json([
                'success' => true,
                'disponible' => !$conflits,
                'message' => $conflits ? 'Salle non disponible pour cette période' : 'Salle disponible'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification de disponibilité',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des ressources
     */
    public function getStatistiquesRessources()
    {
        try {
            $stats = [
                'total_documents' => Document::count(),
                'documents_par_type' => Document::selectRaw('type_doc, COUNT(*) as total')
                    ->groupBy('type_doc')
                    ->get(),
                'total_salles' => Salle::count(),
                'salles_disponibles' => Salle::where('etat_salle', true)->count(),
                'salles_indisponibles' => Salle::where('etat_salle', false)->count(),
                'capacite_totale' => Salle::where('etat_salle', true)->sum('nb_place_salle'),
                'documents_recents' => Document::with(['sessionExamen', 'bureau'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques des ressources récupérées avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}