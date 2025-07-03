<?php

namespace App\Http\Controllers\Labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Laboratoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaboratoireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laboratoires = Laboratoire::with(['projets', 'membres'])->paginate(10);
        return view('sige_app.frontend.labo.laboratoires.index', compact('laboratoires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sige_app.frontend.labo.laboratoires.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_lab' => 'required|unique:laboratoire,code_lab|max:50',
            'label_labo' => 'required|max:255',
            'sigle' => 'required|max:20',
            'desc_labo' => 'required',
            'axes_recherche' => 'nullable',
            'email_labo' => 'required|email',
            'tel_labo' => 'required',
            'adresse_labo' => 'required',
            'logo_labo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('logo_labo')) {
            $validated['logo_labo'] = $request->file('logo_labo')->store('logos', 'public');
        }

        Laboratoire::create($validated);

        return redirect()->route('laboratoires.index')
            ->with('success', 'Laboratoire créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_lab)
    {
        $laboratoire = Laboratoire::with(['projets', 'membres', 'equipements', 'pages'])
            ->where('code_lab', $code_lab)
            ->firstOrFail();

        return view('sige_app.frontend.labo.laboratoires.show', compact('laboratoire'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        return view('sige_app.frontend.labo.laboratoires.edit', compact('laboratoire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        $validated = $request->validate([
            'label_labo' => 'required|max:255',
            'sigle' => 'required|max:20',
            'desc_labo' => 'required',
            'axes_recherche' => 'nullable',
            'email_labo' => 'required|email',
            'tel_labo' => 'required',
            'adresse_labo' => 'required',
            'logo_labo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('logo_labo')) {
            if ($laboratoire->logo_labo) {
                Storage::disk('public')->delete($laboratoire->logo_labo);
            }
            $validated['logo_labo'] = $request->file('logo_labo')->store('logos', 'public');
        }

        $laboratoire->update($validated);

        return redirect()->route('laboratoires.show', $laboratoire->code_lab)
            ->with('success', 'Laboratoire mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        if ($laboratoire->logo_labo) {
            Storage::disk('public')->delete($laboratoire->logo_labo);
        }

        $laboratoire->delete();

        return redirect()->route('laboratoires.index')
            ->with('success', 'Laboratoire supprimé avec succès.');
    }
}
