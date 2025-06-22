<?php

namespace App\Http\Controllers\requetes;

use App\Http\Controllers\Controller;
use App\Models\requetes\Requete;
use App\Models\requetes\Category;
use App\Models\Bureau;
use App\Models\requetes\FichierRequete;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\requetes\RequeteSubmittedMail;
use App\Mail\requetes\RequetteStatusChangeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RequetteController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     *
     */


    public function index(Request $request)

    {
        $id = $request->session()->get('user')->code_user;
        $query = Requete::with(['category', 'user', 'bureau'])
            ->where('code_user', $id);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('code_cat', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_sousmis', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_sousmis', '<=', $request->date_to);
        }

        $requetes = $query->orderBy('date_sousmis', 'desc')->paginate(10);
        $categories = Category::all();
        return view('sige_app.backend.requetes.index', compact('requetes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $bureaux = Bureau::all();
        return view('sige_app.backend.requetes.create', compact('categories', 'bureaux'));
    }

    /**
     * Store a newly created resource in storage.


     */


    public function store(Request $request)
    {
        $request->validate([
            'titre_requete' => 'required|string|max:180',
            'desc_requete' => 'required|string|max:180',
            'date_asignation' => 'nullable|date',
            'date_traitement' => 'nullable|date',
            'note_interne' => 'nullable|string|max:255',
            'code_cat' => 'required|exists:categories,code_cat',
            // 'code_bureau' => 'required|exists:bureau,code_bureau',
            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
            // 'priorite' => 'in:urgent,standard'
        ]);

        try {
            // Générer un code unique pour la requête
            //  $codeRequete = 'REQ-' . date('Ymd') . '-' . strtoupper(Str::random(8));
            $user = session('user');
            // Bureau par défaut : Scolarité
            $bureauScolarite = Bureau::where('label_bureau', 'Scolarite')
                ->orWhere('code_bureau', 'SCOL')
                ->first();

            if (!$bureauScolarite) {
                return back()->withErrors(['error' => 'Service Scolarité non trouvé. Contactez l\'administrateur.']);
            }
            // Créer la requête
            $requete = Requete::create([
                //  'code_requete' => $codeRequete,
                'titre_requete' => $request->titre_requete,
                'desc_requete' => $request->desc_requete,
                'date_asignation' => null,
                'date_traitement' => null,
                'note_interne' => null,
                'status' => 'en attente',
                'date_sousmis' => now(),
                'code_cat' => $request->code_cat,
                'code_user' => $user->code_user,
                'code_bureau' => $bureauScolarite->code_bureau,
                // 'priorite' => $request->priorite ?? 'standard'
            ]);

            // Gérer les fichiers joints
            if ($request->hasFile('fichiers')) {
                foreach ($request->file('fichiers') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('requetes_fichiers', $filename, 'public');

                    FichierRequete::create([
                        'id_fichier' => 'FILE-' . strtoupper(Str::random(10)),
                        'chemin' => $path,
                        'code_requete' => $requete->code_requete,
                        'nom_original' => $file->getClientOriginalName(),
                        'taille' => $file->getSize()
                    ]);
                }
            }

            // Envoyer email de confirmation à l'étudiant
            $user = session('user');
            // if (!$user) {
            //     abort(401, 'Utilisateur non authentifié');
            // }
            $mailSent = true;
            try {
                Mail::to($user->email_user)->send(new RequeteSubmittedMail($requete));
            } catch (\Exception $mailException) {
                $mailSent = false;
                // Log::error('Erreur lors de l\'envoi de l\'email de confirmation: ' . $mailException->getMessage());
            }

            $successMessage = 'Votre requête a été soumise avec succès. Numéro de référence: ' . $requete->code_requete;
            if (!$mailSent) {
                $successMessage .= ' Cependant, l\'email de confirmation n\'a pas pu être envoyé.';
            }

            return redirect()->route('requetes.index', $requete->code_requete)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            // Log::error('Erreur lors de la soumission de la requête: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erreur lors de la soumission de la requête. Veuillez réessayer. Détails: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_requete)
    {
        $user = session('user');
        $requete = Requete::with(['category', 'user', 'bureau', 'fichiers', 'reponses'])
            ->where('code_requete', $code_requete)
            ->where('code_user', $user->code_user)
            ->firstOrFail();

        // Fetch personnel managing the bureau at each step date
        $progressSteps = [];

        // Helper function to get personnel managing bureau at a given date
        $getManagerAtDate = function ($bureauCode, $date) {
            if (!$date) {
                return null;
            }
            $persRole = \App\Models\PersRole::where('code_bureau', $bureauCode)
                ->where('statut_role', \App\Models\PersRole::STATUT_ACTIF)
                ->where(function ($query) use ($date) {
                    $query->whereNull('date_fin_role')
                          ->orWhere('date_fin_role', '>', $date);
                })
                ->where('date_debut_role', '<=', $date)
                ->with('personnel')
                ->first();
            return $persRole ? $persRole->personnel : null;
        };

        // Step: Soumission
        $submissionDate = $requete->date_sousmis;
        $submissionBureau = $requete->bureau;
        $submissionManager = $getManagerAtDate($submissionBureau->code_bureau ?? null, $submissionDate);
        $progressSteps[] = [
            'step' => 'Soumission',
            'date' => $submissionDate,
            'bureau' => $submissionBureau,
            'manager' => $submissionManager,
            'purpose' => $requete->desc_requete,
            'sender' => $requete->user,
            'recipient' => $submissionManager ?? $submissionBureau,
        ];

        // Step: Assignation
        $assignDate = $requete->date_asignation;
        $assignBureau = $requete->bureau; // Assuming same bureau, adjust if different
        $assignManager = $getManagerAtDate($assignBureau->code_bureau ?? null, $assignDate);
        $progressSteps[] = [
            'step' => 'Assignation',
            'date' => $assignDate,
            'bureau' => $assignBureau,
            'manager' => $assignManager,
            'purpose' => $requete->note_interne,
            'sender' => $submissionManager,
            'recipient' => $assignManager ?? $assignBureau,
        ];

        // Step: Traitement
        $treatmentDate = $requete->date_traitement;
        $treatmentBureau = $requete->bureau; // Assuming same bureau, adjust if different
        $treatmentManager = $getManagerAtDate($treatmentBureau->code_bureau ?? null, $treatmentDate);
        $progressSteps[] = [
            'step' => 'Traitement',
            'date' => $treatmentDate,
            'bureau' => $treatmentBureau,
            'manager' => $treatmentManager,
            'purpose' => $requete->note_interne,
            'sender' => $assignManager,
            'recipient' => $treatmentManager ?? $treatmentBureau,
        ];

        // Paginate progressSteps array manually
        $currentPage = request()->get('page', 1);
        $perPage = 2;
        $offset = ($currentPage - 1) * $perPage;
        $itemsForCurrentPage = array_slice($progressSteps, $offset, $perPage);
        $progressSteps = new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsForCurrentPage,
            count($progressSteps),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('sige_app.backend.requetes.show', compact('requete', 'progressSteps'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code_requete)
    {
        $user = session('user');
        $requete = Requete::where('code_requete', $code_requete)
            ->where('code_user', $user->code_user)
            ->where('status', 'en attente')
            ->firstOrFail();

        $categories = Category::all();
        $bureaux = Bureau::all();
        // $personnel = session('user');
        return view('sige_app.backend.requetes.edit', compact('requete', 'categories', 'bureaux'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_requete)
    {
        $user = session('user');
        $requete = Requete::where('code_requete', $code_requete)
            ->where('code_user', $user->code_user)
            ->where('status', 'en attente')
            ->firstOrFail();

        $request->validate([
            'titre_requete' => 'required|string|max:180',
            'desc_requete' => 'required|string|max:180',
            'code_cat' => 'required|exists:categories,code_cat',
            // 'code_bureau' => 'required|exists:bureau,code_bureau',
            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            // 'priorite' => 'in:urgent,standard'
        ]);

        try {
            $requete->update([
                'titre_requete' => $request->titre_requete,
                'desc_requete' => $request->desc_requete,
                'code_cat' => $request->code_cat,
                // 'code_bureau' => $request->code_bureau,
                // 'priorite' => $request->priorite ?? 'standard'
            ]);

            // Gérer les nouveaux fichiers
            if ($request->hasFile('fichiers')) {
                foreach ($request->file('fichiers') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('requetes_fichiers', $filename, 'public');

                    FichierRequete::create([
                        'id_fichier' => 'FILE-' . strtoupper(Str::random(10)),
                        'chemin' => $path,
                        'code_requete' => $code_requete,
                        'nom_original' => $file->getClientOriginalName(),
                        'taille' => $file->getSize()
                    ]);
                }
            }

            Log::info('Requete updated successfully: ' . $code_requete);

            return redirect()->route('requetes.show', $code_requete)
                ->with('success', 'Votre requête a été mise à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la requête: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour de la requête.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_requete)
    {
        $user = session('user');
        $requete = Requete::where('code_requete', $code_requete)
            ->where('code_user', $user->code_user)
            ->where('status', 'en attente')
            ->firstOrFail();

        try {
            // Supprimer les fichiers associés
            foreach ($requete->fichiers as $fichier) {
                Storage::disk('public')->delete($fichier->chemin);
                $fichier->delete();
            }

            $requete->delete();

            return redirect()->route('requetes.index')
                ->with('success', 'Requête supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de la requête.');
        }
    }

    /**
     * Supprimer un fichier spécifique
     */
    public function deleteFichier(string $id_fichier)
    {
        $fichier = FichierRequete::where('id_fichier', $id_fichier)
            ->whereHas('requete', function ($query) {
                $query->where('code_user', Auth::user()->code_user)
                    ->where('status', 'en attente');
            })
            ->firstOrFail();

        try {
            Storage::disk('public')->delete($fichier->chemin);
            $fichier->delete();

            return back()->with('success', 'Fichier supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du fichier.');
        }
    }

    /**
     * Télécharger un fichier
     */
    public function downloadFichier(string $id_fichier)
    {
        $fichier = FichierRequete::where('id_fichier', $id_fichier)
            ->whereHas('requete', function ($query) {
                $query->where('code_user', Auth::user()->code_user);
            })
            ->firstOrFail();

        if (!Storage::disk('public')->exists($fichier->chemin)) {
            abort(404, 'Fichier non trouvé');
        }

        $filePath = Storage::disk('public')->path($fichier->chemin);
        return response()->download($filePath, $fichier->nom_original);
    }

    /**
     * Tableau de bord des statistiques
     */


    /**
     * Afficher les statistiques des requêtes
     */
}
