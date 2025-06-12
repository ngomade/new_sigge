<?php

namespace App\Http\Controllers\requetes;

use App\Models\notes\Bureau;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class BureauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $bureaux = Bureau::with(['documents', 'presentations', 'sousBureau', 'bureauParents'])
            ->paginate(15);

        return view('bureaux.index', compact('bureaux'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $allBureaux = Bureau::all(); // Pour la sélection des sous-bureaux
        $types = Bureau::distinct()->pluck('type_bureau')->filter(); // Types existants

        return view('bureaux.create', compact('allBureaux', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code_bureau' => 'required|string|max:255|unique:bureau,code_bureau',
            'label_bureau' => 'required|string|max:255',
            'desc_bureau' => 'nullable|string',
            'type_bureau' => 'required|string|max:100',
            'sous_bureaux' => 'nullable|array',
            'sous_bureaux.*' => 'exists:bureau,code_bureau'
        ]);

        try {
            $bureau = Bureau::create([
                'code_bureau' => $validated['code_bureau'],
                'label_bureau' => $validated['label_bureau'],
                'desc_bureau' => $validated['desc_bureau'] ?? null,
                'type_bureau' => $validated['type_bureau']
            ]);

            // Attacher les sous-bureaux si fournis
            if (isset($validated['sous_bureaux'])) {
                $bureau->sousBureau()->attach($validated['sous_bureaux']);
            }

            return redirect()->route('bureaux.index')
                ->with('success', 'Bureau créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du bureau: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_bureau): View
    {
        $bureau = Bureau::with(['documents', 'presentations', 'sousBureau', 'bureauParents'])
            ->where('code_bureau', $code_bureau)
            ->firstOrFail();

        return view('bureaux.show', compact('bureau'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_bureau): View
    {
        $bureau = Bureau::with(['sousBureau'])
            ->where('code_bureau', $code_bureau)
            ->firstOrFail();

        $allBureaux = Bureau::where('code_bureau', '!=', $code_bureau)->get();
        $types = Bureau::distinct()->pluck('type_bureau')->filter();
        $selectedSousBureaux = $bureau->sousBureau->pluck('code_bureau')->toArray();

        return view('bureaux.edit', compact('bureau', 'allBureaux', 'types', 'selectedSousBureaux'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_bureau): RedirectResponse
    {
        $bureau = Bureau::where('code_bureau', $code_bureau)->firstOrFail();

        $validated = $request->validate([
            'label_bureau' => 'required|string|max:255',
            'desc_bureau' => 'nullable|string',
            'type_bureau' => 'required|string|max:100',
            'sous_bureaux' => 'nullable|array',
            'sous_bureaux.*' => 'exists:bureau,code_bureau'
        ]);

        try {
            $bureau->update($validated);

            // Synchroniser les sous-bureaux
            if (isset($validated['sous_bureaux'])) {
                $bureau->sousBureau()->sync($validated['sous_bureaux']);
            } else {
                $bureau->sousBureau()->detach();
            }

            return redirect()->route('bureaux.index')
                ->with('success', 'Bureau mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du bureau: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_bureau): RedirectResponse
    {
        try {
            $bureau = Bureau::where('code_bureau', $code_bureau)->firstOrFail();

            // Détacher les relations many-to-many
            $bureau->sousBureau()->detach();
            $bureau->bureauParents()->detach();

            $bureau->delete();

            return redirect()->route('bureaux.index')
                ->with('success', 'Bureau supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du bureau: ' . $e->getMessage());
        }
    }

    /**
     * Recherche de bureaux
     */
    public function search(Request $request): View
    {
        $query = $request->get('q');
        $type = $request->get('type');

        $bureaux = Bureau::query();

        if ($query) {
            $bureaux->where(function($q) use ($query) {
                $q->where('label_bureau', 'LIKE', "%{$query}%")
                  ->orWhere('desc_bureau', 'LIKE', "%{$query}%")
                  ->orWhere('code_bureau', 'LIKE', "%{$query}%");
            });
        }

        if ($type) {
            $bureaux->where('type_bureau', $type);
        }

        $bureaux = $bureaux->with(['documents', 'presentations', 'sousBureau', 'bureauParents'])
            ->paginate(15)
            ->appends($request->query());

        $types = Bureau::distinct()->pluck('type_bureau')->filter();

        return view('bureaux.index', compact('bureaux', 'query', 'type', 'types'));
    }

    /**
     * Afficher les sous-bureaux d'un bureau
     */
    public function sousBureaux($code_bureau): View
    {
        $bureau = Bureau::with(['sousBureau'])
            ->where('code_bureau', $code_bureau)
            ->firstOrFail();

        return view('bureaux.sous-bureaux', compact('bureau'));
    }

    /**
     * Afficher les bureaux parents d'un bureau
     */
    public function bureauParents($code_bureau): View
    {
        $bureau = Bureau::with(['bureauParents'])
            ->where('code_bureau', $code_bureau)
            ->firstOrFail();

        return view('bureaux.bureau-parents', compact('bureau'));
    }

    /**
     * Afficher les documents d'un bureau
     */
    public function documents($code_bureau): View
    {
        $bureau = Bureau::with(['documents'])
            ->where('code_bureau', $code_bureau)
            ->firstOrFail();

        return view('bureaux.documents', compact('bureau'));
    }

    /**
     * Afficher les présentations d'un bureau
     */
    public function presentations($code_bureau): View
    {
        $bureau = Bureau::with(['presentations'])
            ->where('code_bureau', $code_bureau)
            ->firstOrFail();

        return view('bureaux.presentations', compact('bureau'));
    }
}
