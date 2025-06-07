<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\Serie;
use App\Models\Diplome;
use App\Models\Filiere;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index()
    {
        return Serie::with(['diplomes', 'filieres'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label_serie' => 'required|string|max:255',
        ]);
        $serie = Serie::create($validated);
        return response()->json($serie);
    }

    public function show(string $id)
    {

        $serie = Serie::with(['diplomes', 'filieres'])->findOrFail($id);
        return response()->json($serie);
    }
    public function showByIdDiplomeAndFiliere(string $filiere_id, string $diplome_id)
    {
        // On récupère toutes les séries associées à la combinaison filière/diplôme via la table pivot
        $series = Serie::whereHas('diplomes', function($q) use ($filiere_id, $diplome_id) {
            $q->where('filiere_diplome.filiere_code', $filiere_id)
                ->where('filiere_diplome.code_dip', $diplome_id);
        })->get();

        return response()->json($series);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'label_serie' => 'sometimes|required|string|max:255',
        ]);
        $serie = Serie::findOrFail($id);
        $serie->update($validated);
        return response()->json($serie);
    }

    public function destroy(string $id)
    {
        $serie = Serie::findOrFail($id);
        $serie->diplomes()->detach();
        $serie->filieres()->detach();
        $serie->delete();
        return response()->noContent();
    }
}

