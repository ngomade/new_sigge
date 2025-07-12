<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Publication;
use App\Models\laboratoires\PersLab;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        $userType = session('user_type');

        $query = Publication::with(['createur.personnel', 'createur.user', 'createur']);

        $isAdmin = false;
        if (session()->has('laboratoire_code') && $userId && $userType) {
            $codeLab = session('laboratoire_code');
            $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $codeLab)
                ->where('statut', 'actif')
                ->where(function ($q) use ($userId, $userType) {
                    if ($userType === 'externe') {
                        $q->where('id_user_externe', $userId);
                    } else {
                        $q->where('id_pers_lab', $userId);
                    }
                })
                ->with('roleLabo')
                ->first();

            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }

        // Filtres
        if ($request->filled('type')) {
            $query->where('type_publi', $request->type);
        }

        if ($request->filled('domaine')) {
            $query->where('domaine', 'like', '%' . $request->domaine . '%');
        }

        if ($request->filled('annee')) {
            $query->whereYear('created_at', $request->annee);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre_publi', 'like', "%$search%")
                  ->orWhere('domaine', 'like', "%$search%")
                  ->orWhere('tags', 'like', "%$search%")
                  ->orWhere('reference', 'like', "%$search%");
            });
        }

        $publications = $query->orderBy('created_at', 'desc')->paginate(10);

        $laboratoire = null;
        if (session()->has('laboratoire_code')) {
            $laboratoire = \App\Models\laboratoires\Laboratoire::where('code_lab', session('laboratoire_code'))->first();
        }

        // Statistiques
        $stats = [
            'total' => Publication::count(),
            'par_type' => Publication::selectRaw('type_publi, COUNT(*) as total')->groupBy('type_publi')->pluck('total', 'type_publi')->toArray(),
            'par_annee' => Publication::selectRaw('YEAR(created_at) as annee, COUNT(*) as total')->groupBy('annee')->orderBy('annee', 'desc')->pluck('total', 'annee')->toArray(),
        ];

        // Données pour les filtres
        $types = ['article', 'conference', 'livre', 'rapport', 'these'];
        $annees = Publication::selectRaw('YEAR(created_at) as annee')->distinct()->orderBy('annee', 'desc')->pluck('annee');

        return view('laboratoires.admin.publications.index', compact(
            'publications',
            'laboratoire',
            'stats',
            'types',
            'annees',
            'request'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laboratoire = null;
        if (session()->has('laboratoire_code')) {
            $laboratoire = \App\Models\laboratoires\Laboratoire::where('code_lab', session('laboratoire_code'))->first();
        }

        return view('laboratoires.admin.publications.create', compact('laboratoire'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre_publi' => 'required|max:255',
            'type_publi' => 'required|in:article,conference,livre,rapport,these',
            'domaine' => 'nullable|max:100',
            'resume' => 'nullable',
            'tags' => 'nullable|string',
            'reference' => 'nullable|string',
            'rapport' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240', // 10MB max
        ]);

        // Gérer l'upload du fichier rapport
        if ($request->hasFile('rapport')) {
            $rapportPath = $request->file('rapport')->store('publications/rapports', 'public');
            $validated['rapport_path'] = $rapportPath;
        }

        // Récupérer l'utilisateur connecté depuis la session
        $userId = session('user_id');
        $userType = session('user_type');
        $codeLab = session('laboratoire_code');

        if (!$userId || !$userType || !$codeLab) {
            return back()->withInput()->with('error', 'Vous devez être connecté pour créer une publication.');
        }

        // L'id_pers_lab est le même que l'user_id pour tous les types
        $validated['id_pers_lab'] = $userId;
        $validated['code_lab'] = $codeLab;

        Publication::create($validated);

        return redirect()->route('labo.publications.index')
            ->with('success', 'Publication ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_publi)
    {
        $userId = session('user_id');
        $userType = session('user_type');

        $publication = Publication::with(['createur', 'laboratoire'])
            ->where('code_publi', $code_publi)
            ->firstOrFail();

        $laboratoire = $publication->laboratoire;

        // Check if user is admin or creator
        $isAdmin = false;
        if (session()->has('laboratoire_code') && $userId && $userType) {
            $codeLab = session('laboratoire_code');
            $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $codeLab)
                ->where('statut', 'actif')
                ->where(function ($q) use ($userId, $userType) {
                    if ($userType === 'externe') {
                        $q->where('id_user_externe', $userId);
                    } else {
                        $q->where('id_pers_lab', $userId);
                    }
                })
                ->with('roleLabo')
                ->first();

            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }

        if (!$isAdmin && $publication->id_pers_lab !== $userId) {
            abort(403, 'Unauthorized access to this publication.');
        }

        return view('laboratoires.admin.publications.show', compact('publication', 'laboratoire'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code_publi)
    {
        $userId = session('user_id');
        $userType = session('user_type');

        $publication = Publication::with(['createur', 'laboratoire'])
            ->where('code_publi', $code_publi)
            ->firstOrFail();

        $laboratoire = $publication->laboratoire;

        // Check if user is admin or creator
        $isAdmin = false;
        if (session()->has('laboratoire_code') && $userId && $userType) {
            $codeLab = session('laboratoire_code');
            $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $codeLab)
                ->where('statut', 'actif')
                ->where(function ($q) use ($userId, $userType) {
                    if ($userType === 'externe') {
                        $q->where('id_user_externe', $userId);
                    } else {
                        $q->where('id_pers_lab', $userId);
                    }
                })
                ->with('roleLabo')
                ->first();

            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }

        if (!$isAdmin && $publication->id_pers_lab !== $userId) {
            abort(403, 'Unauthorized access to edit this publication.');
        }

        return view('laboratoires.admin.publications.edit', compact('publication', 'laboratoire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_publi)
    {
        $userId = session('user_id');
        $userType = session('user_type');

        $publication = Publication::where('code_publi', $code_publi)->firstOrFail();

        // Check if user is admin or creator
        $isAdmin = false;
        if (session()->has('laboratoire_code') && $userId && $userType) {
            $codeLab = session('laboratoire_code');
            $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $codeLab)
                ->where('statut', 'actif')
                ->where(function ($q) use ($userId, $userType) {
                    if ($userType === 'externe') {
                        $q->where('id_user_externe', $userId);
                    } else {
                        $q->where('id_pers_lab', $userId);
                    }
                })
                ->with('roleLabo')
                ->first();

            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }

        if (!$isAdmin && $publication->id_pers_lab !== $userId) {
            abort(403, 'Unauthorized access to update this publication.');
        }

        $validated = $request->validate([
            'titre_publi' => 'required|max:255',
            'type_publi' => 'required|in:article,conference,livre,rapport,these',
            'domaine' => 'nullable|max:100',
            'resume' => 'nullable',
            'tags' => 'nullable|string',
            'reference' => 'nullable|string',
            'rapport' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240', // 10MB max
        ]);

        // Gérer l'upload du fichier rapport
        if ($request->hasFile('rapport')) {
            // Supprimer l'ancien fichier s'il existe
            if ($publication->rapport_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($publication->rapport_path);
            }
            $rapportPath = $request->file('rapport')->store('publications/rapports', 'public');
            $validated['rapport_path'] = $rapportPath;
        }

        $publication->update($validated);

        return redirect()->route('labo.publications.show', $publication->code_publi)
            ->with('success', 'Publication mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_publi)
    {
        $userId = session('user_id');
        $userType = session('user_type');

        $publication = Publication::where('code_publi', $code_publi)->firstOrFail();

        // Check if user is admin or creator
        $isAdmin = false;
        if (session()->has('laboratoire_code') && $userId && $userType) {
            $codeLab = session('laboratoire_code');
            $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $codeLab)
                ->where('statut', 'actif')
                ->where(function ($q) use ($userId, $userType) {
                    if ($userType === 'externe') {
                        $q->where('id_user_externe', $userId);
                    } else {
                        $q->where('id_pers_lab', $userId);
                    }
                })
                ->with('roleLabo')
                ->first();

            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }

        if (!$isAdmin && $publication->id_pers_lab !== $userId) {
            abort(403, 'Unauthorized access to delete this publication.');
        }

        $publication->delete();

        return redirect()->route('labo.publications.index')
            ->with('success', 'Publication supprimée avec succès.');
    }
}
