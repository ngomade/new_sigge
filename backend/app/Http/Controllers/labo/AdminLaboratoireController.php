<?php

namespace App\Http\Controllers\Labo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\ProjetLabo;
use App\Models\laboratoires\Equipements;
use App\Models\laboratoires\Publication;
use App\Models\laboratoires\UserExterne;

class AdminLaboratoireController extends Controller
{
    public function dashboard($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Statistiques principales
        $stats = [
            'membres' => LaboratoirePersLab::where('code_lab', $code_lab)->where('statut', 'actif')->count(),
            'projets' => ProjetLabo::where('code_lab', $code_lab)->count(),
            'equipements' => Equipements::where('code_lab', $code_lab)->count(),
            'publications' => Publication::where('code_lab', $code_lab)->count(),
            'candidatures' => UserExterne::where('code_lab', $code_lab)->where('statut', 'en_attente')->count(),
            'externes' => UserExterne::where('code_lab', $code_lab)->where('statut', 'actif')->count(),
        ];

        return view('laboratoires.admin.dashboard', compact('laboratoire', 'stats'));
    }

    public function membres($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Filtres
        $role = $request->input('role');
        $statut = $request->input('statut');
        $type = $request->input('type');
        $search = $request->input('search');

        $query = LaboratoirePersLab::where('code_lab', $code_lab)
            ->with(['persLab', 'roleLabo', 'userExterne']);

        if ($role) {
            $query->where('id_rl', $role);
        }
        if ($statut) {
            $query->where('statut', $statut);
        }
        if ($type) {
            $query->whereHas('persLab', function($q) use ($type) {
                $q->where('type_pers_lab', $type);
            });
        }
        if ($search) {
            $query->whereHas('persLab', function($q) use ($search) {
                $q->where('id_pers_lab', 'like', "%$search%")
                  ->orWhere('type_pers_lab', 'like', "%$search%")
                  ->orWhere('id_pers_lab', 'like', "%$search%")
                  ;
            });
        }

        $membres = $query->orderByDesc('date_affectation')->paginate(20);
        $roles = \App\Models\laboratoires\RoleLabo::all();

        return view('laboratoires.admin.membres.index', compact('laboratoire', 'membres', 'roles', 'role', 'statut', 'type', 'search'));
    }

    public function ajouterMembreForm($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $roles = \App\Models\laboratoires\RoleLabo::all();
        $personnel = \App\Models\Personnel::all();
        $users = \App\Models\Users::all();
        $userExternes = \App\Models\laboratoires\UserExterne::where('code_lab', $code_lab)->get();
        return view('laboratoires.admin.membres.create', compact('laboratoire', 'roles', 'personnel', 'users', 'userExternes'));
    }

    public function ajouterMembre(Request $request, $code_lab)
    {
        $request->validate([
            'type_pers_lab' => 'required|in:personnel,user,user_externe',
            'id_pers_lab' => 'required',
            'id_rl' => 'required|exists:role_labo,id_rl',
            'date_affectation' => 'required|date',
            'date_fin_affectation' => 'nullable|date|after:date_affectation',
            'statut' => 'required|in:actif,inactif'
        ]);

        // Créer ou récupérer dans pers_lab
        $persLab = \App\Models\laboratoires\PersLab::firstOrCreate(
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
            'code_lab' => $code_lab,
            'id_pers_lab' => $persLab->id_pers_lab,
            'id_rl' => $request->id_rl,
            'date_affectation' => $request->date_affectation,
            'date_fin_affectation' => $request->date_fin_affectation,
            'statut' => $request->statut
        ];
        if ($request->type_pers_lab === 'user_externe') {
            $affectationData['id_user_externe'] = $request->id_pers_lab;
        }
        \App\Models\laboratoires\LaboratoirePersLab::create($affectationData);

        return redirect()->route('laboratoires.admin.membres', $code_lab)
            ->with('success', 'Membre ajouté avec succès au laboratoire.');
    }

    public function ficheMembre($code_lab, $membre)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $affectation = \App\Models\laboratoires\LaboratoirePersLab::with(['persLab', 'roleLabo', 'userExterne'])
            ->where('code_lab', $code_lab)
            ->where('id_pers_lab', $membre)
            ->firstOrFail();
        return view('laboratoires.admin.membres.show', compact('laboratoire', 'affectation'));
    }

    public function modifierMembreForm($code_lab, $membre)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $affectation = \App\Models\laboratoires\LaboratoirePersLab::with(['persLab', 'roleLabo', 'userExterne'])
            ->where('code_lab', $code_lab)
            ->where('id_pers_lab', $membre)
            ->firstOrFail();
        $roles = \App\Models\laboratoires\RoleLabo::all();
        return view('laboratoires.admin.membres.edit', compact('laboratoire', 'affectation', 'roles'));
    }

    public function modifierMembre(Request $request, $code_lab, $membre)
    {
        $request->validate([
            'id_rl' => 'required|exists:role_labo,id_rl',
            'date_affectation' => 'required|date',
            'date_fin_affectation' => 'nullable|date|after:date_affectation',
            'statut' => 'required|in:actif,inactif'
        ]);
        $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('id_pers_lab', $membre)
            ->firstOrFail();
        $affectation->update([
            'id_rl' => $request->id_rl,
            'date_affectation' => $request->date_affectation,
            'date_fin_affectation' => $request->date_fin_affectation,
            'statut' => $request->statut
        ]);
        return redirect()->route('laboratoires.admin.membres', $code_lab)
            ->with('success', 'Membre modifié avec succès.');
    }

    public function supprimerMembre(Request $request, $code_lab, $membre)
    {
        $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('id_pers_lab', $membre)
            ->firstOrFail();
        $affectation->delete();
        return redirect()->route('laboratoires.admin.membres', $code_lab)
            ->with('success', 'Membre supprimé avec succès.');
    }

    public function actionsGroupeesMembres(Request $request, $code_lab)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);
        if (!is_array($ids) || count($ids) === 0) {
            return back()->with('error', 'Aucun membre sélectionné.');
        }
        $affected = 0;
        if ($action === 'delete') {
            $affected = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
                ->whereIn('id_pers_lab', $ids)
                ->delete();
            return back()->with('success', "$affected membre(s) supprimé(s) avec succès.");
        } elseif ($action === 'role') {
            $role = $request->input('role');
            if (!$role) {
                return back()->with('error', 'Veuillez sélectionner un rôle.');
            }
            $affected = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
                ->whereIn('id_pers_lab', $ids)
                ->update(['id_rl' => $role]);
            return back()->with('success', "$affected membre(s) mis à jour (rôle).");
        } elseif ($action === 'statut') {
            $statut = $request->input('statut');
            if (!$statut) {
                return back()->with('error', 'Veuillez sélectionner un statut.');
            }
            $affected = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
                ->whereIn('id_pers_lab', $ids)
                ->update(['statut' => $statut]);
            return back()->with('success', "$affected membre(s) mis à jour (statut).");
        }
        return back()->with('error', 'Action non reconnue.');
    }

    public function projets($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // TODO: Implémenter la logique de gestion des projets
        return view('laboratoires.admin.projets.index', compact('laboratoire'));
    }

    public function equipements($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // TODO: Implémenter la logique de gestion des équipements
        return view('laboratoires.admin.equipements.index', compact('laboratoire'));
    }

    public function candidatures($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Filtres
        $statut = $request->input('statut');
        $search = $request->input('search');

        $query = UserExterne::where('code_lab', $code_lab);

        if ($statut) {
            $query->where('statut', $statut);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom_user_ext', 'like', "%$search%")
                  ->orWhere('prenom_user_ext', 'like', "%$search%")
                  ->orWhere('email_user_ext', 'like', "%$search%");
            });
        }

        $candidatures = $query->orderByDesc('created_at')->paginate(20);

        return view('laboratoires.admin.candidatures.index', compact('laboratoire', 'candidatures', 'statut', 'search'));
    }

    public function candidatureShow($code_lab, $candidature)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $candidature = UserExterne::where('code_lab', $code_lab)
            ->where('id_user_ext', $candidature)
            ->firstOrFail();

        return view('laboratoires.admin.candidatures.show', compact('laboratoire', 'candidature'));
    }

    public function candidatureApprove(Request $request, $code_lab, $candidature)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $candidature = UserExterne::where('code_lab', $code_lab)
            ->where('id_user_ext', $candidature)
            ->firstOrFail();

        try {
            // Générer un mot de passe temporaire
            $tempPassword = \Illuminate\Support\Str::random(8);

            // Mettre à jour le statut et le mot de passe
            $candidature->update([
                'statut' => 'actif',
                'pwd' => \Illuminate\Support\Facades\Hash::make($tempPassword)
            ]);

            // Mettre à jour l'affectation
            LaboratoirePersLab::where('id_user_externe', $candidature->id_user_ext)
                ->update(['statut' => 'actif']);

            // Mettre à jour pers_lab
            \App\Models\laboratoires\PersLab::where('id_pers_lab', $candidature->id_user_ext)
                ->update(['statut' => 'actif']);

            // TODO: Envoyer un email avec les identifiants
            // Mail::to($candidature->email_user_ext)->send(new CandidatureApprovedMail($candidature, $tempPassword));

            return redirect()->route('laboratoires.admin.candidatures', $code_lab)
                ->with('success', 'Candidature approuvée avec succès. Un email a été envoyé au candidat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'approbation : ' . $e->getMessage());
        }
    }

    public function candidatureReject(Request $request, $code_lab, $candidature)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $candidature = UserExterne::where('code_lab', $code_lab)
            ->where('id_user_ext', $candidature)
            ->firstOrFail();

        try {
            // Mettre à jour le statut
            $candidature->update(['statut' => 'rejeté']);

            // Mettre à jour l'affectation
            LaboratoirePersLab::where('id_user_externe', $candidature->id_user_ext)
                ->update(['statut' => 'rejeté']);

            // Mettre à jour pers_lab
            \App\Models\laboratoires\PersLab::where('id_pers_lab', $candidature->id_user_ext)
                ->update(['statut' => 'rejeté']);

            // TODO: Envoyer un email de rejet
            // Mail::to($candidature->email_user_ext)->send(new CandidatureRejectedMail($candidature));

            return redirect()->route('laboratoires.admin.candidatures', $code_lab)
                ->with('success', 'Candidature rejetée avec succès. Un email a été envoyé au candidat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du rejet : ' . $e->getMessage());
        }
    }

    public function externes($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Filtres
        $statut = $request->input('statut');
        $search = $request->input('search');

        $query = UserExterne::where('code_lab', $code_lab);

        if ($statut) {
            $query->where('statut', $statut);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom_user_ext', 'like', "%$search%")
                  ->orWhere('prenom_user_ext', 'like', "%$search%")
                  ->orWhere('email_user_ext', 'like', "%$search%");
            });
        }

        $externes = $query->orderByDesc('created_at')->paginate(20);

        return view('laboratoires.admin.externes.index', compact('laboratoire', 'externes', 'statut', 'search'));
    }

    public function externeCreate($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $roles = \App\Models\laboratoires\RoleLabo::all();

        return view('laboratoires.admin.externes.create', compact('laboratoire', 'roles'));
    }

    public function externeStore(Request $request, $code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        $request->validate([
            'nom_user_ext' => 'required|string|max:255',
            'prenom_user_ext' => 'required|string|max:255',
            'email_user_ext' => 'required|email|unique:user_externe,email_user_ext',
            'tel_user_ext' => 'required|string|max:20',
            'statut' => 'required|in:actif,inactif',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'motivation' => 'nullable|string',
            'id_rl' => 'required|exists:role_labo,id_rl',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        try {
            // Générer un mot de passe temporaire
            $tempPassword = \Illuminate\Support\Str::random(8);

            // Créer l'utilisateur externe
            $userExterne = UserExterne::create([
                'code_lab' => $code_lab,
                'nom_user_ext' => $request->nom_user_ext,
                'prenom_user_ext' => $request->prenom_user_ext,
                'email_user_ext' => $request->email_user_ext,
                'tel_user_ext' => $request->tel_user_ext,
                'statut' => $request->statut,
                'pwd' => \Illuminate\Support\Facades\Hash::make($tempPassword),
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'motivation' => $request->motivation
            ]);

            // Gérer le CV si fourni
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
                $userExterne->update(['cv_path' => $cvPath]);
            }

            // Créer l'entrée dans pers_lab
            $persLab = \App\Models\laboratoires\PersLab::create([
                'id_pers_lab' => $userExterne->id_user_ext,
                'type_pers_lab' => 'user_externe',
                'date_entree' => now(),
                'statut' => $request->statut
            ]);

            // Créer l'affectation
            \App\Models\laboratoires\LaboratoirePersLab::create([
                'code_lab' => $code_lab,
                'id_pers_lab' => $persLab->id_pers_lab,
                'id_user_externe' => $userExterne->id_user_ext,
                'id_rl' => $request->id_rl,
                'date_affectation' => $request->date_debut,
                'date_fin_affectation' => $request->date_fin,
                'statut' => $request->statut
            ]);

            return redirect()->route('laboratoires.admin.externes', $code_lab)
                ->with('success', 'Utilisateur externe créé avec succès. Mot de passe temporaire : ' . $tempPassword);

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function externeShow($code_lab, $externe)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $externe = UserExterne::where('code_lab', $code_lab)
            ->where('id_user_ext', $externe)
            ->firstOrFail();

        // Récupérer l'affectation
        $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('id_user_externe', $externe->id_user_ext)
            ->with('roleLabo')
            ->first();

        return view('laboratoires.admin.externes.show', compact('laboratoire', 'externe', 'affectation'));
    }

    public function externeEdit($code_lab, $externe)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $externe = UserExterne::where('code_lab', $code_lab)
            ->where('id_user_ext', $externe)
            ->firstOrFail();
        $roles = \App\Models\laboratoires\RoleLabo::all();

        // Récupérer l'affectation
        $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('id_user_externe', $externe->id_user_ext)
            ->first();

        return view('laboratoires.admin.externes.edit', compact('laboratoire', 'externe', 'affectation', 'roles'));
    }

    public function externeUpdate(Request $request, $code_lab, $externe)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $externe = UserExterne::where('code_lab', $code_lab)
            ->where('id_user_ext', $externe)
            ->firstOrFail();

        $request->validate([
            'nom_user_ext' => 'required|string|max:255',
            'prenom_user_ext' => 'required|string|max:255',
            'email_user_ext' => 'required|email|unique:user_externe,email_user_ext,' . $externe->id_user_ext . ',id_user_ext',
            'tel_user_ext' => 'required|string|max:20',
            'statut' => 'required|in:actif,inactif',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'motivation' => 'nullable|string',
            'id_rl' => 'required|exists:role_labo,id_rl',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        try {
            // Mettre à jour l'utilisateur externe
            $externe->update([
                'nom_user_ext' => $request->nom_user_ext,
                'prenom_user_ext' => $request->prenom_user_ext,
                'email_user_ext' => $request->email_user_ext,
                'tel_user_ext' => $request->tel_user_ext,
                'statut' => $request->statut,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'motivation' => $request->motivation
            ]);

            // Gérer le CV si fourni
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
                $externe->update(['cv_path' => $cvPath]);
            }

            // Mettre à jour pers_lab
            \App\Models\laboratoires\PersLab::where('id_pers_lab', $externe->id_user_ext)
                ->update(['statut' => $request->statut]);

            // Mettre à jour l'affectation
            \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('id_user_externe', $externe->id_user_ext)
                ->update([
                    'id_rl' => $request->id_rl,
                    'date_affectation' => $request->date_debut,
                    'date_fin_affectation' => $request->date_fin,
                    'statut' => $request->statut
                ]);

            return redirect()->route('laboratoires.admin.externes', $code_lab)
                ->with('success', 'Utilisateur externe mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function externeDestroy(Request $request, $code_lab, $externe)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $externe = UserExterne::where('code_lab', $code_lab)
            ->where('id_user_ext', $externe)
            ->firstOrFail();

        try {
            // Supprimer l'affectation
            \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('id_user_externe', $externe->id_user_ext)
                ->delete();

            // Supprimer de pers_lab
            \App\Models\laboratoires\PersLab::where('id_pers_lab', $externe->id_user_ext)
                ->delete();

            // Supprimer l'utilisateur externe
            $externe->delete();

            return redirect()->route('laboratoires.admin.externes', $code_lab)
                ->with('success', 'Utilisateur externe supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
