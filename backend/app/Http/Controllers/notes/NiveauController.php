<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\notes\Classe;
use App\Models\notes\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NiveauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $niveaux = Niveau::with('class')->paginate(10);

        return view('sige_app.backend.niveau.niveau_index', compact('niveaux'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = Classe::all();

        return view('sige_app.backend.niveau.niveau_create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_niveau' => 'required|string|max:128',
            'code_class' => 'nullable|exists:classes,code_class',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Niveau::create([
                'code_niveau' => Str::uuid(),
                'label_niveau' => $request->label_niveau,
                'code_class' => $request->code_class,
            ]);

            return redirect()->route('niveaux.index')
                ->with('success', 'Niveau créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du niveau.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_niveau)
    {
        $niveau = Niveau::with(['class', 'semestres'])->findOrFail($code_niveau);

        return view('sige_app.backend.niveau.niveau_show', compact('niveau'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_niveau)
    {
        $niveau = Niveau::findOrFail($code_niveau);
        $classes = Classe::all();

        return view('sige_app.backend.niveau.niveau_edit', compact('niveau', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_niveau)
    {
        $validator = Validator::make($request->all(), [
            'label_niveau' => 'required|string|max:128',
            'code_class' => 'nullable|exists:classes,code_class',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $niveau = Niveau::findOrFail($code_niveau);
            $niveau->update([
                'label_niveau' => $request->label_niveau,
                'code_class' => $request->code_class,
            ]);

            return redirect()->route('niveaux.index')
                ->with('success', 'Niveau modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la modification du niveau.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_niveau)
    {
        try {
            $niveau = Niveau::findOrFail($code_niveau);
            $niveau->delete();

            return redirect()->route('niveaux.index')
                ->with('success', 'Niveau supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du niveau.');
        }
    }
}
