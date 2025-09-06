<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\notes\Document;
use App\Models\notes\Salle;
use App\Models\notes\SessionExamen;
use App\Models\Bureau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Throwable;

class RessourceController extends Controller
{
    /**
     * GESTION DES DOCUMENTS
     */
    
    /**
     * Afficher la liste des documents avec vue calendrier
     */
    public function indexDocuments(Request $request)
    {
        try {
            $query = Document::with(['sessionExamen', 'bureau']);

            // Filtres
            if ($request->filled('session')) {
                $query->where('code_session', $request->session);
            }

            if ($request->filled('bureau')) {
                $query->where('code_bureau', $request->bureau);
            }

            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $query->whereBetween('created_at', [
                    $request->date_debut,
                    $request->date_fin
                ]);
            }

            $documents = $query->orderBy('created_at', 'desc')->paginate(15);

            // Données pour les filtres
            $sessions = SessionExamen::orderBy('code_session')->get();
            $bureaux = Bureau::orderBy('code_bureau')->get();

            return view('sige_app.backend.gestion_notes.ressource.documents.index', compact('documents', 'sessions', 'bureaux'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage des documents: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du chargement des documents.');
        }
    }

    /**
     * Show the form for creating a new document
     */
    public function createDocument()
    {
        try {
            $sessions = SessionExamen::orderBy('code_session')->get();
            $bureaux = Bureau::orderBy('code_bureau')->get();

            return view('sige_app.backend.gestion_notes.ressource.documents.create', compact('sessions', 'bureaux'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création de document: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('ressources.documents.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created document
     */
    public function storeDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_doc' => 'required|string|max:128',
            'type_doc' => 'required|string|max:128',
            'fichier' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'code_session' => 'nullable|string|exists:session_examen,code_session',
            'code_bureau' => 'nullable|string|exists:bureau,code_bureau',
            'description_doc' => 'nullable|string'
        ], [
            'label_doc.required' => 'Le libellé du document est obligatoire.',
            'type_doc.required' => 'Le type de document est obligatoire.',
            'fichier.required' => 'Le fichier est obligatoire.',
            'fichier.mimes' => 'Le fichier doit être au format PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG ou PNG.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

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

            DB::commit();

            Log::info('Document créé avec succès', [
                'document_id' => $document->id,
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);

            return redirect()->route('ressources.documents.index')
                ->with('success', 'Document créé avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            // Supprimer le fichier en cas d'erreur
            if (isset($cheminFichier)) {
                Storage::disk('public')->delete($cheminFichier);
            }

            Log::error('Erreur lors de la création du document: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création du document.')
                ->withInput();
        }
    }

    /**
     * Display the specified document
     */
    public function showDocument($id)
    {
        try {
            $document = Document::with(['sessionExamen', 'bureau'])->findOrFail($id);

            return view('sige_app.backend.gestion_notes.ressource.documents.show', compact('document'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du document: ' . $e->getMessage(), [
                'document_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('ressources.documents.index')
                ->with('error', 'Document introuvable ou erreur lors du chargement.');
        }
    }

    /**
     * Show the form for editing the specified document
     */
    public function editDocument($id)
    {
        try {
            $document = Document::findOrFail($id);
            $sessions = SessionExamen::orderBy('code_session')->get();
            $bureaux = Bureau::orderBy('code_bureau')->get();

            return view('sige_app.backend.gestion_notes.ressource.documents.edit', compact('document', 'sessions', 'bureaux'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification de document: ' . $e->getMessage(), [
                'document_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('ressources.documents.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Update the specified document
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
        ], [
            'label_doc.required' => 'Le libellé du document est obligatoire.',
            'type_doc.required' => 'Le type de document est obligatoire.',
            'fichier.mimes' => 'Le fichier doit être au format PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG ou PNG.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $document = Document::findOrFail($id);
            $ancienFichier = $document->nom_fichier;

            // Si un nouveau fichier est fourni
            if ($request->hasFile('fichier')) {
                $file = $request->file('fichier');
                $nomFichier = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $cheminFichier = $file->storeAs('documents', $nomFichier, 'public');

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

            DB::commit();

            Log::info('Document modifié avec succès', [
                'document_id' => $document->id,
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);

            return redirect()->route('ressources.documents.index')
                ->with('success', 'Document modifié avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();
            
            // Supprimer le nouveau fichier en cas d'erreur
            if (isset($cheminFichier)) {
                Storage::disk('public')->delete($cheminFichier);
            }

            Log::error('Erreur lors de la modification du document: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la modification du document.')
                ->withInput();
        }
    }

    /**
     * Remove the specified document
     */
    public function destroyDocument($id)
    {
        try {
            DB::beginTransaction();

            $document = Document::findOrFail($id);
            $nomFichier = $document->nom_fichier;

            $document->delete();

            // Supprimer le fichier physique
            if ($nomFichier && Storage::disk('public')->exists('documents/' . $nomFichier)) {
                Storage::disk('public')->delete('documents/' . $nomFichier);
            }

            DB::commit();

            Log::info('Document supprimé avec succès', [
                'document_id' => $id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('ressources.documents.index')
                ->with('success', 'Document supprimé avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression du document: ' . $e->getMessage(), [
                'document_id' => $id,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression du document.');
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
            $salles = Salle::orderBy('code_salle')->paginate(15);

            return view('sige_app.backend.gestion_notes.ressource.salles.index', compact('salles'));

        } catch (Throwable $e) {
            Log::error<|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|><|reserved_token_163839|>I have gathered the relevant information from the RessourceController.php file.

Information Gathered:
- The method indexSalles() fetches all Salle records using `Salle::orderBy('code_salle')->get()` and passes them to the view.
- The view expects a paginated result because it calls `$salles->links()` for pagination.
- The current code fetches all records as a Collection, which does not have the links() method, causing the error.

Plan:
- Modify the indexSalles() method in RessourceController.php to use pagination instead of get().
  Change:
  ```php
  $salles = Salle::orderBy('code_salle')->get();
  ```
  To:
  ```php
  $salles = Salle::orderBy('code_salle')->paginate(15);
  ```
- This will return a LengthAwarePaginator instance that supports the links() method.
- No other dependent files need editing as the view and route are already set up.

Followup steps:
- Test the /ressources/salles page to confirm pagination works and the error is resolved.

Please confirm if I can proceed with this plan. 

<ask_followup_question>
<question>May I proceed to update the indexSalles() method to use pagination?</question>

    /**
     * Show the form for creating a new salle
     */
    public function createSalle()
    {
        return view('sige_app.backend.gestion_notes.ressource.salles.create');
    }

    /**
     * Store a newly created salle
     */
    public function storeSalle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_salle' => 'required|string|max:32|unique:salle,code_salle',
            'nb_place_salle' => 'required|integer|min:1',
            'etat_salle' => 'required|boolean',
            'desc_salle' => 'nullable|string'
        ], [
            'code_salle.required' => 'Le code de la salle est obligatoire.',
            'code_salle.unique' => 'Ce code de salle existe déjà.',
            'nb_place_salle.required' => 'Le nombre de places est obligatoire.',
            'nb_place_salle.min' => 'Le nombre de places doit être au moins 1.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Salle::create($request->all());

            Log::info('Salle créée avec succès', [
                'code_salle' => $request->code_salle,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('ressources.salles.index')
                ->with('success', 'Salle créée avec succès.');

        } catch (Throwable $e) {
            Log::error('Erreur lors de la création de la salle: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de la salle.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified salle
     */
    public function editSalle($codeSalle)
    {
        try {
            $salle = Salle::findOrFail($codeSalle);

            return view('sige_app.backend.gestion_notes.ressource.salles.edit', compact('salle'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification de salle: ' . $e->getMessage(), [
                'code_salle' => $codeSalle,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('ressources.salles.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Update the specified salle
     */
    public function updateSalle(Request $request, $codeSalle)
    {
        $validator = Validator::make($request->all(), [
            'code_salle' => 'required|string|max:32|unique:salle,code_salle,' . $codeSalle . ',code_salle',
            'nb_place_salle' => 'required|integer|min:1',
            'etat_salle' => 'required|boolean',
            'desc_salle' => 'nullable|string'
        ], [
            'code_salle.required' => 'Le code de la salle est obligatoire.',
            'code_salle.unique' => 'Ce code de salle existe déjà.',
            'nb_place_salle.required' => 'Le nombre de places est obligatoire.',
            'nb_place_salle.min' => 'Le nombre de places doit être au moins 1.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $salle = Salle::findOrFail($codeSalle);
            $salle->update($request->all());

            Log::info('Salle modifiée avec succès', [
                'code_salle' => $codeSalle,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('ressources.salles.index')
                ->with('success', 'Salle modifiée avec succès.');

        } catch (Throwable $e) {
            Log::error('Erreur lors de la modification de la salle: ' . $e->getMessage(), [
                'code_salle' => $codeSalle,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la modification de la salle.')
                ->withInput();
        }
    }

    /**
     * Remove the specified salle
     */
    public function destroySalle($codeSalle)
    {
        try {
            DB::beginTransaction();

            $salle = Salle::findOrFail($codeSalle);
            
            // Vérifier si la salle est utilisée
            if ($salle->periodes()->exists()) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette salle car elle est utilisée dans des planifications.');
            }

            $salle->delete();

            DB::commit();

            Log::info('Salle supprimée avec succès', [
                'code_salle' => $codeSalle,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('ressources.salles.index')
                ->with('success', 'Salle supprimée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression de la salle: ' . $e->getMessage(), [
                'code_salle' => $codeSalle,
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de la salle.');
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
                return redirect()->back()
                    ->with('error', 'Fichier non trouvé.');
            }

            return Storage::disk('public')->download($cheminFichier, $document->label_doc . '.' . pathinfo($document->nom_fichier, PATHINFO_EXTENSION));

        } catch (Throwable $e) {
            Log::error('Erreur lors du téléchargement du document: ' . $e->getMessage(), [
                'document_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du téléchargement du fichier.');
        }
    }
}
