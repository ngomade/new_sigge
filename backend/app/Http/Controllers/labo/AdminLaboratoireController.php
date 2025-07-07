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

        // Statistiques détaillées des projets
        $projetsStats = [
            'en_cours' => ProjetLabo::where('code_lab', $code_lab)->where('statut_projet', 'en_cours')->count(),
            'termines' => ProjetLabo::where('code_lab', $code_lab)->where('statut_projet', 'termine')->count(),
            'en_attente' => ProjetLabo::where('code_lab', $code_lab)->where('statut_projet', 'en_attente')->count(),
            'suspendus' => ProjetLabo::where('code_lab', $code_lab)->where('statut_projet', 'suspendu')->count(),
        ];

        // Statistiques des équipements
        $equipementsStats = [
            'disponibles' => Equipements::where('code_lab', $code_lab)->where('etat', 'disponible')->count(),
            'en_utilisation' => Equipements::where('code_lab', $code_lab)->where('etat', 'en utilisation')->count(),
            'en_maintenance' => Equipements::where('code_lab', $code_lab)->where('etat', 'en maintenance')->count(),
            'hors_service' => Equipements::where('code_lab', $code_lab)->where('etat', 'hors service')->count(),
        ];

        // Projets récents
        $projetsRecents = ProjetLabo::where('code_lab', $code_lab)
            ->orderBy('debut_projet', 'desc')
            ->limit(5)
            ->get();

        // Équipements les plus utilisés
        $equipementsPopulaires = Equipements::where('code_lab', $code_lab)
            ->withCount('reservations')
            ->orderBy('reservations_count', 'desc')
            ->limit(5)
            ->get();

        // Activité récente (dernières 30 jours)
        $dateLimite = now()->subDays(30);
        $activiteRecente = [
            'nouvelles_candidatures' => UserExterne::where('code_lab', $code_lab)
                ->where('created_at', '>=', $dateLimite)
                ->count(),
            'nouveaux_projets' => ProjetLabo::where('code_lab', $code_lab)
                ->where('created_at', '>=', $dateLimite)
                ->count(),
            'nouvelles_reservations' => \App\Models\laboratoires\ReservationAgent::whereHas('equipement', function($q) use ($code_lab) {
                $q->where('code_lab', $code_lab);
            })->where('created_at', '>=', $dateLimite)->count(),
        ];

        return view('laboratoires.admin.dashboard', compact(
            'laboratoire',
            'stats',
            'projetsStats',
            'equipementsStats',
            'projetsRecents',
            'equipementsPopulaires',
            'activiteRecente'
        ));
    }

    public function dashboardNew($code_lab)
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

        // Statistiques détaillées des projets
        $projetsStats = [
            'en_cours' => ProjetLabo::where('code_lab', $code_lab)->where('statut_projet', 'en_cours')->count(),
            'termines' => ProjetLabo::where('code_lab', $code_lab)->where('statut_projet', 'termine')->count(),
            'en_attente' => ProjetLabo::where('code_lab', $code_lab)->where('statut_projet', 'en_attente')->count(),
            'suspendus' => ProjetLabo::where('code_lab', $code_lab)->where('statut_projet', 'suspendu')->count(),
        ];

        // Statistiques des équipements
        $equipementsStats = [
            'disponibles' => Equipements::where('code_lab', $code_lab)->where('etat', 'disponible')->count(),
            'en_utilisation' => Equipements::where('code_lab', $code_lab)->where('etat', 'en utilisation')->count(),
            'en_maintenance' => Equipements::where('code_lab', $code_lab)->where('etat', 'en maintenance')->count(),
            'hors_service' => Equipements::where('code_lab', $code_lab)->where('etat', 'hors service')->count(),
        ];

        // Projets récents
        $projetsRecents = ProjetLabo::where('code_lab', $code_lab)
            ->orderBy('debut_projet', 'desc')
            ->limit(5)
            ->get();

        // Équipements les plus utilisés
        $equipementsPopulaires = Equipements::where('code_lab', $code_lab)
            ->withCount('reservations')
            ->orderBy('reservations_count', 'desc')
            ->limit(5)
            ->get();

        // Activité récente (dernières 30 jours)
        $dateLimite = now()->subDays(30);
        $activiteRecente = [
            'nouvelles_candidatures' => UserExterne::where('code_lab', $code_lab)
                ->where('created_at', '>=', $dateLimite)
                ->count(),
            'nouveaux_projets' => ProjetLabo::where('code_lab', $code_lab)
                ->where('created_at', '>=', $dateLimite)
                ->count(),
            'nouvelles_reservations' => \App\Models\laboratoires\ReservationAgent::whereHas('equipement', function($q) use ($code_lab) {
                $q->where('code_lab', $code_lab);
            })->where('created_at', '>=', $dateLimite)->count(),
        ];

        return view('laboratoires.admin.dashboard-new', compact(
            'laboratoire',
            'stats',
            'projetsStats',
            'equipementsStats',
            'projetsRecents',
            'equipementsPopulaires',
            'activiteRecente'
        ));
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

    public function projets($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Filtres
        $statut = $request->input('statut');
        $search = $request->input('search');

        $query = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->with(['participants.membre', 'participants.userExterne', 'docs']);

        if ($statut) {
            $query->where('statut_projet', $statut);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('theme_projet', 'like', "%$search%")
                  ->orWhere('description_projet', 'like', "%$search%");
            });
        }

        $projets = $query->orderByDesc('created_at')->paginate(20);

        return view('laboratoires.admin.projets.index', compact('laboratoire', 'projets', 'statut', 'search'));
    }

    public function projetCreate($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Récupérer les membres actifs du laboratoire
        $membres = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with(['persLab', 'userExterne'])
            ->get();

        return view('laboratoires.admin.projets.create', compact('laboratoire', 'membres'));
    }

    public function projetStore(Request $request, $code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        $request->validate([
            'theme_projet' => 'required|string|max:255',
            'description_projet' => 'required|string',
            'statut_projet' => 'required|in:En cours,Terminé,En pause,Annulé',
            'debut_projet' => 'required|date',
            'fin_projet' => 'nullable|date|after:debut_projet',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:laboratoire_pers_lab,id_pers_lab',
            'roles_participants' => 'nullable|array',
            'roles_participants.*' => 'string|max:100'
        ]);

        try {
            // Créer le projet
            $projet = \App\Models\laboratoires\ProjetLabo::create([
                'theme_projet' => $request->theme_projet,
                'description_projet' => $request->description_projet,
                'code_lab' => $code_lab,
                'statut_projet' => $request->statut_projet,
                'debut_projet' => $request->debut_projet,
                'fin_projet' => $request->fin_projet
            ]);

            // Ajouter les participants
            if ($request->has('participants')) {
                foreach ($request->participants as $index => $id_pers_lab) {
                    $role = $request->roles_participants[$index] ?? 'Participant';

                    \App\Models\laboratoires\ParticiperProjet::create([
                        'code_projet' => $projet->code_projet,
                        'id_pers_lab' => $id_pers_lab,
                        'role' => $role,
                        'debut_participation' => $request->debut_projet,
                        'fin_participation' => $request->fin_projet
                    ]);
                }
            }

            return redirect()->route('laboratoires.admin.projets.show', [$code_lab, $projet->code_projet])
                ->with('success', 'Projet créé avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function projetShow($code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->with(['participants.membre', 'participants.userExterne', 'docs'])
            ->firstOrFail();

        return view('laboratoires.admin.projets.show', compact('laboratoire', 'projet'));
    }

    public function projetEdit($code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->with(['participants.membre', 'participants.userExterne'])
            ->firstOrFail();

        // Récupérer les membres actifs du laboratoire
        $membres = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with(['persLab', 'userExterne'])
            ->get();

        return view('laboratoires.admin.projets.edit', compact('laboratoire', 'projet', 'membres'));
    }

    public function projetUpdate(Request $request, $code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->firstOrFail();

        $request->validate([
            'theme_projet' => 'required|string|max:255',
            'description_projet' => 'required|string',
            'statut_projet' => 'required|in:En cours,Terminé,En pause,Annulé',
            'debut_projet' => 'required|date',
            'fin_projet' => 'nullable|date|after:debut_projet'
        ]);

        try {
            $projet->update([
                'theme_projet' => $request->theme_projet,
                'description_projet' => $request->description_projet,
                'statut_projet' => $request->statut_projet,
                'debut_projet' => $request->debut_projet,
                'fin_projet' => $request->fin_projet
            ]);

            return redirect()->route('laboratoires.admin.projets.show', [$code_lab, $projet->code_projet])
                ->with('success', 'Projet mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function projetDestroy(Request $request, $code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->firstOrFail();

        try {
            // Supprimer les participants
            \App\Models\laboratoires\ParticiperProjet::where('code_projet', $projet->code_projet)->delete();

            // Supprimer les documents
            \App\Models\laboratoires\DocProjetLabo::where('code_projet', $projet->code_projet)->delete();

            // Supprimer le projet
            $projet->delete();

            return redirect()->route('laboratoires.admin.projets', $code_lab)
                ->with('success', 'Projet supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function projetParticipants($code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->with(['participants.membre', 'participants.userExterne'])
            ->firstOrFail();

        // Récupérer les membres actifs du laboratoire
        $membres = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with(['persLab', 'userExterne'])
            ->get();

        // Récupérer les utilisateurs externes actifs
        $usersExternes = \App\Models\laboratoires\UserExterne::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->get();

        return view('laboratoires.admin.projets.participants', compact('laboratoire', 'projet', 'membres', 'usersExternes'));
    }

    public function projetParticipantsStore(Request $request, $code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->firstOrFail();

        $request->validate([
            'type_participant' => 'required|in:membre,externe',
            'membre_id' => 'required_if:type_participant,membre|exists:laboratoire_pers_lab,id_pers_lab',
            'user_externe_id' => 'required_if:type_participant,externe|exists:user_externe,id_user_ext',
            'role' => 'required|string|max:100',
            'debut_participation' => 'required|date',
            'fin_participation' => 'nullable|date|after:debut_participation'
        ]);

        try {
            $id_pers_lab = null;
            $id_user_ext = null;

            if ($request->type_participant === 'membre') {
                $id_pers_lab = $request->membre_id;
            } else {
                $id_user_ext = $request->user_externe_id;
            }

            // Vérifier si le participant n'est pas déjà dans le projet
            $existing = \App\Models\laboratoires\ParticiperProjet::where('code_projet', $projet->code_projet)
                ->where(function($query) use ($id_pers_lab, $id_user_ext) {
                    if ($id_pers_lab) {
                        $query->where('id_pers_lab', $id_pers_lab);
                    }
                    if ($id_user_ext) {
                        $query->orWhere('id_user_ext', $id_user_ext);
                    }
                })
                ->first();

            if ($existing) {
                return back()->with('error', 'Ce participant est déjà dans le projet.');
            }

            \App\Models\laboratoires\ParticiperProjet::create([
                'code_projet' => $projet->code_projet,
                'id_pers_lab' => $id_pers_lab,
                'id_user_ext' => $id_user_ext,
                'role' => $request->role,
                'debut_participation' => $request->debut_participation,
                'fin_participation' => $request->fin_participation
            ]);

            return redirect()->route('laboratoires.admin.projets.participants', [$code_lab, $projet->code_projet])
                ->with('success', 'Participant ajouté avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    public function projetParticipantsDestroy(Request $request, $code_lab, $projet, $participant)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->firstOrFail();

        try {
            // Supprimer le participant (peut être un membre ou un utilisateur externe)
            \App\Models\laboratoires\ParticiperProjet::where('code_projet', $projet->code_projet)
                ->where(function($query) use ($participant) {
                    $query->where('id_pers_lab', $participant)
                          ->orWhere('id_user_ext', $participant);
                })
                ->delete();

            return redirect()->route('laboratoires.admin.projets.participants', [$code_lab, $projet->code_projet])
                ->with('success', 'Participant retiré avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function projetDocuments($code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->with('docs')
            ->firstOrFail();

        return view('laboratoires.admin.projets.documents', compact('laboratoire', 'projet'));
    }

    public function projetDocumentsStore(Request $request, $code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->firstOrFail();

        $request->validate([
            'titre_doc' => 'required|string|max:255',
            'description_doc' => 'nullable|string|max:1000',
            'fichier' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif|max:10240'
        ]);

        try {
            $fichierPath = $request->file('fichier')->store('documents_projets', 'public');

            \App\Models\laboratoires\DocProjetLabo::create([
                'code_projet' => $projet->code_projet,
                'titre_doc' => $request->titre_doc,
                'description_doc' => $request->description_doc,
                'fichier' => $fichierPath
            ]);

            return redirect()->route('laboratoires.admin.projets.documents', [$code_lab, $projet->code_projet])
                ->with('success', 'Document ajouté avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    public function projetDocumentsDestroy(Request $request, $code_lab, $projet, $document)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->firstOrFail();

        try {
            $doc = \App\Models\laboratoires\DocProjetLabo::where('code_projet', $projet->code_projet)
                ->where('id_doc', $document)
                ->firstOrFail();

            // Supprimer le fichier
            if ($doc->fichier) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->fichier);
            }

            $doc->delete();

            return redirect()->route('laboratoires.admin.projets.documents', [$code_lab, $projet->code_projet])
                ->with('success', 'Document supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function equipements($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Filtres
        $etat = $request->input('etat');
        $search = $request->input('search');
        $localisation = $request->input('localisation');

        $query = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->with(['laboratoire', 'entretiens', 'reservations']);

        if ($etat) {
            $query->where('etat', $etat);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom_equip', 'like', "%$search%")
                  ->orWhere('ref_equip', 'like', "%$search%")
                  ->orWhere('desc_equip', 'like', "%$search%");
            });
        }
        if ($localisation) {
            $query->where('localisation', 'like', "%$localisation%");
        }

        $equipements = $query->orderBy('nom_equip')->paginate(20);

        // Statistiques
        $stats = [
            'total' => \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)->count(),
            'disponible' => \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)->where('etat', 'disponible')->count(),
            'maintenance' => \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)->where('etat', 'en maintenance')->count(),
            'hors_service' => \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)->where('etat', 'hors service')->count(),
        ];

        return view('laboratoires.admin.equipements.index', compact('laboratoire', 'equipements', 'stats', 'etat', 'search', 'localisation'));
    }

    public function equipementCreate($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        return view('laboratoires.admin.equipements.create', compact('laboratoire'));
    }

    public function equipementStore(Request $request, $code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        $request->validate([
            'nom_equip' => 'required|string|max:150',
            'ref_equip' => 'nullable|string|max:100',
            'desc_equip' => 'nullable|string',
            'etat' => 'required|in:disponible,en maintenance,hors service',
            'date_achat' => 'nullable|date',
            'valeur' => 'nullable|numeric|min:0',
            'localisation' => 'nullable|string|max:150'
        ]);

        try {
            \App\Models\laboratoires\Equipements::create([
                'nom_equip' => $request->nom_equip,
                'ref_equip' => $request->ref_equip,
                'desc_equip' => $request->desc_equip,
                'etat' => $request->etat,
                'date_achat' => $request->date_achat,
                'valeur' => $request->valeur,
                'localisation' => $request->localisation,
                'code_lab' => $code_lab
            ]);

            return redirect()->route('laboratoires.admin.equipements', $code_lab)
                ->with('success', 'Équipement ajouté avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    public function equipementShow($code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->with(['laboratoire', 'entretiens.personnel', 'reservations.personnel'])
            ->firstOrFail();

        return view('laboratoires.admin.equipements.show', compact('laboratoire', 'equipement'));
    }

    public function equipementEdit($code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        return view('laboratoires.admin.equipements.edit', compact('laboratoire', 'equipement'));
    }

    public function equipementUpdate(Request $request, $code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        $request->validate([
            'nom_equip' => 'required|string|max:150',
            'ref_equip' => 'nullable|string|max:100',
            'desc_equip' => 'nullable|string',
            'etat' => 'required|in:disponible,en maintenance,hors service',
            'date_achat' => 'nullable|date',
            'valeur' => 'nullable|numeric|min:0',
            'localisation' => 'nullable|string|max:150'
        ]);

        try {
            $equipement->update([
                'nom_equip' => $request->nom_equip,
                'ref_equip' => $request->ref_equip,
                'desc_equip' => $request->desc_equip,
                'etat' => $request->etat,
                'date_achat' => $request->date_achat,
                'valeur' => $request->valeur,
                'localisation' => $request->localisation
            ]);

            return redirect()->route('laboratoires.admin.equipements.show', [$code_lab, $equipement->code_equip])
                ->with('success', 'Équipement mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function equipementDestroy(Request $request, $code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        try {
            // Vérifier s'il y a des réservations actives
            if ($equipement->hasReservationActive()) {
                return back()->with('error', 'Impossible de supprimer cet équipement car il a des réservations actives.');
            }

            // Vérifier s'il y a des entretiens en cours
            if ($equipement->getEntretienEnCours()) {
                return back()->with('error', 'Impossible de supprimer cet équipement car il a un entretien en cours.');
            }

            $equipement->delete();

            return redirect()->route('laboratoires.admin.equipements', $code_lab)
                ->with('success', 'Équipement supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    // Gestion des entretiens
    public function equipementEntretiens($code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->with(['entretiens.personnel'])
            ->firstOrFail();

        $personnel = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with(['persLab'])
            ->get();

        return view('laboratoires.admin.equipements.entretiens', compact('laboratoire', 'equipement', 'personnel'));
    }

    public function equipementEntretienStore(Request $request, $code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        $request->validate([
            'id_pers_lab' => 'required|exists:laboratoire_pers_lab,id_pers_lab',
            'type_entretien' => 'required|in:entretien,reparation',
            'debut_entretien' => 'required|date',
            'fin_entretien' => 'required|date|after:debut_entretien',
            'desc_entretien' => 'nullable|string',
            'cout' => 'nullable|numeric|min:0'
        ]);

        try {
            // Vérifier s'il y a déjà un entretien en cours
            $entretienEnCours = $equipement->getEntretienEnCours();
            if ($entretienEnCours) {
                return back()->with('error', 'Cet équipement a déjà un entretien en cours.');
            }

            \App\Models\laboratoires\EntretienReparation::create([
                'code_equip' => $equipement->code_equip,
                'id_pers_lab' => $request->id_pers_lab,
                'statut_entretien' => 'En cours',
                'debut_entretien' => $request->debut_entretien,
                'fin_entretien' => $request->fin_entretien,
                'type_entretien' => $request->type_entretien,
                'desc_entretien' => $request->desc_entretien,
                'cout' => $request->cout
            ]);

            // Mettre à jour l'état de l'équipement
            $equipement->update(['etat' => 'en maintenance']);

            return redirect()->route('laboratoires.admin.equipements.entretiens', [$code_lab, $equipement->code_equip])
                ->with('success', 'Entretien programmé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la programmation : ' . $e->getMessage());
        }
    }

    public function equipementEntretienUpdate(Request $request, $code_lab, $equipement, $entretien)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        $entretien = \App\Models\laboratoires\EntretienReparation::where('code_equip', $equipement->code_equip)
            ->where('id', $entretien)
            ->firstOrFail();

        $request->validate([
            'statut_entretien' => 'required|in:En cours,Terminé,En pause,Annulé',
            'debut_entretien' => 'required|date',
            'fin_entretien' => 'required|date|after:debut_entretien',
            'desc_entretien' => 'nullable|string',
            'cout' => 'nullable|numeric|min:0'
        ]);

        try {
            $entretien->update([
                'statut_entretien' => $request->statut_entretien,
                'debut_entretien' => $request->debut_entretien,
                'fin_entretien' => $request->fin_entretien,
                'desc_entretien' => $request->desc_entretien,
                'cout' => $request->cout
            ]);

            // Mettre à jour l'état de l'équipement selon le statut
            $nouvelEtat = match($request->statut_entretien) {
                'Terminé' => 'disponible',
                'Annulé' => 'disponible',
                default => 'en maintenance'
            };
            $equipement->update(['etat' => $nouvelEtat]);

            return redirect()->route('laboratoires.admin.equipements.entretiens', [$code_lab, $equipement->code_equip])
                ->with('success', 'Entretien mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    // Gestion des réservations
    public function equipementReservations($code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->with(['reservations.personnel'])
            ->firstOrFail();

        $personnel = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with(['persLab'])
            ->get();

        return view('laboratoires.admin.equipements.reservations', compact('laboratoire', 'equipement', 'personnel'));
    }

    public function equipementReservationStore(Request $request, $code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        $request->validate([
            'id_pers_lab' => 'required|exists:laboratoire_pers_lab,id_pers_lab',
            'debut_reserv' => 'required|date|after_or_equal:today',
            'fin_reserv' => 'required|date|after:debut_reserv'
        ]);

        try {
            // Vérifier si l'équipement est disponible
            if (!$equipement->isDisponible()) {
                return back()->with('error', 'Cet équipement n\'est pas disponible pour la réservation.');
            }

            // Vérifier s'il y a des conflits de réservation
            $conflit = \App\Models\laboratoires\ReservationAgent::where('code_equip', $equipement->code_equip)
                ->where('statut', 'confirmé')
                ->where(function($query) use ($request) {
                    $query->whereBetween('debut_reserv', [$request->debut_reserv, $request->fin_reserv])
                          ->orWhereBetween('fin_reserv', [$request->debut_reserv, $request->fin_reserv])
                          ->orWhere(function($q) use ($request) {
                              $q->where('debut_reserv', '<=', $request->debut_reserv)
                                ->where('fin_reserv', '>=', $request->fin_reserv);
                          });
                })
                ->first();

            if ($conflit) {
                return back()->with('error', 'Il y a un conflit de réservation pour cette période.');
            }

            \App\Models\laboratoires\ReservationAgent::create([
                'code_equip' => $equipement->code_equip,
                'id_pers_lab' => $request->id_pers_lab,
                'debut_reserv' => $request->debut_reserv,
                'fin_reserv' => $request->fin_reserv,
                'statut' => 'en attente'
            ]);

            return redirect()->route('laboratoires.admin.equipements.reservations', [$code_lab, $equipement->code_equip])
                ->with('success', 'Demande de réservation créée avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function equipementReservationUpdate(Request $request, $code_lab, $equipement, $reservation)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = \App\Models\laboratoires\Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        $reservation = \App\Models\laboratoires\ReservationAgent::where('code_equip', $equipement->code_equip)
            ->where('id_pers_lab', $reservation)
            ->firstOrFail();

        $request->validate([
            'statut' => 'required|in:en attente,confirmé,refusé,annulé'
        ]);

        try {
            $reservation->update(['statut' => $request->statut]);

            // Si la réservation est confirmée, mettre à jour l'état de l'équipement
            if ($request->statut === 'confirmé') {
                $equipement->update(['etat' => 'réservé']);
            } elseif ($request->statut === 'refusé' || $request->statut === 'annulé') {
                // Vérifier s'il y a d'autres réservations actives
                $autresReservations = \App\Models\laboratoires\ReservationAgent::where('code_equip', $equipement->code_equip)
                    ->where('statut', 'confirmé')
                    ->where('id_pers_lab', '!=', $reservation->id_pers_lab)
                    ->exists();

                if (!$autresReservations) {
                    $equipement->update(['etat' => 'disponible']);
                }
            }

            return redirect()->route('laboratoires.admin.equipements.reservations', [$code_lab, $equipement->code_equip])
                ->with('success', 'Statut de la réservation mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
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
            LaboratoirePersLab::where('id_user_ext', $candidature->id_user_ext)
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
            LaboratoirePersLab::where('id_user_ext', $candidature->id_user_ext)
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
            'motivation' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
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
                // 'motivation' => $request->motivation
            ]);

            // Gérer le CV si fourni
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
                $userExterne->update(['cv_path' => $cvPath]);
            }

             // Gérer les motivations si fourni
            if ($request->hasFile('motivation')) {
                $motivationPath = $request->file('motivation')->store('motivations', 'public');
                $userExterne->update(['motivation_path' => $motivationPath]);
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
                ->where('id_user_ext', $externe->id_user_ext)
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
                ->where('id_user_ext', $externe->id_user_ext)
                ->delete();

            // Supprimer de pers_lab
            // Removed deletion from pers_lab as tables are independent to avoid SQL error
            // \App\Models\laboratoires\PersLab::whereRaw('BINARY `id_pers_lab` = ?', [$externe->id_user_ext])
            //     ->delete();

            // Supprimer l'utilisateur externe
            $externe->delete();

            return redirect()->route('laboratoires.admin.externes', $code_lab)
                ->with('success', 'Utilisateur externe supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Générer un rapport PDF du laboratoire
     */
    public function generateReportPDF($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $type = $request->input('type', 'general');

        // Récupérer les données selon le type de rapport
        $data = $this->getReportData($code_lab, $type);

        // Générer le PDF
        $pdf = \PDF::loadView('laboratoires.admin.reports.pdf', compact('laboratoire', 'data', 'type'));

        $filename = "rapport_{$laboratoire->code_lab}_{$type}_" . now()->format('Y-m-d') . ".pdf";

        return $pdf->download($filename);
    }

    /**
     * Générer un rapport Excel du laboratoire
     */
    public function generateReportExcel($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $type = $request->input('type', 'general');

        // Récupérer les données selon le type de rapport
        $data = $this->getReportData($code_lab, $type);

        $filename = "rapport_{$laboratoire->code_lab}_{$type}_" . now()->format('Y-m-d') . ".xlsx";

        return \Excel::download(new \App\Exports\LaboratoireReportExport($laboratoire, $data, $type), $filename);
    }

    /**
     * Afficher les statistiques d'utilisation des équipements
     */
    public function equipementsStats($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        $periode = $request->input('periode', '30'); // jours
        $dateDebut = now()->subDays($periode);

        // Statistiques d'utilisation par équipement
        $statsUtilisation = \App\Models\laboratoires\ReservationAgent::whereHas('equipement', function($q) use ($code_lab) {
            $q->where('code_lab', $code_lab);
        })
        ->where('debut_reserv', '>=', $dateDebut)
        ->with('equipement')
        ->get()
        ->groupBy('code_equip')
        ->map(function($reservations) {
            return [
                'total_heures' => $reservations->sum(function($r) {
                    return \Carbon\Carbon::parse($r->debut_reserv)->diffInHours($r->fin_reserv);
                }),
                'nombre_reservations' => $reservations->count(),
                'taux_utilisation' => $reservations->where('statut', 'confirmé')->count() / max($reservations->count(), 1) * 100
            ];
        });

        // Équipements les plus utilisés
        $equipementsPopulaires = Equipements::where('code_lab', $code_lab)
            ->withCount(['reservations' => function($q) use ($dateDebut) {
                $q->where('debut_reserv', '>=', $dateDebut);
            }])
            ->orderBy('reservations_count', 'desc')
            ->get();

        // Équipements sous-utilisés
        $equipementsSousUtilises = Equipements::where('code_lab', $code_lab)
            ->whereDoesntHave('reservations', function($q) use ($dateDebut) {
                $q->where('debut_reserv', '>=', $dateDebut);
            })
            ->get();

        // Statistiques par période (pour graphiques)
        $statsParPeriode = \App\Models\laboratoires\ReservationAgent::whereHas('equipement', function($q) use ($code_lab) {
            $q->where('code_lab', $code_lab);
        })
        ->where('debut_reserv', '>=', $dateDebut)
        ->selectRaw('DATE(debut_reserv) as date, COUNT(*) as total_reservations')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return view('laboratoires.admin.equipements.stats', compact(
            'laboratoire',
            'statsUtilisation',
            'equipementsPopulaires',
            'equipementsSousUtilises',
            'statsParPeriode',
            'periode'
        ));
    }

    /**
     * Afficher la page de reporting
     */
    public function reporting($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        return view('laboratoires.admin.reporting', compact('laboratoire'));
    }

    /**
     * Récupérer les données pour les rapports
     */
    private function getReportData($code_lab, $type)
    {
        switch ($type) {
            case 'membres':
                return [
                    'membres' => LaboratoirePersLab::where('code_lab', $code_lab)
                        ->with(['persLab', 'roleLabo'])
                        ->get(),
                    'roles' => \App\Models\laboratoires\RoleLabo::all(),
                ];

            case 'projets':
                return [
                    'projets' => ProjetLabo::where('code_lab', $code_lab)
                        ->with(['participants', 'documents'])
                        ->get(),
                ];

            case 'equipements':
                return [
                    'equipements' => Equipements::where('code_lab', $code_lab)
                        ->with(['entretiens', 'reservations'])
                        ->get(),
                ];

            case 'utilisations':
                return [
                    'reservations' => \App\Models\laboratoires\ReservationAgent::whereHas('equipement', function($q) use ($code_lab) {
                        $q->where('code_lab', $code_lab);
                    })->with(['equipement', 'personnel'])->get(),
                ];

            default: // general
                return [
                    'membres' => LaboratoirePersLab::where('code_lab', $code_lab)->count(),
                    'projets' => ProjetLabo::where('code_lab', $code_lab)->count(),
                    'equipements' => Equipements::where('code_lab', $code_lab)->count(),
                    'publications' => Publication::where('code_lab', $code_lab)->count(),
                    'externes' => UserExterne::where('code_lab', $code_lab)->count(),
                                    'reservations' => \App\Models\laboratoires\ReservationAgent::whereHas('equipement', function($q) use ($code_lab) {
                    $q->where('code_lab', $code_lab);
                })->count(),
                ];
        }
    }
}
