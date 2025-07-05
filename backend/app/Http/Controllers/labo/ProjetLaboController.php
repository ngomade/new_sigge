<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\ProjetLabo;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\PersLab;
use App\Models\laboratoires\UserExterne;
use App\Models\laboratoires\ParticiperProjet;
use Illuminate\Http\Request;

class ProjetLaboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProjetLabo::with(['laboratoire', 'participants']);

        if ($request->has('laboratoire')) {
            $query->where('code_lab', $request->laboratoire);
        }

        $projets = $query->paginate(10);
        $laboratoires = Laboratoire::all();

        return view('sige_app.frontend.labo.projets.index', compact('projets', 'laboratoires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laboratoires = Laboratoire::all();
        $membres = PersLab::with('laboratoire')->where('statut', 'actif')->get();
        $externes = UserExterne::where('statut', 'actif')->get();

        return view('sige_app.frontend.labo.projets.create', compact('laboratoires', 'membres', 'externes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'theme_projet' => 'required|max:255',
            'description_projet' => 'required',
            'code_lab' => 'required|exists:laboratoire,code_lab',
            'statut_projet' => 'required|in:en_cours,termine,suspendu',
            'debut_projet' => 'required|date',
            'fin_projet' => 'nullable|date|after:debut_projet',
            'participants_internes' => 'nullable|array',
            'participants_externes' => 'nullable|array',
            'roles' => 'nullable|array'
        ]);

        $projet = ProjetLabo::create($validated);

        // Ajouter les participants internes
        if ($request->has('participants_internes')) {
            foreach ($request->participants_internes as $id_pers_lab) {
                ParticiperProjet::create([
                    'code_projet' => $projet->code_projet,
                    'id_pers_lab' => $id_pers_lab,
                    'role' => $request->roles[$id_pers_lab] ?? 'Participant',
                    'debut_participation' => $projet->debut_projet
                ]);
            }
        }

        // Ajouter les participants externes
        if ($request->has('participants_externes')) {
            foreach ($request->participants_externes as $id_user_ext) {
                ParticiperProjet::create([
                    'code_projet' => $projet->code_projet,
                    'id_user_ext' => $id_user_ext,
                    'role' => $request->roles['ext_' . $id_user_ext] ?? 'Participant externe',
                    'debut_participation' => $projet->debut_projet
                ]);
            }
        }

        return redirect()->route('projets.show', $projet->code_projet)
            ->with('success', 'Projet créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $projet = ProjetLabo::with(['laboratoire', 'participants.membre', 'participants.userExterne', 'docs'])
            ->findOrFail($id);

        return view('sige_app.frontend.labo.projets.show', compact('projet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $projet = ProjetLabo::with('participants')->findOrFail($id);
        $laboratoires = Laboratoire::all();
        $membres = PersLab::with('laboratoire')->where('statut', 'actif')->get();
        $externes = UserExterne::where('statut', 'actif')->get();

        return view('sige_app.frontend.labo.projets.edit', compact('projet', 'laboratoires', 'membres', 'externes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $projet = ProjetLabo::findOrFail($id);

        $validated = $request->validate([
            'theme_projet' => 'required|max:255',
            'description_projet' => 'required',
            'code_lab' => 'required|exists:laboratoire,code_lab',
            'statut_projet' => 'required|in:en_cours,termine,suspendu',
            'debut_projet' => 'required|date',
            'fin_projet' => 'nullable|date|after:debut_projet'
        ]);

        $projet->update($validated);

        return redirect()->route('projets.show', $projet->code_projet)
            ->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $projet = ProjetLabo::findOrFail($id);
        $projet->delete();

        return redirect()->route('projets.index')
            ->with('success', 'Projet supprimé avec succès.');
    }
}
