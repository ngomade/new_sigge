<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\UserExterne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserExterneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UserExterne::with('laboratoire');

        if ($request->has('laboratoire')) {
            $query->where('code_lab', $request->laboratoire);
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        $externes = $query->paginate(10);
        $laboratoires = Laboratoire::all();

        return view('laboratoires.admin.externes.index', compact('externes', 'laboratoires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laboratoires = Laboratoire::all();

        return view('laboratoires.admin.externes.create', compact('laboratoires'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_lab' => 'required|exists:laboratoire,code_lab',
            'nom_user_ext' => 'required|max:100',
            'prenom_user_ext' => 'required|max:100',
            'email_user_ext' => 'required|email|unique:user_externe,email_user_ext',
            'tel_user_ext' => 'required|max:20',
            'statut' => 'required|in:actif,inactif',
            'pwd' => 'required|min:6',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
        ]);

        $validated['pwd'] = Hash::make($validated['pwd']);

        if ($request->hasFile('logo_url')) {
            $validated['logo_url'] = $request->file('logo_url')->store('logos_externes', 'public');
        }

        UserExterne::create($validated);

        return redirect()->route('externes.index')
            ->with('success', 'Utilisateur externe créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $externe = UserExterne::with(['laboratoire', 'participationsProjet.projet'])
            ->findOrFail($id);

        return view('sige_app.frontend.labo.externes.show', compact('externe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $externe = UserExterne::findOrFail($id);
        $laboratoires = Laboratoire::all();

        return view('sige_app.frontend.labo.externes.edit', compact('externe', 'laboratoires'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $externe = UserExterne::findOrFail($id);

        $validated = $request->validate([
            'code_lab' => 'required|exists:laboratoire,code_lab',
            'nom_user_ext' => 'required|max:100',
            'prenom_user_ext' => 'required|max:100',
            'email_user_ext' => 'required|email|unique:user_externe,email_user_ext,'.$id.',id_user_ext',
            'tel_user_ext' => 'required|max:20',
            'statut' => 'required|in:actif,inactif',
            'pwd' => 'nullable|min:6',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
        ]);

        if ($request->filled('pwd')) {
            $validated['pwd'] = Hash::make($validated['pwd']);
        } else {
            unset($validated['pwd']);
        }

        if ($request->hasFile('logo_url')) {
            if ($externe->logo_url) {
                Storage::disk('public')->delete($externe->logo_url);
            }
            $validated['logo_url'] = $request->file('logo_url')->store('logos_externes', 'public');
        }

        $externe->update($validated);

        return redirect()->route('externes.show', $externe->id_user_ext)
            ->with('success', 'Utilisateur externe mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $externe = UserExterne::findOrFail($id);

        if ($externe->logo_url) {
            Storage::disk('public')->delete($externe->logo_url);
        }

        $externe->delete();

        return redirect()->route('externes.index')
            ->with('success', 'Utilisateur externe supprimé avec succès.');
    }
}
