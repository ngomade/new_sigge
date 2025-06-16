<?php

namespace App\Http\Controllers\requetes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Role;
use App\Models\Bureau;

class AffectationPersonnelController extends Controller
{
    // Afficher la liste des affectations avec filtres
    public function index(Request $request)
    {
        $query = Personnel::with([
            'roles' => function($query) {
                $query->withPivot('date_debut', 'date_fin', 'statut_role', 'code_bureau', 'created_at', 'updated_at');
            },
            'roles.bureau'
        ]);

        if ($request->filled('code_bureau')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('pers_role.code_bureau', $request->code_bureau);
            });
        }

        if ($request->filled('statut_role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('pers_role.statut_role', $request->statut_role);
            });
        }

        if ($request->filled('id')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('pers_role.id_role', $request->id_role);
            });
        }

        $affectations = $query->paginate(15);

        return view('affectations.index', compact('affectations'));
    }

    // Afficher les affectations d'un personnel spécifique
    public function show($code_pers)
    {
        $personnel = Personnel::with([
            'roles' => function($query) {
                $query->withPivot('date_debut', 'date_fin', 'statut_role', 'code_bureau', 'created_at', 'updated_at')
                      ->orderBy('pers_role.date_debut', 'desc');
            },
            'roles.bureau'
        ])->find($code_pers);

        if (!$personnel) {
            return redirect()->route('affectations.index')->withErrors('Personnel non trouvé');
        }

        return view('affectations.show', [
            'personnel' => $personnel,
            'affectations_count' => $personnel->roles->count(),
            'affectations_actives' => $personnel->roles->where('pivot.statut_role', 'actif')->count()
        ]);
    }

    // Afficher le formulaire de création d'une affectation
    public function create()
    {
        $personnels = Personnel::all();
        $roles = Role::all();
        $bureaux = Bureau::all();

        return view('affectations.create', compact('personnels', 'roles', 'bureaux'));
    }

    // Enregistrer une nouvelle affectation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_pers' => 'required|exists:personnel,code_pers',
            'id_role' => 'required|exists:roles,id_role',
            'code_bureau' => 'required|exists:bureau,code_bureau',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut_role' => 'required|in:actif,inactif,suspendu'
        ]);

        try {
            $personnel = Personnel::find($validated['code_pers']);

            $existingAffectation = $personnel->roles()
                ->wherePivot('id', $validated['id'])
                ->wherePivot('code_bureau', $validated['code_bureau'])
                ->wherePivot('statut_role', 'actif')
                ->first();

            if ($existingAffectation) {
                return redirect()->back()->withInput()->withErrors('Cette affectation existe déjà');
            }

            $personnel->roles()->attach($validated['id'], [
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
                'statut_role' => $validated['statut_role'],
                'code_bureau' => $validated['code_bureau'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('affectations.index')->with('success', 'Affectation créée avec succès');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors('Erreur lors de la création de l\'affectation : ' . $e->getMessage());
        }
    }

    // Afficher le formulaire d'édition d'une affectation
    public function edit($code_pers, $id_role)
    {
        $personnel = Personnel::with([
            'roles' => function($query) use ($id_role) {
                $query->where('roles.id', $id_role)
                      ->withPivot('date_debut', 'date_fin', 'statut_role', 'code_bureau', 'created_at', 'updated_at');
            },
            'roles.bureau'
        ])->find($code_pers);

        if (!$personnel || $personnel->roles->isEmpty()) {
            return redirect()->route('affectations.index')->withErrors('Affectation non trouvée');
        }

        $roles = Role::all();
        $bureaux = Bureau::all();

        return view('affectations.edit', compact('personnel', 'roles', 'bureaux'));
    }

    // Mettre à jour une affectation
    public function update(Request $request, $code_pers, $id_role)
    {
        $validated = $request->validate([
            'date_debut' => 'sometimes|date',
            'date_fin' => 'nullable|date',
            'statut_role' => 'sometimes|in:actif,inactif,suspendu,termine',
            'code_bureau' => 'sometimes|exists:bureau,code_bureau'
        ]);

        try {
            $personnel = Personnel::find($code_pers);
            if (!$personnel) {
                return redirect()->route('affectations.index')->withErrors('Personnel non trouvé');
            }

            $affectationExists = $personnel->roles()
                ->wherePivot('id_role', $id_role)
                ->exists();

            if (!$affectationExists) {
                return redirect()->route('affectations.index')->withErrors('Affectation non trouvée');
            }

            $updateData = array_merge($validated, ['updated_at' => now()]);
            $personnel->roles()->updateExistingPivot($id_role, $updateData);

            return redirect()->route('affectations.show', $code_pers)->with('success', 'Affectation mise à jour avec succès');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    // Supprimer une affectation
    public function destroy($code_pers, $id_role)
    {
        try {
            $personnel = Personnel::find($code_pers);
            if (!$personnel) {
                return redirect()->route('affectations.index')->withErrors('Personnel non trouvé');
            }

            $affectationDetails = $this->getAffectationDetails($code_pers, $id_role);

            if (!$affectationDetails) {
                return redirect()->route('affectations.index')->withErrors('Affectation non trouvée');
            }

            $personnel->roles()->detach($id_role);

            return redirect()->route('affectations.index')->with('success', 'Affectation supprimée avec succès');

        } catch (\Exception $e) {
            return redirect()->route('affectations.index')->withErrors('Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    // Obtenir les détails d'une affectation
    private function getAffectationDetails($code_pers, $id_role)
    {
        return Personnel::with([
            'roles' => function($query) use ($id_role) {
                $query->where('roles.id_role', $id_role)
                      ->withPivot('date_debut', 'date_fin', 'statut_role', 'code_bureau', 'created_at', 'updated_at');
            },
            'roles.bureau'
        ])->find($code_pers);
    }
}
