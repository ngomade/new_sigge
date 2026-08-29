<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\Anneescolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnneescolaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annees = Anneescolaire::orderBy('code_annee', 'desc')->paginate(10);

        return view('sige_app.backend.annee.annee_index', compact('annees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sige_app.backend.annee.annee_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_annee' => 'required|integer|unique:anneescolaire',
            'debut_annee' => 'required|date',
            'fin_annee' => 'required|date|after:debut_annee',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Anneescolaire::create([
                'code_annee' => $request->code_annee,
                'debut_annee' => $request->debut_annee,
                'fin_annee' => $request->fin_annee,
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

        return view('sige_app.backend.annee.annee_show', compact('annee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_annee)
    {
        $annee = Anneescolaire::findOrFail($code_annee);

        return view('sige_app.backend.annee.annee_edit', compact('annee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_annee)
    {
        $validator = Validator::make($request->all(), [
            'debut_annee' => 'required|date',
            'fin_annee' => 'required|date|after:debut_annee',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $annee = Anneescolaire::findOrFail($code_annee);
            $annee->update([
                'debut_annee' => $request->debut_annee,
                'fin_annee' => $request->fin_annee,
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
