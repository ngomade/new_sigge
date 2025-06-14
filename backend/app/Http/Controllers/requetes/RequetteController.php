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

class RequetteController extends Controller
{
    //
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Requete::with(['category', 'user', 'bureau'])
            ->where('code_user', Auth::user()->code_user);

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
            'code_cat' => 'required|exists:categories,code_cat',
            'code_bureau' => 'required|exists:bureau,code_bureau',
            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
            'priorite' => 'in:urgent,standard'
        ]);

        try {
            // Générer un code unique pour la requête
            $codeRequete = 'REQ-' . date('Ymd') . '-' . strtoupper(Str::random(8));

            // Créer la requête
            $requete = Requete::create([
                'code_requete' => $codeRequete,
                'titre_requete' => $request->titre_requete,
                'desc_requete' => $request->desc_requete,
                'status' => 'en attente',
                'date_sousmis' => now(),
                'code_cat' => $request->code_cat,
                'code_user' => Auth::user()->code_user,
                'code_bureau' => $request->code_bureau,
                'priorite' => $request->priorite ?? 'standard'
            ]);

            // Gérer les fichiers joints
            if ($request->hasFile('fichiers')) {
                foreach ($request->file('fichiers') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('requetes_fichiers', $filename, 'public');

                    FichierRequete::create([
                        'id_fichier' => 'FILE-' . strtoupper(Str::random(10)),
                        'chemin' => $path,
                        'code_requete' => $codeRequete,
                        'nom_original' => $file->getClientOriginalName(),
                        'taille' => $file->getSize()
                    ]);
                }
            }

            // Envoyer email de confirmation à l'étudiant
            Mail::to(Auth::user()->email_user)->send(new RequeteSubmittedMail($requete));

            

            return redirect()->route('requetes.show', $requete->code_requete)
                ->with('success', 'Votre requête a été soumise avec succès. Numéro de référence: ' . $codeRequete);

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la soumission de la requête. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_requete)
    {
        $requete = Requete::with(['category', 'user', 'bureau', 'fichiers', 'reponses'])
            ->where('code_requete', $code_requete)
            ->where('code_user', Auth::user()->code_user)
            ->firstOrFail();

        return view('sige_app.backend.requetes.show', compact('requete'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code_requete)
    {
        $requete = Requete::where('code_requete', $code_requete)
            ->where('code_user', Auth::user()->code_user)
            ->where('status', 'en attente')
            ->firstOrFail();

        $categories = Category::all();
        $bureaux = Bureau::all();

        return view('sige_app.backend.requetes.edit', compact('requete', 'categories', 'bureaux'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_requete)
    {
        $requete = Requete::where('code_requete', $code_requete)
            ->where('code_user', Auth::user()->code_user)
            ->where('status', 'en attente')
            ->firstOrFail();

        $request->validate([
            'titre_requete' => 'required|string|max:180',
            'desc_requete' => 'required|string|max:180',
            'code_cat' => 'required|exists:categories,code_cat',
            'code_bureau' => 'required|exists:bureau,code_bureau',
            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'priorite' => 'in:urgent,standard'
        ]);

        try {
            $requete->update([
                'titre_requete' => $request->titre_requete,
                'desc_requete' => $request->desc_requete,
                'code_cat' => $request->code_cat,
                'code_bureau' => $request->code_bureau,
                'priorite' => $request->priorite ?? 'standard'
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

            return redirect()->route('requetes.show', $code_requete)
                ->with('success', 'Votre requête a été mise à jour avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour de la requête.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_requete)
    {
        $requete = Requete::where('code_requete', $code_requete)
            ->where('code_user', Auth::user()->code_user)
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
            ->whereHas('requete', function($query) {
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
            ->whereHas('requete', function($query) {
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
    

}
