<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\PersLab;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\RoleLabo;
use App\Models\laboratoires\PlRole;
use Illuminate\Http\Request;

class PersLabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PersLab::with(['laboratoire', 'roles.roleLabo']);

        if ($request->has('laboratoire')) {
            $query->where('code_lab', $request->laboratoire);
        }

        if ($request->has('type')) {
            $query->where('type_pers_lab', $request->type);
        }

        $membres = $query->paginate(10);
        $laboratoires = Laboratoire::all();

        return view('sige_app.frontend.labo.membres.index', compact('membres', 'laboratoires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laboratoires = Laboratoire::all();
        $roles = RoleLabo::all();

        return view('sige_app.frontend.labo.membres.create', compact('laboratoires', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pers_lab' => 'required|unique:pers_lab,id_pers_lab',
            'type_pers_lab' => 'required|in:enseignant,etudiant,personnel',
            'code_lab' => 'required|exists:laboratoire,code_lab',
            'date_entree' => 'required|date',
            'date_sortie' => 'nullable|date|after:date_entree',
            'statut' => 'required|in:actif,inactif',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:role_labo,id_rl'
        ]);

        $membre = PersLab::create($validated);

        // Attribuer les rôles
        foreach ($request->roles as $role_id) {
            PlRole::create([
                'id_pers_lab' => $membre->id_pers_lab,
                'id_rl' => $role_id,
                'date_debut' => $membre->date_entree
            ]);
        }

        return redirect()->route('membres.show', $membre->id_pers_lab)
            ->with('success', 'Membre ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $membre = PersLab::with(['laboratoire', 'roles.roleLabo'])
            ->where('id_pers_lab', $id)
            ->firstOrFail();

        $participations = $membre->participationsProjet()
            ->with(['projet', 'userExterne'])
            ->get();

        return view('sige_app.frontend.labo.membres.show', compact('membre', 'participations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $membre = PersLab::with('roles')->where('id_pers_lab', $id)->firstOrFail();
        $laboratoires = Laboratoire::all();
        $roles = RoleLabo::all();

        return view('sige_app.frontend.labo.membres.edit', compact('membre', 'laboratoires', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $membre = PersLab::where('id_pers_lab', $id)->firstOrFail();

        $validated = $request->validate([
            'type_pers_lab' => 'required|in:enseignant,etudiant,personnel',
            'code_lab' => 'required|exists:laboratoire,code_lab',
            'date_entree' => 'required|date',
            'date_sortie' => 'nullable|date|after:date_entree',
            'statut' => 'required|in:actif,inactif'
        ]);

        $membre->update($validated);

        // Gérer les rôles si modifiés
        if ($request->has('roles')) {
            // Terminer les rôles actuels
            PlRole::where('id_pers_lab', $membre->id_pers_lab)
                ->whereNull('date_fin')
                ->update(['date_fin' => now()]);

            // Ajouter les nouveaux rôles
            foreach ($request->roles as $role_id) {
                PlRole::create([
                    'id_pers_lab' => $membre->id_pers_lab,
                    'id_rl' => $role_id,
                    'date_debut' => now()
                ]);
            }
        }

        return redirect()->route('membres.show', $membre->id_pers_lab)
            ->with('success', 'Membre mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $membre = PersLab::where('id_pers_lab', $id)->firstOrFail();
        $membre->delete();

        return redirect()->route('membres.index')
            ->with('success', 'Membre supprimé avec succès.');
    }
}
