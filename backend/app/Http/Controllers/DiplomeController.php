<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Diplome;
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
            'filiere_codes' => 'array',
            'filiere_codes.*' => 'exists:filiere,filiere_code',
        ]);
        $diplome = Diplome::create([
            'label_dip' => $validated['label_dip'],
        ]);
        if (isset($validated['filiere_codes'])) {
            $diplome->filieres()->sync($validated['filiere_codes']);
        }
        return response()->json($diplome->load('filieres'));
    }

    public function show(string $id)
    {
        $diplome = Diplome::with('filieres')->findOrFail($id);
        return response()->json($diplome);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'label_dip' => 'sometimes|required|string|max:255',
            'filiere_codes' => 'array',
            'filiere_codes.*' => 'exists:filiere,filiere_code',
        ]);
        $diplome = Diplome::findOrFail($id);
        if (isset($validated['label_dip'])) {
            $diplome->update(['label_dip' => $validated['label_dip']]);
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
