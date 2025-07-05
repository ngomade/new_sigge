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
            $query->whereYear('date_publi', $request->annee);
        }

        $publications = $query->orderBy('date_publi', 'desc')->paginate(10);

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
            'date_publi' => 'required|date',
            'domaine' => 'required|max:100',
            'resume' => 'nullable',
            'id_pers_lab' => 'required|exists:pers_lab,id_pers_lab'
        ]);

        Publication::create($validated);

        return redirect()->route('publications.index')
            ->with('success', 'Publication ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $publication = Publication::with(['createur.laboratoire'])
            ->findOrFail($id);

        return view('sige_app.frontend.labo.publications.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $publication = Publication::findOrFail($id);
        $membres = PersLab::with('laboratoire')->where('statut', 'actif')->get();

        return view('sige_app.frontend.labo.publications.edit', compact('publication', 'membres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $publication = Publication::findOrFail($id);

        $validated = $request->validate([
            'titre_publi' => 'required|max:255',
            'type_publi' => 'required|in:article,conference,livre,rapport,these',
            'date_publi' => 'required|date',
            'domaine' => 'required|max:100',
            'resume' => 'nullable',
            'id_pers_lab' => 'required|exists:pers_lab,id_pers_lab'
        ]);

        $publication->update($validated);

        return redirect()->route('publications.show', $publication->code_publi)
            ->with('success', 'Publication mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $publication = Publication::findOrFail($id);
        $publication->delete();

        return redirect()->route('publications.index')
            ->with('success', 'Publication supprimée avec succès.');
    }
}
