<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\PersLab;
use App\Models\laboratoires\RoleLabo;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\Personnel;
use App\Models\Users;
use App\Models\laboratoires\UserExterne;
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
        try {
                    $validated = $request->validate([
            'code_lab' => 'required|unique:laboratoire,code_lab|max:50',
            'label_labo' => 'required|max:255',
            'desc_labo' => 'required',
            'axes_recherche' => 'nullable',
            'email_labo' => 'required|email',
            'tel_labo' => 'required',
            'adresse_labo' => 'required',
            'logo_labo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type_pers_lab' => 'required|in:personnel,users,user_externe',
            'id_pers_lab' => 'required'
        ]);

        // Gestion du logo
        if ($request->hasFile('logo_labo')) {
            $validated['logo_labo'] = $request->file('logo_labo')->store('logos', 'public');
        }

        // 1. Récupérer les infos de la personne
        $typePers = $request->input('type_pers_lab');
        $idPers = $request->input('id_pers_lab');

        // 2. Créer ou récupérer dans pers_lab
        $persLab = PersLab::firstOrCreate(
            [
                'id_pers_lab' => $idPers,
                'type_pers_lab' => $typePers
            ],
            [
                'date_entree' => now(),
                'statut' => 'actif'
            ]
        );

        // 3. Créer le laboratoire en liant l'admin
        $validated['admin_pers_labo'] = $persLab->id_pers_lab;
        $laboratoire = Laboratoire::create($validated);

        // 4. Créer l'affectation dans la table de liaison
        LaboratoirePersLab::create([
            'code_lab' => $laboratoire->code_lab,
            'id_pers_lab' => $persLab->id_pers_lab,
            'date_affectation' => now(),
            'statut' => 'actif'
        ]);

        // 5. Attribuer le rôle admin dans la table de liaison
        $roleAdmin = RoleLabo::where('lib_rl', 'admin')->first();
        if (!$roleAdmin) {
            // Si le rôle admin n'existe pas, on le crée
            $roleAdmin = RoleLabo::create([
                'lib_rl' => 'admin'
            ]);
        }

        // Mettre à jour l'affectation avec le rôle
        LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
            ->where('id_pers_lab', $persLab->id_pers_lab)
            ->update(['id_rl' => $roleAdmin->id_rl]);

        return redirect()->route('labo.laboratoires.index')
            ->with('success', 'Laboratoire créé avec succès et administrateur assigné.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du laboratoire : ' . $e->getMessage());
        }
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

        return redirect()->route('labo.laboratoires.show', $laboratoire->code_lab)
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

        return redirect()->route('labo.laboratoires.index')
            ->with('success', 'Laboratoire supprimé avec succès.');
    }
}
