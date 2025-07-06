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
        $query = Publication::with('createur');

        if ($request->has('type')) {
            $query->where('type_publi', $request->type);
        }

        if ($request->has('domaine')) {
            $query->where('domaine', 'like', '%' . $request->domaine . '%');
        }

        if ($request->has('annee')) {
            $query->whereYear('created_at', $request->annee);
        }

        $publications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('sige_app.frontend.labo.publications.index', compact('publications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $membres = PersLab::with('laboratoire')->where('statut', 'actif')->get();

        return view('sige_app.frontend.labo.publications.create', compact('membres'));
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
            'rapport_path' => 'nullable|string',
            'id_pers_lab' => 'required|exists:pers_lab,id_pers_lab',
            'code_lab' => 'nullable|exists:laboratoire,code_lab'
        ]);

        Publication::create($validated);

        return redirect()->route('publications.index')
            ->with('success', 'Publication ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_publi)
    {
        $publication = Publication::with(['createur.laboratoire'])
            ->where('code_publi', $code_publi)
            ->firstOrFail();

        return view('sige_app.frontend.labo.publications.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code_publi)
    {
        $publication = Publication::where('code_publi', $code_publi)->firstOrFail();
        $membres = PersLab::with('laboratoire')->where('statut', 'actif')->get();

        return view('sige_app.frontend.labo.publications.edit', compact('publication', 'membres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_publi)
    {
        $publication = Publication::where('code_publi', $code_publi)->firstOrFail();

        $validated = $request->validate([
            'titre_publi' => 'required|max:255',
            'type_publi' => 'required|in:article,conference,livre,rapport,these',
            'domaine' => 'nullable|max:100',
            'resume' => 'nullable',
            'tags' => 'nullable|string',
            'reference' => 'nullable|string',
            'rapport_path' => 'nullable|string',
            'id_pers_lab' => 'required|exists:pers_lab,id_pers_lab',
            'code_lab' => 'nullable|exists:laboratoire,code_lab'
        ]);

        $publication->update($validated);

        return redirect()->route('publications.show', $publication->code_publi)
            ->with('success', 'Publication mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_publi)
    {
        $publication = Publication::where('code_publi', $code_publi)->firstOrFail();
        $publication->delete();

        return redirect()->route('publications.index')
            ->with('success', 'Publication supprimée avec succès.');
    }
}
