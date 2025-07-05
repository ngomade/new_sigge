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
        $affectationData = [
            'code_lab' => $laboratoire->code_lab,
            'id_pers_lab' => $persLab->id_pers_lab,
            'date_affectation' => now(),
            'statut' => 'actif'
        ];

        // Si c'est un utilisateur externe, ajouter l'id_user_externe
        if ($typePers === 'user_externe') {
            $affectationData['id_user_externe'] = $idPers;
        }

        LaboratoirePersLab::create($affectationData);

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

    /**
     * Afficher la liste des membres du laboratoire
     */
    public function membres(Laboratoire $laboratoire)
    {
        $membres = $laboratoire->affectations()
            ->with(['persLab', 'roleLabo', 'userExterne'])
            ->get();

        return view('sige_app.frontend.labo.laboratoires.membres.index', compact('laboratoire', 'membres'));
    }

    /**
     * Afficher le formulaire d'ajout d'un membre
     */
    public function ajouterMembreForm(Laboratoire $laboratoire)
    {
        $roles = RoleLabo::all();
        $personnel = Personnel::all();
        $users = Users::limit(100)->get(); // Limiter à 100 étudiants pour éviter les problèmes de performance
        $userExternes = UserExterne::where('code_lab', $laboratoire->code_lab)->get();

        return view('sige_app.frontend.labo.laboratoires.membres.create', compact('laboratoire', 'roles', 'personnel', 'users', 'userExternes'));
    }

    /**
     * Ajouter un nouveau membre au laboratoire
     */
    public function ajouterMembre(Request $request, Laboratoire $laboratoire)
    {
        $request->validate([
            'type_pers_lab' => 'required|in:personnel,users,user_externe',
            'id_pers_lab' => 'required',
            'id_rl' => 'required|exists:role_labo,id_rl',
            'date_affectation' => 'required|date',
            'date_fin_affectation' => 'nullable|date|after:date_affectation',
            'statut' => 'required|in:actif,inactif'
        ]);

        try {
            // Vérifier si le membre existe déjà dans ce laboratoire
            $existingMember = LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
                ->where('id_pers_lab', $request->id_pers_lab)
                ->first();

            if ($existingMember) {
                return back()->withErrors(['error' => 'Cette personne est déjà membre de ce laboratoire.']);
            }

            // Créer ou récupérer dans pers_lab
            $persLab = PersLab::firstOrCreate(
                [
                    'id_pers_lab' => $request->id_pers_lab,
                    'type_pers_lab' => $request->type_pers_lab
                ],
                [
                    'date_entree' => now(),
                    'statut' => 'actif'
                ]
            );

            // Créer l'affectation
            $affectationData = [
                'code_lab' => $laboratoire->code_lab,
                'id_pers_lab' => $persLab->id_pers_lab,
                'id_rl' => $request->id_rl,
                'date_affectation' => $request->date_affectation,
                'date_fin_affectation' => $request->date_fin_affectation,
                'statut' => $request->statut
            ];

            // Si c'est un utilisateur externe, ajouter l'id_user_externe
            if ($request->type_pers_lab === 'user_externe') {
                $affectationData['id_user_externe'] = $request->id_pers_lab;
            }

            LaboratoirePersLab::create($affectationData);

            return redirect()->route('labo.laboratoires.membres.index', $laboratoire)
                ->with('success', 'Membre ajouté avec succès au laboratoire.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'ajout du membre : ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher le formulaire de modification d'un membre
     */
    public function modifierMembreForm(Laboratoire $laboratoire, $membreId)
    {
        $membre = LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
            ->where('id_pers_lab', $membreId)
            ->with(['persLab', 'roleLabo', 'userExterne'])
            ->firstOrFail();

        $roles = RoleLabo::all();

        return view('sige_app.frontend.labo.laboratoires.membres.edit', compact('laboratoire', 'membre', 'roles'));
    }

    /**
     * Modifier un membre du laboratoire
     */
    public function modifierMembre(Request $request, Laboratoire $laboratoire, $membreId)
    {
        $request->validate([
            'id_rl' => 'required|exists:role_labo,id_rl',
            'date_affectation' => 'required|date',
            'date_fin_affectation' => 'nullable|date|after:date_affectation',
            'statut' => 'required|in:actif,inactif'
        ]);

        try {
            $membre = LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
                ->where('id_pers_lab', $membreId)
                ->firstOrFail();

            $membre->update([
                'id_rl' => $request->id_rl,
                'date_affectation' => $request->date_affectation,
                'date_fin_affectation' => $request->date_fin_affectation,
                'statut' => $request->statut
            ]);

            return redirect()->route('labo.laboratoires.membres.index', $laboratoire)
                ->with('success', 'Membre modifié avec succès.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification du membre : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer un membre du laboratoire
     */
    public function supprimerMembre(Laboratoire $laboratoire, $membreId)
    {
        try {
            $membre = LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
                ->where('id_pers_lab', $membreId)
                ->firstOrFail();

            $membre->delete();

            return redirect()->route('labo.laboratoires.membres.index', $laboratoire)
                ->with('success', 'Membre supprimé avec succès du laboratoire.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression du membre : ' . $e->getMessage()]);
        }
    }

    /**
     * Récupérer les personnes par type via AJAX
     */
    public function getPersonsByType(Request $request, Laboratoire $laboratoire)
    {
        $type = $request->query('type');
        $data = [];

        switch ($type) {
            case 'personnel':
                $personnel = Personnel::all();
                $data = $personnel->map(function($p) {
                    return [
                        'id' => $p->code_pers,
                        'text' => $p->nom_pers . ' ' . $p->prenom_pers . ' (' . $p->code_pers . ')'
                    ];
                });
                break;

            case 'users':
                $users = Users::all();
                $data = $users->map(function($u) {
                    return [
                        'id' => $u->code_user,
                        'text' => $u->nom_user . ' ' . $u->prenom_user . ' (' . $u->code_user . ')'
                    ];
                });
                break;

            case 'user_externe':
                $userExternes = UserExterne::where('code_lab', $laboratoire->code_lab)->get();
                $data = $userExternes->map(function($ue) {
                    return [
                        'id' => $ue->id_user_ext,
                        'text' => $ue->nom_user_ext . ' ' . $ue->prenom_user_ext . ' (' . $ue->id_user_ext . ')'
                    ];
                });
                break;

            default:
                $data = [];
        }

        return response()->json($data);
    }
}
