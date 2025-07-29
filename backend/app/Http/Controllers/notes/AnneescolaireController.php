<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\Anneescolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;


use Illuminate\Support\Facades\Validator;

class AnneescolaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annees = Anneescolaire::orderBy('code_annee', 'desc')->paginate(10);
        return view('annees.index', compact('annees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('annees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_annee' => 'required|string|max:128',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut_annee' => 'required|integer|in:0,1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            AnneeScolaire::create([
                'label_annee' => $request->label_annee,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'statut_annee' => $request->statut_annee
            ]);

            return redirect()->route('annees.index')
                ->with('success', 'Année scolaire créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de l\'année scolaire.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_annee)
    {
        $annee = Anneescolaire::findOrFail($code_annee);
        return view('annees.show', compact('annee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_annee)
    {
        $annee = AnneeScolaire::findOrFail($code_annee);
        return view('annees.edit', compact('annee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_annee)
    {
        $validator = Validator::make($request->all(), [
            'label_annee' => 'required|string|max:128',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut_annee' => 'required|integer|in:0,1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $annee = Anneescolaire::findOrFail($code_annee);
            $annee->update([
                'label_annee' => $request->label_annee,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'statut_annee' => $request->statut_annee
            ]);

            return redirect()->route('annees.index')
                ->with('success', 'Année scolaire modifiée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la modification de l\'année scolaire.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_annee)
    {
        try {
            $annee = Anneescolaire::findOrFail($code_annee);
            $annee->delete();

            return redirect()->route('annees.index')
                ->with('success', 'Année scolaire supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'année scolaire.');
        }
    }
}