<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Diplome;
use App\Models\Filiere;
use Illuminate\Http\Request;

class DiplomeController extends Controller
{
    public function index()
    {
        return Diplome::with('filieres')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label_dip' => 'required|string|max:255',
            'specialite_dip' => "required|string|max:255",
            'filiere_codes' => 'array',
            'filiere_codes.*' => 'exists:filiere,code_filiere',
        ]);
        $diplome = Diplome::create([
            'label_dip' => $validated['label_dip'],
            'specialite_dip' => $validated['specialite_dip'],
        ]);
        if (isset($validated['filiere_codes'])) {
            $diplome->filieres()->sync($validated['filiere_codes']);
        }
        return response()->json($diplome->load('filieres'));
    }

    public function show(string $id)
    {
        $diplome = Diplome::findOrFail($id);
        return response()->json($diplome);
    }

    public function showByFiliere(string $id)
    {
        // $id est l'identifiant de la filière, on retourne les diplômes associés à cette filière sans doublons
        $filiere = Filiere::with('diplomes')->findOrFail($id);
        $diplomes = $filiere->diplomes->unique('code_dip')->values();
        return response()->json($diplomes);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'label_dip' => 'sometimes|required|string|max:255',
            'specialite_dip' => 'sometimes|required|string|max:255',
            'filiere_codes' => 'array',
            'filiere_codes.*' => 'exists:filiere,code_filiere',
        ]);
        $diplome = Diplome::findOrFail($id);
        if (isset($validated['label_dip'])) {
            $diplome->update(['label_dip' => $validated['label_dip'], 'specialide_dip' => $validated['specialite_dip']]);
        }
        if (isset($validated['filiere_codes'])) {
            $diplome->filieres()->sync($validated['filiere_codes']);
        }
        return response()->json($diplome->load('filieres'));
    }

    public function destroy(string $id)
    {
        $diplome = Diplome::findOrFail($id);
        $diplome->filieres()->detach(); // Detach all filieres before deleting
        $diplome->delete();
        return response()->noContent();
    }
}
