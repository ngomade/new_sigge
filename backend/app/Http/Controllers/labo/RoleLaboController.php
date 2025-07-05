<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\RoleLabo;
use App\Models\laboratoires\LaboratoirePersLab;
use Illuminate\Http\Request;

class RoleLaboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = RoleLabo::with('affectations')->paginate(10);
        return view('sige_app.frontend.labo.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sige_app.frontend.labo.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lib_rl' => 'required|unique:role_labo,lib_rl|max:100'
        ]);

        RoleLabo::create($validated);

        return redirect()->route('labo.roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = RoleLabo::with('affectations.persLab', 'affectations.laboratoire')->findOrFail($id);
        return view('sige_app.frontend.labo.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = RoleLabo::findOrFail($id);
        return view('sige_app.frontend.labo.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = RoleLabo::findOrFail($id);

        $validated = $request->validate([
            'lib_rl' => 'required|max:100|unique:role_labo,lib_rl,' . $id . ',id_rl'
        ]);

        $role->update($validated);

        return redirect()->route('labo.roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = RoleLabo::findOrFail($id);

        // Vérifier si le rôle est utilisé
        if ($role->affectations()->count() > 0) {
            return redirect()->route('labo.roles.index')
                ->with('error', 'Ce rôle ne peut pas être supprimé car il est attribué à des personnes.');
        }

        $role->delete();

        return redirect()->route('labo.roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
}
