<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\ReservationAgent;
use Exception;
use Illuminate\Http\Request;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\ProjetLabo;
use App\Models\laboratoires\Equipements;
use App\Models\laboratoires\Publication;
use App\Models\laboratoires\UserExterne;
use App\Models\laboratoires\RapportLabo;
use App\Models\laboratoires\LabNotif;
use App\Services\LaboratoireAlertService;
use App\Mail\ExterneConfirmationMail;
use App\Mail\ExternePasswordResetMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaboAnnonce;

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
            'nouvelles_reservations' => ReservationAgent::whereHas('equipement', function ($q) use ($code_lab) {
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
            'nouvelles_reservations' => ReservationAgent::whereHas('equipement', function ($q) use ($code_lab) {
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
            ->with([
                'persLab.personnel',
                'persLab.user',
                'roleLabo',
                'userExterne'
            ]);

        if ($role) {
            $query->where('id_rl', $role);
        }
        if ($statut) {
            $query->where('statut', $statut);
        }
        if ($type) {
            if ($type === 'user_externe') {
                // Pour les externes : vérifier qu'ils ont un id_user_externe
                $query->whereNotNull('id_user_externe');
            } else {
                // Pour personnel et users : vérifier dans persLab
                $query->whereHas('persLab', function ($q) use ($type) {
                    $q->where('type_pers_lab', $type);
                });
            }
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Recherche dans persLab (personnel et users)
                $q->whereHas('persLab', function ($subQ) use ($search) {
                    $subQ->where('id_pers_lab', 'like', "%$search%")
                        ->orWhere('type_pers_lab', 'like', "%$search%");
                })
                    // Ou recherche dans userExterne
                    ->orWhereHas('userExterne', function ($subQ) use ($search) {
                        $subQ->where('nom_user_ext', 'like', "%$search%")
                            ->orWhere('prenom_user_ext', 'like', "%$search%")
                            ->orWhere('email_user_ext', 'like', "%$search%");
                    });
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

        // Créer l'affectation selon le type
        if ($request->type_pers_lab === 'user_externe') {
            // Pour les externes : pas de pers_lab, utiliser uniquement id_user_externe
            $affectationData = [
                'code_lab' => $code_lab,
                'id_user_externe' => $request->id_pers_lab,
                'id_rl' => $request->id_rl,
                'date_affectation' => $request->date_affectation,
                'date_fin_affectation' => $request->date_fin_affectation,
                'statut' => $request->statut
            ];
        } else {
            // Pour personnel et users : créer dans pers_lab puis affecter
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

            $affectationData = [
                'code_lab' => $code_lab,
                'id_pers_lab' => $persLab->id_pers_lab,
                'id_rl' => $request->id_rl,
                'date_affectation' => $request->date_affectation,
                'date_fin_affectation' => $request->date_fin_affectation,
                'statut' => $request->statut
            ];
        }

        LaboratoirePersLab::create($affectationData);

        return redirect()->route('laboratoires.admin.membres', $code_lab)
            ->with('success', 'Membre ajouté avec succès au laboratoire.');
    }

    public function ficheMembre($code_lab, $membre)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $layout = session()->has('laboratoire_code') ? 'laboratoires.public.layout' : 'sige_app.backend.template.backend';
        $affectation = LaboratoirePersLab::with(['persLab', 'roleLabo', 'userExterne'])
            ->where('code_lab', $code_lab)
            ->where(function ($q) use ($membre) {
                $q->where('id_pers_lab', $membre)
                    ->orWhere('id_user_externe', $membre);
            })
            ->firstOrFail();
        return view('laboratoires.admin.membres.show', compact('laboratoire', 'affectation', 'layout'));
    }

    public function modifierMembreForm($code_lab, $membre)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $layout = session()->has('laboratoire_code') ? 'laboratoires.public.layout' : 'sige_app.backend.template.backend';
        $affectation = LaboratoirePersLab::with(['persLab', 'roleLabo', 'userExterne'])
            ->where('code_lab', $code_lab)
            ->where(function ($q) use ($membre) {
                $q->where('id_pers_lab', $membre)
                    ->orWhere('id_user_externe', $membre);
            })
            ->firstOrFail();
        $roles = \App\Models\laboratoires\RoleLabo::all();
        return view('laboratoires.admin.membres.edit', compact('laboratoire', 'affectation', 'roles', 'layout'));
    }

    public function modifierMembre(Request $request, $code_lab, $membre)
    {
        $request->validate([
            'id_rl' => 'required|exists:role_labo,id_rl',
            'date_affectation' => 'required|date',
            'date_fin_affectation' => 'nullable|date|after:date_affectation',
            'statut' => 'required|in:actif,inactif'
        ]);
        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where(function ($q) use ($membre) {
                $q->where('id_pers_lab', $membre)
                    ->orWhere('id_user_externe', $membre);
            })
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
        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where(function ($q) use ($membre) {
                $q->where('id_pers_lab', $membre)
                    ->orWhere('id_user_externe', $membre);
            })
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
            $affected = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where(function ($q) use ($ids) {
                    $q->whereIn('id_pers_lab', $ids)
                        ->orWhereIn('id_user_externe', $ids);
                })
                ->delete();
            return back()->with('success', "$affected membre(s) supprimé(s) avec succès.");
        } elseif ($action === 'role') {
            $role = $request->input('role');
            if (!$role) {
                return back()->with('error', 'Veuillez sélectionner un rôle.');
            }
            $affected = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where(function ($q) use ($ids) {
                    $q->whereIn('id_pers_lab', $ids)
                        ->orWhereIn('id_user_externe', $ids);
                })
                ->update(['id_rl' => $role]);
            return back()->with('success', "$affected membre(s) mis à jour (rôle).");
        } elseif ($action === 'statut') {
            $statut = $request->input('statut');
            if (!$statut) {
                return back()->with('error', 'Veuillez sélectionner un statut.');
            }
            $affected = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where(function ($q) use ($ids) {
                    $q->whereIn('id_pers_lab', $ids)
                        ->orWhereIn('id_user_externe', $ids);
                })
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
            $query->where(function ($q) use ($search) {
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

        // Récupérer les membres actifs du laboratoire avec les relations imbriquées
        $membres = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with([
                'persLab.personnel',
                'persLab.user',
                'userExterne'
            ])
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
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function projetShow($code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->with([
                'participants.membre.personnel',
                'participants.membre.user',
                'participants.userExterne',
                'docs'
            ])
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

        // Récupérer les membres actifs du laboratoire avec les relations imbriquées
        $membres = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with([
                'persLab.personnel',
                'persLab.user',
                'userExterne'
            ])
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function projetParticipants($code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->with([
                'participants.membre.personnel',
                'participants.membre.user',
                'participants.userExterne'
            ])
            ->firstOrFail();

        // Debug: vérifier les participants chargés
        \Log::info('Participants chargés:', [
            'count' => $projet->participants->count(),
            'participants' => $projet->participants->map(function ($p) {
                return [
                    'id_pers_lab' => $p->id_pers_lab,
                    'id_user_ext' => $p->id_user_ext,
                    'membre_loaded' => $p->relationLoaded('membre'),
                    'userExterne_loaded' => $p->relationLoaded('userExterne'),
                    'membre_type' => $p->membre ? $p->membre->type_pers_lab : null,
                    'membre_nom' => $p->membre ? $p->membre->nom_complet : null,
                    'membre_email' => $p->membre ? $p->membre->email : null,
                ];
            })->toArray()
        ]);

        // Récupérer les participants déjà dans le projet
        $participantsExistants = $projet->participants;
        $idsParticipantsExistants = [];

        foreach ($participantsExistants as $participant) {
            if ($participant->id_pers_lab) {
                // Pour les membres internes, récupérer l'ID de l'affectation
                $affectation = LaboratoirePersLab::where('id_pers_lab', $participant->id_pers_lab)
                    ->where('code_lab', $code_lab)
                    ->first();
                if ($affectation) {
                    $idsParticipantsExistants[] = $affectation->id;
                }
            } elseif ($participant->id_user_ext) {
                $idsParticipantsExistants[] = $participant->id_user_ext;
            }
        }

        // Récupérer les membres internes actifs du laboratoire (personnel et utilisateurs) avec les relations imbriquées
        $membres = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->whereNotNull('id_pers_lab') // Seulement les membres internes
            ->whereNotIn('id', $idsParticipantsExistants) // Exclure ceux déjà dans le projet
            ->with([
                'persLab.personnel',
                'persLab.user'
            ])
            ->get();

        // Récupérer les utilisateurs externes actifs (exclure ceux déjà dans le projet)
        $usersExternes = \App\Models\laboratoires\UserExterne::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->whereNotIn('id_user_ext', $idsParticipantsExistants)
            ->get();

        return view('laboratoires.admin.projets.participants', compact('laboratoire', 'projet', 'membres', 'usersExternes'));
    }

    public function projetParticipantsStore(Request $request, $code_lab, $projet)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->firstOrFail();

            $rules = [
            'type_participant' => 'required|in:membre,externe',

            'role' => 'required|string|max:100',
            'debut_participation' => 'required|date',
            'fin_participation' => 'nullable|date|after:debut_participation'
            ];

        // Ajouter les règles conditionnelles
        if ($request->type_participant === 'membre') {
            $rules['membre_id'] = 'required|exists:laboratoire_pers_lab,id';
        } else {
            $rules['user_externe_id'] = 'required|exists:user_externe,id_user_ext';
        }

        $request->validate($rules);

        // Additional check to prevent null id_pers_lab when type is membre
        if ($request->type_participant === 'membre' && empty($request->membre_id)) {
            return back()->withInput()->with('error', 'Veuillez sélectionner un membre du laboratoire.');
        }
        if ($request->type_participant === 'externe' && empty($request->user_externe_id)) {
            return back()->withInput()->with('error', 'Veuillez sélectionner un utilisateur externe.');
        }

        try {
            $id_pers_lab = null;
            $id_user_ext = null;
            if ($request->type_participant === 'membre') {
                $laboratoirePersLab = \App\Models\laboratoires\LaboratoirePersLab::where('id', $request->membre_id)->first();
                if (!$laboratoirePersLab) {
                    return back()->withInput()->with('error', 'Membre du laboratoire invalide.');
                }
                $id_pers_lab = $laboratoirePersLab->id_pers_lab;
            } else {
                $id_user_ext = $request->user_externe_id;
            }

            if ($request->type_participant === 'membre') {
                // Debug: afficher les données reçues
                \Log::info('Ajout membre - type_participant: ' . $request->type_participant);
                \Log::info('Ajout membre - membre_id: ' . $request->membre_id);

                // Pour les membres, récupérer l'id_pers_lab depuis laboratoire_pers_lab
                $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('id', $request->membre_id)
                    ->where('code_lab', $code_lab)
                    ->where('statut', 'actif')
                    ->first();

                if (!$affectation) {
                    \Log::error('Affectation non trouvée pour membre_id: ' . $request->membre_id);
                    return back()->with('error', 'Membre non trouvé ou inactif.');
                }

                $id_pers_lab = $affectation->id_pers_lab;
                \Log::info('Ajout membre - id_pers_lab trouvé: ' . $id_pers_lab);
            } else {
                $id_user_ext = $request->user_externe_id;
                \Log::info('Ajout externe - id_user_ext: ' . $id_user_ext);
            }

            // Vérifier si le participant n'est pas déjà dans le projet
            $existing = \App\Models\laboratoires\ParticiperProjet::where('code_projet', $projet->code_projet)
                ->where(function ($query) use ($id_pers_lab, $id_user_ext) {
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

            // Debug: afficher les données avant création
            \Log::info('Création participant avec données:', [
                'code_projet' => $projet->code_projet,
                'id_pers_lab' => $id_pers_lab,
                'id_user_ext' => $id_user_ext,
                'role' => $request->role,
                'debut_participation' => $request->debut_participation,
                'fin_participation' => $request->fin_participation
            ]);

            $participant = \App\Models\laboratoires\ParticiperProjet::create([
                'code_projet' => $projet->code_projet,
                'id_pers_lab' => $id_pers_lab,
                'id_user_ext' => $id_user_ext,
                'role' => $request->role,
                'debut_participation' => $request->debut_participation,
                'fin_participation' => $request->fin_participation
            ]);

            // Debug: vérifier que l'enregistrement a été créé
            if (!$participant) {
                \Log::error('Échec de création du participant');
                return back()->with('error', 'Erreur lors de la création de l\'enregistrement participant.');
            }

            \Log::info('Participant créé avec succès, ID: ' . $participant->id);

            return redirect()->route('laboratoires.admin.projets.participants', [$code_lab, $projet->code_projet])
                ->with('success', 'Participant ajouté avec succès.');
        } catch (Exception $e) {
            \Log::error('Erreur lors de l\'ajout du participant: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
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
            // Debug: afficher les informations de suppression
            \Log::info('Suppression participant:', [
                'code_lab' => $code_lab,
                'code_projet' => $projet->code_projet,
                'participant_id' => $participant
            ]);

            // Vérifier d'abord combien de participants existent
            $totalParticipants = \App\Models\laboratoires\ParticiperProjet::where('code_projet', $projet->code_projet)->count();
            \Log::info('Nombre total de participants avant suppression: ' . $totalParticipants);

            // Supprimer le participant par son ID dans la table participer_projet
            $participantRecord = \App\Models\laboratoires\ParticiperProjet::where('code_projet', $projet->code_projet)
                ->where('id', $participant)
                ->first();

            if (!$participantRecord) {
                \Log::error('Participant non trouvé avec ID: ' . $participant);
                return back()->with('error', 'Participant non trouvé.');
            }

            \Log::info('Participant trouvé:', [
                'id' => $participantRecord->id,
                'id_pers_lab' => $participantRecord->id_pers_lab,
                'id_user_ext' => $participantRecord->id_user_ext,
                'role' => $participantRecord->role
            ]);

            $participantRecord->delete();

            // Vérifier combien de participants restent
            $participantsRestants = \App\Models\laboratoires\ParticiperProjet::where('code_projet', $projet->code_projet)->count();
            \Log::info('Nombre de participants après suppression: ' . $participantsRestants);

            return redirect()->route('laboratoires.admin.projets.participants', [$code_lab, $projet->code_projet])
                ->with('success', 'Participant retiré avec succès.');
        } catch (Exception $e) {
            \Log::error('Erreur lors de la suppression: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
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

        $documents = $projet->docs()->orderByDesc('created_at')->paginate(20);

        return view('laboratoires.admin.projets.documents', compact('laboratoire', 'projet', 'documents'));
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
            'path' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif|max:10240'
        ]);

        try {
            $path = $request->file('path')->store('documents_projets', 'public');

            \App\Models\laboratoires\DocProjetLabo::create([
                'code_projet' => $projet->code_projet,
                'titre_doc' => $request->titre_doc,
                'description_doc' => $request->description_doc,
                'path' => $path
            ]);

            return redirect()->route('laboratoires.admin.projets.documents', [$code_lab, $projet->code_projet])
                ->with('success', 'Document ajouté avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    public function projetDocumentsDestroy($code_lab, $projet, $document)
    {
        // Récupérer le document par id_doc et code_projet
        $doc = \App\Models\laboratoires\DocProjetLabo::where('id_doc', $document)
            ->where('code_projet', $projet)
            ->firstOrFail();

        // Supprimer le fichier physique si présent
        if ($doc->path && \Storage::disk('public')->exists($doc->path)) {
            \Storage::disk('public')->delete($doc->path);
            }

            $doc->delete();

        return back()->with('success', 'Document supprimé avec succès.');
        }

    public function projetDocumentsUpdate(Request $request, $code_lab, $projet, $document)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $projet = \App\Models\laboratoires\ProjetLabo::where('code_lab', $code_lab)
            ->where('code_projet', $projet)
            ->firstOrFail();
        $doc = \App\Models\laboratoires\DocProjetLabo::where('id_doc', $document)
            ->where('code_projet', $projet->code_projet)
                ->firstOrFail();

        $request->validate([
            'titre_doc' => 'required|string|max:255',
            'path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif|max:10240'
        ]);

        try {
            $data = [
                'titre_doc' => $request->titre_doc,
            ];
            if ($request->hasFile('path')) {
                // Supprimer l'ancien fichier
                if ($doc->path && \Storage::disk('public')->exists($doc->path)) {
                    \Storage::disk('public')->delete($doc->path);
                }
                $data['path'] = $request->file('path')->store('documents_projets', 'public');
            }
            $doc->update($data);

            return redirect()->route('laboratoires.admin.projets.documents', [$code_lab, $projet->code_projet])
                ->with('success', 'Document modifié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function equipements($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Filtres
        $etat = $request->input('etat');
        $search = $request->input('search');
        $localisation = $request->input('localisation');

        $query = Equipements::where('code_lab', $code_lab)
            ->with(['laboratoire', 'entretiens', 'reservations']);

        if ($etat) {
            $query->where('etat', $etat);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
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
            'total' => Equipements::where('code_lab', $code_lab)->count(),
            'disponible' => Equipements::where('code_lab', $code_lab)->where('etat', 'disponible')->count(),
            'maintenance' => Equipements::where('code_lab', $code_lab)->where('etat', 'en maintenance')->count(),
            'hors_service' => Equipements::where('code_lab', $code_lab)->where('etat', 'hors service')->count(),
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
            Equipements::create([
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
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    public function equipementShow($code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->with(['laboratoire', 'entretiens.personnel', 'reservations.personnel'])
            ->firstOrFail();

        return view('laboratoires.admin.equipements.show', compact('laboratoire', 'equipement'));
    }

    public function equipementEdit($code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        return view('laboratoires.admin.equipements.edit', compact('laboratoire', 'equipement'));
    }

    public function equipementUpdate(Request $request, $code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
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
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function equipementDestroy(Request $request, $code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
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
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    // Gestion des entretiens
    public function equipementEntretiens($code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->with(['entretiens.personnel'])
            ->firstOrFail();

        $entretiens = $equipement->entretiens()->with('personnel')->orderByDesc('debut_entretien')->get();
        $personnel = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with(['persLab'])
            ->get();
        $externes = \App\Models\laboratoires\UserExterne::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->get();

        return view('laboratoires.admin.equipements.entretiens', compact('laboratoire', 'equipement', 'personnel', 'entretiens', 'externes'));
    }

    public function equipementEntretienStore(Request $request, $code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        $request->validate([
            'participant_type' => 'required|in:interne,externe',
            'id_pers_lab' => 'nullable|exists:laboratoire_pers_lab,id',
            'id_user_ext' => 'nullable|exists:user_externe,id_user_ext',
            'type_entretien' => 'required|in:entretien,reparation',
            'debut_entretien' => 'required|date',
            'fin_entretien' => 'required|date|after:debut_entretien',
            'desc_entretien' => 'nullable|string',
            'cout' => 'nullable|numeric|min:0'
        ]);

        // Validation logique
        if ($request->participant_type === 'interne' && !$request->id_pers_lab) {
            return back()->with('error', 'Veuillez sélectionner un membre interne.');
        }
        if ($request->participant_type === 'externe' && !$request->id_user_ext) {
            return back()->with('error', 'Veuillez sélectionner un user externe.');
        }
        if ($request->id_pers_lab && $request->id_user_ext) {
            return back()->with('error', 'Un seul type de participant doit être sélectionné.');
        }

        try {
            $entretienEnCours = $equipement->getEntretienEnCours();
            if ($entretienEnCours) {
                return back()->with('error', 'Cet équipement a déjà un entretien en cours.');
            }
            if ($equipement->etat === 'hors service') {
                return back()->with('error', 'Cet équipement est hors service et ne peut pas être entretenu.');
            }
            \App\Models\laboratoires\EntretienReparation::create([
                'code_equip' => $equipement->code_equip,
                'id_pers_lab' => $request->participant_type === 'interne' ? $request->id_pers_lab : null,
                'id_user_ext' => $request->participant_type === 'externe' ? $request->id_user_ext : null,
                'statut_entretien' => 'En cours',
                'debut_entretien' => $request->debut_entretien,
                'fin_entretien' => $request->fin_entretien,
                'type_entretien' => $request->type_entretien,
                'desc_entretien' => $request->desc_entretien,
                'cout' => $request->cout
            ]);
            $equipement->update(['etat' => 'en maintenance']);
            // Après la création d'un entretien
            $equipement->updateEtatAutomatique();
            return redirect()->route('laboratoires.admin.equipements.entretiens', [$code_lab, $equipement->code_equip])
                ->with('success', 'Entretien programmé avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la programmation : ' . $e->getMessage());
        }
    }

    public function equipementEntretienUpdate(Request $request, $code_lab, $equipement, $entretien)
    {
        if (!$this->peutValider($code_lab)) {
            abort(403, 'Vous n\'êtes pas autorisé à valider ou refuser un entretien.');
        }
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        // Chercher l'entretien par son ID (pas par code_equip/id_pers_lab)
        $entretien = \App\Models\laboratoires\EntretienReparation::where('code_equip', $equipement->code_equip)
            ->where('id', $entretien) // Utiliser l'ID de l'entretien
            ->firstOrFail();

        // Si on change seulement le statut (depuis les boutons d'action)
        if ($request->has('keep_dates') && $request->keep_dates === 'true') {
            $request->validate([
                'statut_entretien' => 'required|in:En cours,Terminé,En pause,Annulé'
            ]);

            try {
                // Mettre à jour seulement le statut
                $entretien->update([
                    'statut_entretien' => $request->statut_entretien
                ]);

                // Mettre à jour l'état de l'équipement selon le statut
                $this->updateEquipementStateAfterEntretien($equipement, $request->statut_entretien);

                return redirect()->route('laboratoires.admin.equipements.entretiens', [$code_lab, $equipement->code_equip])
                    ->with('success', 'Statut de l\'entretien mis à jour avec succès.');

            } catch (Exception $e) {
                return back()->with('error', 'Erreur lors de la mise à jour du statut : ' . $e->getMessage());
            }
        }

        // Sinon, c'est une mise à jour complète (depuis le modal)
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
            $this->updateEquipementStateAfterEntretien($equipement, $request->statut_entretien);

            return redirect()->route('laboratoires.admin.equipements.entretiens', [$code_lab, $equipement->code_equip])
                ->with('success', 'Entretien mis à jour avec succès.');

        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour l'état de l'équipement après modification d'un entretien
     */
    private function updateEquipementStateAfterEntretien($equipement, $nouveauStatut)
    {
        // Si l'entretien est terminé ou annulé
        if (in_array($nouveauStatut, ['Terminé', 'Annulé'])) {
            // Vérifier s'il y a d'autres entretiens en cours
            $autresEntretiensEnCours = \App\Models\laboratoires\EntretienReparation::where('code_equip', $equipement->code_equip)
                ->whereIn('statut_entretien', ['En cours', 'En pause'])
                ->exists();

            if (!$autresEntretiensEnCours) {
                // Vérifier s'il y a des réservations actives
                $reservationsActives = ReservationAgent::where('code_equip', $equipement->code_equip)
                    ->where('statut', 'confirmé')
                    ->where('debut_reserv', '<=', now())
                    ->where('fin_reserv', '>=', now())
                    ->exists();

                if ($reservationsActives) {
                    $equipement->update(['etat' => 'en utilisation']);
                } else {
                    $equipement->update(['etat' => 'disponible']);
                }
            }
        } // Si l'entretien est en cours ou en pause
        elseif (in_array($nouveauStatut, ['En cours', 'En pause'])) {
            $equipement->update(['etat' => 'en maintenance']);
        }
    }

    // Gestion des réservations
    public function equipementReservations($code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->with(['reservations.personnel'])
            ->firstOrFail();

        $reservations = $equipement->reservations()->with('personnel')->orderByDesc('debut_reserv')->get();
        $personnel = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with(['persLab'])
            ->get();
        $externes = \App\Models\laboratoires\UserExterne::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->get();

        return view('laboratoires.admin.equipements.reservations', compact('laboratoire', 'equipement', 'personnel', 'reservations', 'externes'));
    }

    public function equipementReservationStore(Request $request, $code_lab, $equipement)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        $request->validate([
            'participant_type' => 'required|in:interne,externe',
            'id_pers_lab' => 'nullable|exists:laboratoire_pers_lab,id_pers_lab',
            'id_user_ext' => 'nullable|exists:user_externe,id_user_ext',
            'debut_reserv' => 'required|date|after_or_equal:today',
            'fin_reserv' => 'required|date|after:debut_reserv'
        ]);
        if ($request->participant_type === 'interne' && !$request->id_pers_lab) {
            return back()->with('error', 'Veuillez sélectionner un membre interne.');
        }
        if ($request->participant_type === 'externe' && !$request->id_user_ext) {
            return back()->with('error', 'Veuillez sélectionner un user externe.');
        }
        if ($request->id_pers_lab && $request->id_user_ext) {
            return back()->with('error', 'Un seul type de participant doit être sélectionné.');
        }
        try {
            if (!$equipement->isDisponible()) {
                return back()->with('error', 'Cet équipement n\'est pas disponible pour la réservation.');
            }
            $conflit = ReservationAgent::where('code_equip', $equipement->code_equip)
                ->where('statut', 'confirmé')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('debut_reserv', [$request->debut_reserv, $request->fin_reserv])
                        ->orWhereBetween('fin_reserv', [$request->debut_reserv, $request->fin_reserv])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('debut_reserv', '<=', $request->debut_reserv)
                                ->where('fin_reserv', '>=', $request->fin_reserv);
                        });
                })
                ->first();
            if ($conflit) {
                return back()->with('error', 'Il y a un conflit de réservation pour cette période.');
            }
            ReservationAgent::create([
                'code_equip' => $equipement->code_equip,
                'id_pers_lab' => $request->participant_type === 'interne' ? $request->id_pers_lab : null,
                'id_user_ext' => $request->participant_type === 'externe' ? $request->id_user_ext : null,
                'debut_reserv' => $request->debut_reserv,
                'fin_reserv' => $request->fin_reserv,
                'statut' => 'en attente'
            ]);
            // Après la création d'une réservation
            $equipement->updateEtatAutomatique();
            return redirect()->route('laboratoires.admin.equipements.reservations', [$code_lab, $equipement->code_equip])
                ->with('success', 'Demande de réservation créée avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function equipementReservationUpdate(Request $request, $code_lab, $equipement, $reservation)
    {
        if (!$this->peutValider($code_lab)) {
            abort(403, 'Vous n\'êtes pas autorisé à valider ou refuser une réservation.');
        }
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $equipement = Equipements::where('code_lab', $code_lab)
            ->where('code_equip', $equipement)
            ->firstOrFail();

        // Chercher la réservation par son ID (pas par id_pers_lab)
        $reservation = ReservationAgent::where('code_equip', $equipement->code_equip)
            ->where('id', $reservation) // Utiliser l'ID de la réservation
            ->firstOrFail();

        $request->validate([
            'statut' => 'required|in:en attente,confirmé,refusé,annulé'
        ]);

        try {
            $reservation->update(['statut' => $request->statut]);

            // Si la réservation est confirmée et qu'elle est active, mettre à jour l'état de l'équipement
            if ($request->statut === 'confirmé' && $reservation->isActive()) {
                // Vérifier s'il n'y a pas d'autres réservations actives qui se chevauchent
                $conflits = ReservationAgent::where('code_equip', $equipement->code_equip)
                    ->where('id', '!=', $reservation->id)
                    ->where('statut', 'confirmé')
                    ->where(function ($query) use ($reservation) {
                        $query->whereBetween('debut_reserv', [$reservation->debut_reserv, $reservation->fin_reserv])
                            ->orWhereBetween('fin_reserv', [$reservation->debut_reserv, $reservation->fin_reserv])
                            ->orWhere(function ($q) use ($reservation) {
                                $q->where('debut_reserv', '<=', $reservation->debut_reserv)
                                    ->where('fin_reserv', '>=', $reservation->fin_reserv);
                            });
                    })
                    ->exists();

                if ($conflits) {
                    // Annuler la confirmation s'il y a un conflit
                    $reservation->update(['statut' => 'en attente']);
                    return back()->with('error', 'Il y a un conflit avec une autre réservation confirmée pour cette période.');
                }

                // Mettre à jour l'état de l'équipement si la réservation est pour maintenant
                if ($reservation->debut_reserv <= now() && $reservation->fin_reserv >= now()) {
                    $equipement->update(['etat' => 'en utilisation']);
                }
            } elseif ($request->statut === 'refusé' || $request->statut === 'annulé') {
                // Vérifier s'il y a d'autres réservations actives en cours
                $autresReservationsActives = ReservationAgent::where('code_equip', $equipement->code_equip)
                    ->where('statut', 'confirmé')
                    ->where('id', '!=', $reservation->id)
                    ->where('debut_reserv', '<=', now())
                    ->where('fin_reserv', '>=', now())
                    ->exists();

                if (!$autresReservationsActives && $equipement->etat === 'en utilisation') {
                    $equipement->update(['etat' => 'disponible']);
                }
            }

            return redirect()->route('laboratoires.admin.equipements.reservations', [$code_lab, $equipement->code_equip])
                ->with('success', 'Statut de la réservation mis à jour avec succès.');
        } catch (Exception $e) {
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
            $query->where(function ($q) use ($search) {
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

            // Mettre à jour pers_lab uniquement si un enregistrement existe
            $persLab = \App\Models\laboratoires\PersLab::where('id_pers_lab', $candidature->id_user_ext)->first();
            if ($persLab) {
                $persLab->update(['statut' => 'actif']);
            }

            // TODO: Envoyer un email avec les identifiants
            // Mail::to($candidature->email_user_ext)->send(new CandidatureApprovedMail($candidature, $tempPassword));

            return redirect()->route('laboratoires.admin.candidatures', $code_lab)
                ->with('success', 'Candidature approuvée avec succès. Un email a été envoyé au candidat.');
        } catch (Exception $e) {
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
            $persLab = \App\Models\laboratoires\PersLab::where('id_pers_lab', $candidature->id_user_ext)->first();
            if ($persLab) {
                $persLab->update(['statut' => 'rejeté']);
            }
            // TODO: Envoyer un email de rejet
            // Mail::to($candidature->email_user_ext)->send(new CandidatureRejectedMail($candidature));

            return redirect()->route('laboratoires.admin.candidatures', $code_lab)
                ->with('success', 'Candidature rejetée avec succès. Un email a été envoyé au candidat.');
        } catch (Exception $e) {
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
            $query->where(function ($q) use ($search) {
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

            // Créer l'affectation (pas d'entrée dans pers_lab pour les externes)
            LaboratoirePersLab::create([
                'code_lab' => $code_lab,
                'id_pers_lab' => null, // Les externes n'ont pas d'entrée dans pers_lab
                'id_user_externe' => $userExterne->id_user_ext,
                'id_rl' => $request->id_rl,
                'date_affectation' => $request->date_debut,
                'date_fin_affectation' => $request->date_fin,
                'statut' => $request->statut
            ]);

            // Envoyer l'email de confirmation
            $emailSent = true;
            $emailErrorMessage = '';

            // Validate email format more strictly before sending
            if (!filter_var($userExterne->email_user_ext, FILTER_VALIDATE_EMAIL)) {
                $emailSent = false;
                $emailErrorMessage = 'Adresse email invalide.';
                \Illuminate\Support\Facades\Log::error('Email confirmation externe: adresse email invalide pour ' . $userExterne->email_user_ext);
            } else {
                try {
                    \Illuminate\Support\Facades\Mail::to($userExterne->email_user_ext)
                        ->send(new ExterneConfirmationMail($userExterne, $laboratoire, $tempPassword));
                } catch (Exception $e) {
                    $emailSent = false;
                    $emailErrorMessage = $e->getMessage();
                    // Log l'erreur d'envoi d'email mais ne pas faire échouer la création
                    \Illuminate\Support\Facades\Log::error('Erreur envoi email confirmation externe: ' . $emailErrorMessage);
                }
            }

            $successMessage = 'Utilisateur externe créé avec succès.';
            if ($emailSent) {
                $successMessage .= ' Un email de confirmation avec le mot de passe a été envoyé à ' . $userExterne->email_user_ext;
            } else {
                $successMessage .= ' Cependant, l\'envoi de l\'email a échoué : ' . $emailErrorMessage . '. Veuillez vérifier l\'adresse email du destinataire et la configuration du serveur SMTP.';
            }

            return redirect()->route('laboratoires.admin.externes', $code_lab)
                ->with('success', $successMessage);
        } catch (Exception $e) {
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
        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
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
        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
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

            // Mettre à jour l'affectation (pas de pers_lab pour les externes)
            LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('id_user_externe', $externe->id_user_ext)
                ->update([
                    'id_rl' => $request->id_rl,
                    'date_affectation' => $request->date_debut,
                    'date_fin_affectation' => $request->date_fin,
                    'statut' => $request->statut
                ]);

            return redirect()->route('laboratoires.admin.externes', $code_lab)
                ->with('success', 'Utilisateur externe mis à jour avec succès.');
        } catch (Exception $e) {
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
            LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('id_user_externe', $externe->id_user_ext)
                ->delete();

            // Supprimer de pers_lab
            // Removed deletion from pers_lab as tables are independent to avoid SQL error
            // \App\Models\laboratoires\PersLab::whereRaw('BINARY `id_pers_lab` = ?', [$externe->id_user_ext])
            //     ->delete();

            // Supprimer l'utilisateur externe
            $externe->delete();

            return redirect()->route('laboratoires.admin.externes', $code_lab)
                ->with('success', 'Utilisateur externe supprimé avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur externe
     */
    public function externeResetPassword(Request $request, $code_lab, $externe)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $externe = UserExterne::where('code_lab', $code_lab)
            ->where('id_user_ext', $externe)
            ->firstOrFail();

        try {
            // Générer un nouveau mot de passe temporaire
            $newPassword = Str::random(8);

            // Mettre à jour le mot de passe
            $externe->update([
                'pwd' => \Illuminate\Support\Facades\Hash::make($newPassword)
            ]);

            // Envoyer l'email de réinitialisation
            try {
                Mail::to($externe->email_user_ext)
                    ->send(new ExternePasswordResetMail($externe, $laboratoire, $newPassword));
            } catch (Exception $e) {
                // Log l'erreur d'envoi d'email mais ne pas faire échouer la réinitialisation
                \Illuminate\Support\Facades\Log::error('Erreur envoi email réinitialisation mot de passe externe: ' . $e->getMessage());
            }

            return redirect()->route('laboratoires.admin.externes.show', [$code_lab, $externe->id_user_ext])
                ->with('success', 'Mot de passe réinitialisé avec succès. Un email avec le nouveau mot de passe a été envoyé à ' . $externe->email_user_ext);
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la réinitialisation du mot de passe : ' . $e->getMessage());
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
        $statsUtilisation = ReservationAgent::whereHas('equipement', function ($q) use ($code_lab) {
            $q->where('code_lab', $code_lab);
        })
            ->where('debut_reserv', '>=', $dateDebut)
            ->with('equipement')
            ->get()
            ->groupBy('code_equip')
            ->map(function ($reservations) {
                return [
                    'total_heures' => $reservations->sum(function ($r) {
                        return \Carbon\Carbon::parse($r->debut_reserv)->diffInHours($r->fin_reserv);
                    }),
                    'nombre_reservations' => $reservations->count(),
                    'taux_utilisation' => $reservations->where('statut', 'confirmé')->count() / max($reservations->count(), 1) * 100
                ];
            });

        // Équipements les plus utilisés
        $equipementsPopulaires = Equipements::where('code_lab', $code_lab)
            ->withCount(['reservations' => function ($q) use ($dateDebut) {
                $q->where('debut_reserv', '>=', $dateDebut);
            }])
            ->orderBy('reservations_count', 'desc')
            ->get();

        // Équipements sous-utilisés
        $equipementsSousUtilises = Equipements::where('code_lab', $code_lab)
            ->whereDoesntHave('reservations', function ($q) use ($dateDebut) {
                $q->where('debut_reserv', '>=', $dateDebut);
            })
            ->get();

        // Statistiques par période (pour graphiques)
        $statsParPeriode = ReservationAgent::whereHas('equipement', function ($q) use ($code_lab) {
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

        // Récupérer les rapports existants
        $rapports = RapportLabo::where('code_lab', $code_lab)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laboratoires.admin.reporting', compact('laboratoire', 'rapports'));
    }

    /**
     * Afficher la liste des rapports personnalisés
     */
    public function rapports($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        $rapports = RapportLabo::where('code_lab', $code_lab)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('laboratoires.admin.rapports.index', compact('laboratoire', 'rapports'));
    }

    /**
     * Afficher le formulaire de création de rapport
     */
    public function rapportCreate($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        return view('laboratoires.admin.rapports.create', compact('laboratoire'));
    }

    /**
     * Enregistrer un nouveau rapport
     */
    public function rapportStore(Request $request, $code_lab)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'type_rapport' => 'required|in:pdf,word',
            'description' => 'nullable|string'
        ]);

        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Générer un code unique pour le rapport
        $code_rl = 'RL' . strtoupper(Str::random(8));

        // Créer le rapport
        $rapport = RapportLabo::create([
            'code_rl' => $code_rl,
            'path_rl' => '', // Sera mis à jour après génération
            'desc_rapport' => $request->description,
            'code_lab' => $code_lab
        ]);

        // Générer le fichier selon le type
        if ($request->type_rapport === 'pdf') {
            $this->generateCustomPDF($rapport, $request->titre, $request->contenu, $laboratoire);
        } else {
            $this->generateCustomWord($rapport, $request->titre, $request->contenu, $laboratoire);
        }

        return redirect()->route('laboratoires.admin.rapports', $code_lab)
            ->with('success', 'Rapport créé et généré avec succès.');
    }

    /**
     * Afficher un rapport
     */
    public function rapportShow($code_lab, $rapport)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $rapport = RapportLabo::where('code_rl', $rapport)->firstOrFail();

        return view('laboratoires.admin.rapports.show', compact('laboratoire', 'rapport'));
    }

    /**
     * Télécharger un rapport
     */
    public function rapportDownload($code_lab, $rapport)
    {
        $rapport = RapportLabo::where('code_rl', $rapport)->firstOrFail();

        $filePath = storage_path('app/' . $rapport->path_rl);
        if (!file_exists($filePath)) {
            return back()->with('error', 'Fichier non trouvé.');
        }

        return response()->download($filePath);
    }

    /**
     * Supprimer un rapport
     */
    public function rapportDestroy(Request $request, $code_lab, $rapport)
    {
        $rapport = RapportLabo::where('code_rl', $rapport)->firstOrFail();

        // Supprimer le fichier physique
        $filePath = storage_path('app/' . $rapport->path_rl);
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }

        $rapport->delete();

        return redirect()->route('laboratoires.admin.rapports', $code_lab)
            ->with('success', 'Rapport supprimé avec succès.');
    }

    /**
     * Générer un rapport PDF personnalisé
     */
    private function generateCustomPDF($rapport, $titre, $contenu, $laboratoire)
    {
        $data = [
            'titre' => $titre,
            'contenu' => $contenu,
            'laboratoire' => $laboratoire,
            'date_generation' => Carbon::now()->format('d/m/Y H:i'),
            'rapport' => $rapport
        ];

        $pdf = \PDF::loadView('laboratoires.admin.rapports.template-pdf', $data);

        $filename = "rapport_{$rapport->code_rl}_" . Carbon::now()->format('Y-m-d_H-i-s') . ".pdf";
        $path = "private/rapports/{$laboratoire->code_lab}/" . $filename;

        // Créer le dossier s'il n'existe pas
        \Storage::makeDirectory("private/rapports/{$laboratoire->code_lab}");

        // Sauvegarder le PDF
        \Storage::put($path, $pdf->output());

        // Mettre à jour le chemin dans la base de données
        $rapport->update(['path_rl' => $path]);
    }

    /**
     * Générer un rapport Word personnalisé
     */
    private function generateCustomWord($rapport, $titre, $contenu, $laboratoire)
    {
        $data = [
            'titre' => $titre,
            'contenu' => $contenu,
            'laboratoire' => $laboratoire,
            'date_generation' => Carbon::now()->format('d/m/Y H:i'),
            'rapport' => $rapport
        ];

        $filename = "rapport_{$rapport->code_rl}_" . Carbon::now()->format('Y-m-d_H-i-s') . ".docx";
        $path = "private/rapports/{$laboratoire->code_lab}/" . $filename;

        // Créer le dossier s'il n'existe pas
        $dirPath = storage_path("app/private/rapports/{$laboratoire->code_lab}");
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        // Utiliser PhpWord pour générer le document Word
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Créer une section
        $section = $phpWord->addSection();

        // Titre
        $section->addText($titre, ['bold' => true, 'size' => 16]);
        $section->addTextBreak();

        // Informations du laboratoire
        $section->addText("Laboratoire : " . $laboratoire->label_labo, ['bold' => true]);
        $section->addText("Code : " . $laboratoire->code_lab);
        $section->addText("Date de génération : " . $data['date_generation']);
        $section->addTextBreak();

        // Contenu
        $section->addText("Contenu du rapport :", ['bold' => true]);
        $section->addTextBreak();
        $section->addText($contenu);

        // Sauvegarder le document
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('app/' . $path));

        // Mettre à jour le chemin dans la base de données
        $rapport->update(['path_rl' => $path]);
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
                    'reservations' => ReservationAgent::whereHas('equipement', function ($q) use ($code_lab) {
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
                    'reservations' => ReservationAgent::whereHas('equipement', function ($q) use ($code_lab) {
                        $q->where('code_lab', $code_lab);
                    })->count(),
                ];
        }
    }

    // ========================================
    // GESTION DES NOTIFICATIONS ET ALERTES
    // ========================================

    /**
     * Afficher les notifications du laboratoire
     */
    public function notifications($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Filtres
        $type = $request->input('type');
        $lu = $request->input('lu');
        $search = $request->input('search');

        $query = LabNotif::where('code_lab', $code_lab)
            ->with(['expediteur', 'destinataire']);

        if ($type) {
            $query->where('type', $type);
        }
        if ($lu !== null) {
            $query->where('lu', $lu);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                    ->orWhere('message', 'like', "%$search%");
            });
        }

        $notifications = $query->orderByDesc('created_at')->paginate(20);

        // Statistiques des notifications
        $stats = [
            'total' => LabNotif::where('code_lab', $code_lab)->count(),
            'non_lues' => LabNotif::where('code_lab', $code_lab)->where('lu', false)->count(),
            'urgentes' => LabNotif::where('code_lab', $code_lab)->where('type', 'projet_echeance')->count(),
            'maintenance' => LabNotif::where('code_lab', $code_lab)->where('type', 'maintenance_equipement')->count(),
        ];

        return view('laboratoires.admin.notifications.index', compact(
            'laboratoire',
            'notifications',
            'stats',
            'type',
            'lu',
            'search'
        ));
    }

    /**
     * Marquer une notification comme lue
     */
    public function notificationMarkAsRead($code_lab, $notification_id)
    {
        $notification = LabNotif::where('code_lab', $code_lab)
            ->where('id_notif', $notification_id)
            ->firstOrFail();

        $notification->update(['lu' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function notificationsMarkAllAsRead($code_lab)
    {
        LabNotif::where('code_lab', $code_lab)
            ->where('lu', false)
            ->update(['lu' => true]);

        return redirect()->route('laboratoires.admin.notifications', $code_lab)
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Supprimer une notification
     */
    public function notificationDestroy($code_lab, $notification_id)
    {
        $notification = LabNotif::where('code_lab', $code_lab)
            ->where('id_notif', $notification_id)
            ->firstOrFail();

        $notification->delete();

        return redirect()->route('laboratoires.admin.notifications', $code_lab)
            ->with('success', 'Notification supprimée avec succès.');
    }

    /**
     * Afficher les alertes actives du laboratoire
     */
    public function alertes($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $alertService = new LaboratoireAlertService();

        // Obtenir les statistiques des alertes
        $alertStats = $alertService->getAlertStats($code_lab);

        // Projets en échéance urgente (7 jours)
        $projetsUrgents = ProjetLabo::where('code_lab', $code_lab)
            ->where('fin_projet', '>=', now())
            ->where('fin_projet', '<=', now()->addDays(7))
            ->where('statut_projet', '!=', 'Terminé')
            ->get();

        // Projets en échéance importante (30 jours)
        $projetsImportants = ProjetLabo::where('code_lab', $code_lab)
            ->where('fin_projet', '>=', now())
            ->where('fin_projet', '<=', now()->addDays(30))
            ->where('fin_projet', '>', now()->addDays(7))
            ->where('statut_projet', '!=', 'Terminé')
            ->get();

        // Maintenances d'équipements urgentes (3 jours)
        $maintenancesUrgentes = \App\Models\laboratoires\EntretienReparation::whereHas('equipement', function ($query) use ($code_lab) {
            $query->where('code_lab', $code_lab);
        })
            ->where('debut_entretien', '>=', now())
            ->where('debut_entretien', '<=', now()->addDays(3))
            ->where('statut_entretien', '!=', 'Annulé')
            ->with('equipement')
            ->get();

        // Maintenances d'équipements importantes (30 jours)
        $maintenancesImportantes = \App\Models\laboratoires\EntretienReparation::whereHas('equipement', function ($query) use ($code_lab) {
            $query->where('code_lab', $code_lab);
        })
            ->where('debut_entretien', '>=', now())
            ->where('debut_entretien', '<=', now()->addDays(30))
            ->where('debut_entretien', '>', now()->addDays(3))
            ->where('statut_entretien', '!=', 'Annulé')
            ->with('equipement')
            ->get();

        return view('laboratoires.admin.alertes.index', compact(
            'laboratoire',
            'alertStats',
            'projetsUrgents',
            'projetsImportants',
            'maintenancesUrgentes',
            'maintenancesImportantes'
        ));
    }

    /**
     * Exécuter manuellement les vérifications d'alertes
     */
    public function runAlertChecks($code_lab)
    {
        $alertService = new LaboratoireAlertService();
        $alertService->runAllChecks();

        return redirect()->route('laboratoires.admin.alertes', $code_lab)
            ->with('success', 'Vérifications d\'alertes exécutées avec succès.');
    }

    /**
     * Obtenir les notifications non lues pour l'API (AJAX)
     */
    public function getUnreadNotifications($code_lab)
    {
        $notifications = LabNotif::where('code_lab', $code_lab)
            ->where('lu', false)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Obtenir le nombre de notifications non lues pour l'API (AJAX)
     */
    public function getUnreadNotificationsCount($code_lab)
    {
        $count = LabNotif::where('code_lab', $code_lab)
            ->where('lu', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function rapportView($code_lab, $rapport)
    {
        $rapport = RapportLabo::where('code_rl', $rapport)->firstOrFail();
        $filePath = storage_path('app/' . $rapport->path_rl);

        if (!file_exists($filePath)) {
            abort(404, 'Fichier non trouvé.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    /**
     * Afficher les annonces du laboratoire (notifications)
     */
    public function annonces($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $annonces = LaboAnnonce::where('code_lab', $code_lab)->orderByDesc('created_at')->get();
        $isAdmin = false;
        if (session('user_id') && session('laboratoire_code') === $code_lab && session('user_type') === 'personnel') {
            $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('id_pers_lab', session('user_id'))
                ->where('statut', 'actif')
                ->where('date_affectation', '<=', now())
                ->where(function ($query) {
                    $query->whereNull('date_fin_affectation')
                          ->orWhere('date_fin_affectation', '>=', now());
                })
                ->with('roleLabo')
                ->first();
            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }
        return view('laboratoires.admin.annonces.index', compact('laboratoire', 'annonces', 'isAdmin'));
    }

    /**
     * Poster une annonce (admin)
     */
    public function storeAnnonce(Request $request, $code_lab)
    {
        $request->validate([
            'contenu' => 'required|string|max:2000',
            'titre' => 'nullable|string|max:255',
            'fichier' => 'nullable|file|max:5120', // 5 Mo max
        ]);
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        // Vérifier que l'utilisateur est admin
        $isAdmin = false;
        if (session('user_id') && session('laboratoire_code') === $code_lab && session('user_type') === 'personnel') {
            $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('id_pers_lab', session('user_id'))
                ->where('statut', 'actif')
                ->where('date_affectation', '<=', now())
                ->where(function ($query) {
                    $query->whereNull('date_fin_affectation')
                          ->orWhere('date_fin_affectation', '>=', now());
                })
                ->with('roleLabo')
                ->first();
            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }
        if (!$isAdmin) {
            abort(403, 'Seul l\'administrateur du laboratoire peut envoyer une annonce.');
        }
        $fichier = null;
        if ($request->hasFile('fichier')) {
            // Correction : on stocke uniquement dans 'private/annonces' sans préfixer deux fois
            $fichier = $request->file('fichier')->store('annonces', 'local');
        }
        LaboAnnonce::create([
            'code_lab' => $code_lab,
            'id_admin' => session('user_id'),
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'fichier' => $fichier
        ]);
        return redirect()->route('laboratoires.admin.annonces', $code_lab)->with('success', 'Annonce envoyée à tous les membres du laboratoire.');
    }

    /**
     * Supprimer une annonce (admin)
     */
    public function deleteAnnonce($code_lab, $id)
    {
        $annonce = LaboAnnonce::where('id', $id)->where('code_lab', $code_lab)->firstOrFail();
        // Vérifier que l'utilisateur est admin
        $isAdmin = false;
        if (session('user_id') && session('laboratoire_code') === $code_lab && session('user_type') === 'personnel') {
            $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('id_pers_lab', session('user_id'))
                ->where('statut', 'actif')
                ->where('date_affectation', '<=', now())
                ->where(function ($query) {
                    $query->whereNull('date_fin_affectation')
                          ->orWhere('date_fin_affectation', '>=', now());
                })
                ->with('roleLabo')
                ->first();
            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }
        if (!$isAdmin) {
            abort(403, 'Seul l\'administrateur du laboratoire peut supprimer une annonce.');
        }
        // Supprimer le fichier joint si existe
        if ($annonce->fichier && \Storage::disk('local')->exists($annonce->fichier)) {
            \Storage::disk('local')->delete($annonce->fichier);
        }
        $annonce->delete();
        return redirect()->route('laboratoires.admin.annonces', $code_lab)->with('success', 'Annonce supprimée.');
    }

    /**
     * Télécharger le fichier joint d'une annonce
     */
    public function downloadAnnonceFile($code_lab, $id)
    {
        $annonce = \App\Models\LaboAnnonce::where('id', $id)->where('code_lab', $code_lab)->firstOrFail();
        $chemin = storage_path('app/private/' . $annonce->fichier);
        if (!is_file($chemin)) {
            return response()->view('errors.fichier_annonce_introuvable', [
                'message' => 'Le fichier joint de cette annonce est introuvable ou a été supprimé.',
                'annonce' => $annonce,
                'laboratoire' => $annonce->laboratoire
            ], 404);
        }
        return response()->file($chemin, [
            'Content-Type' => mime_content_type($chemin),
            'Content-Disposition' => 'inline; filename="' . basename($chemin) . '"'
        ]);
    }

    // === Publications du laboratoire ===
    public function publications($code_lab, Request $request)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $query = Publication::where('code_lab', $code_lab)->with(['createur.personnel', 'createur.user', 'createur']);
        // Filtres
        if ($request->filled('type')) {
            $query->where('type_publi', $request->type);
        }
        if ($request->filled('domaine')) {
            $query->where('domaine', 'like', '%' . $request->domaine . '%');
        }
        if ($request->filled('annee')) {
            $query->whereYear('created_at', $request->annee);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre_publi', 'like', "%$search%")
                    ->orWhere('domaine', 'like', "%$search%")
                    ->orWhere('tags', 'like', "%$search%")
                    ->orWhere('reference', 'like', "%$search%");
            });
        }
        $publications = $query->orderBy('created_at', 'desc')->paginate(10);
        // Statistiques
        $stats = [
            'total' => Publication::where('code_lab', $code_lab)->count(),
            'par_type' => Publication::where('code_lab', $code_lab)->selectRaw('type_publi, COUNT(*) as total')->groupBy('type_publi')->pluck('total', 'type_publi')->toArray(),
            'par_annee' => Publication::where('code_lab', $code_lab)->selectRaw('YEAR(created_at) as annee, COUNT(*) as total')->groupBy('annee')->orderBy('annee', 'desc')->pluck('total', 'annee')->toArray(),
        ];
        $types = ['article', 'conference', 'livre', 'rapport', 'these'];
        $annees = Publication::where('code_lab', $code_lab)->selectRaw('YEAR(created_at) as annee')->distinct()->orderBy('annee', 'desc')->pluck('annee');
        return view('laboratoires.admin.publications.index', compact('publications', 'laboratoire', 'stats', 'types', 'annees', 'request'));
    }

    public function publicationCreate($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        return view('laboratoires.admin.publications.create', compact('laboratoire'));
    }

    public function publicationStore(Request $request, $code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $validated = $request->validate([
            'titre_publi' => 'required|max:255',
            'type_publi' => 'required|in:article,conference,livre,rapport,these',
            'domaine' => 'nullable|max:100',
            'resume' => 'nullable',
            'tags' => 'nullable|string',
            'reference' => 'nullable|string',
            'rapport' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);
        if ($request->hasFile('rapport')) {
            $rapportPath = $request->file('rapport')->store('publications/rapports', 'public');
            $validated['rapport_path'] = $rapportPath;
        }
        $userId = session('user_id');
        $userType = session('user_type');
        if (!$userId || !$userType) {
            return back()->withInput()->with('error', 'Vous devez être connecté pour créer une publication.');
        }
        $validated['id_pers_lab'] = $userId;
        $validated['code_lab'] = $code_lab;
        Publication::create($validated);
        return redirect()->route('laboratoires.admin.publications.index', $code_lab)
            ->with('success', 'Publication ajoutée avec succès.');
    }

    public function publicationShow($code_lab, $publication)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $userId = session('user_id');
        $userType = session('user_type');
        $publication = Publication::with(['createur', 'laboratoire'])
            ->where('code_lab', $code_lab)
            ->where('code_publi', $publication)
            ->firstOrFail();
        // Vérifier que l'utilisateur est bien membre du laboratoire
        $isMembre = false;
        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->where(function ($q) use ($userId, $userType) {
                if ($userType === 'externe') {
                    $q->where('id_user_externe', $userId);
                } else {
                    $q->where('id_pers_lab', $userId);
                }
            })
            ->first();
        if ($affectation) {
            $isMembre = true;
        }
        if (!$isMembre) {
            abort(403, 'Vous devez être membre du laboratoire pour consulter cette publication.');
        }
        // Plus de restriction sur l'auteur ou l'admin pour la consultation
        return view('laboratoires.admin.publications.show', compact('publication', 'laboratoire'));
    }

    public function publicationEdit($code_lab, $publication)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $userId = session('user_id');
        $userType = session('user_type');
        $publication = Publication::with(['createur', 'laboratoire'])
            ->where('code_lab', $code_lab)
            ->where('code_publi', $publication)
            ->firstOrFail();
        $isAdmin = false;
        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->where(function ($q) use ($userId, $userType) {
                if ($userType === 'externe') {
                    $q->where('id_user_externe', $userId);
                } else {
                    $q->where('id_pers_lab', $userId);
                }
            })
            ->with('roleLabo')
            ->first();
        if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
            $isAdmin = true;
        }
        // Seul l'auteur ou l'admin peut modifier
        if ($publication->id_pers_lab != $userId) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette publication.');
        }
        return view('laboratoires.admin.publications.edit', compact('publication', 'laboratoire'));
    }

    public function publicationUpdate(Request $request, $code_lab, $publication)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $userId = session('user_id');
        $userType = session('user_type');
        $publication = Publication::where('code_lab', $code_lab)
            ->where('code_publi', $publication)
            ->firstOrFail();
        $isAdmin = false;
        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->where(function ($q) use ($userId, $userType) {
                if ($userType === 'externe') {
                    $q->where('id_user_externe', $userId);
                } else {
                    $q->where('id_pers_lab', $userId);
                }
            })
            ->with('roleLabo')
            ->first();
        if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
            $isAdmin = true;
        }
        // Seul l'auteur ou l'admin peut modifier
        if ($publication->id_pers_lab != $userId) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette publication.');
        }
        $validated = $request->validate([
            'titre_publi' => 'required|max:255',
            'type_publi' => 'required|in:article,conference,livre,rapport,these',
            'domaine' => 'nullable|max:100',
            'resume' => 'nullable',
            'tags' => 'nullable|string',
            'reference' => 'nullable|string',
            'rapport' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);
        if ($request->hasFile('rapport')) {
            if ($publication->rapport_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($publication->rapport_path);
            }
            $rapportPath = $request->file('rapport')->store('publications/rapports', 'public');
            $validated['rapport_path'] = $rapportPath;
        }
        $publication->update($validated);
        return redirect()->route('laboratoires.admin.publications.show', [$code_lab, $publication->code_publi])
            ->with('success', 'Publication mise à jour avec succès.');
    }

    public function publicationDestroy($code_lab, $publication)
    {
        $userId = session('user_id');
        $userType = session('user_type');
        $publication = Publication::where('code_lab', $code_lab)
            ->where('code_publi', $publication)
            ->firstOrFail();
        $isAdmin = false;
        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->where(function ($q) use ($userId, $userType) {
                if ($userType === 'externe') {
                    $q->where('id_user_externe', $userId);
                } else {
                    $q->where('id_pers_lab', $userId);
                }
            })
            ->with('roleLabo')
            ->first();
        if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
            $isAdmin = true;
        }
        // Seul l'auteur ou l'admin peut supprimer
        if ($publication->id_pers_lab != $userId) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette publication.');
        }
        $publication->delete();
        return redirect()->route('laboratoires.admin.publications.index', $code_lab)
            ->with('success', 'Publication supprimée avec succès.');
    }

    /**
     * Vérifie si l'utilisateur connecté a le droit de valider (admin, technicien, chef de projet)
     */
    private function peutValider($code_lab)
    {
        $userId = session('user_id');
        $userType = session('user_type');
        $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->where(function ($q) use ($userId, $userType) {
                if ($userType === 'externe') {
                    $q->where('id_user_externe', $userId);
                } else {
                    $q->where('id_pers_lab', $userId);
                }
            })
            ->with('roleLabo')
            ->first();
        if (!$affectation || !$affectation->roleLabo) return false;
        $role = strtolower($affectation->roleLabo->lib_rl);
        return in_array($role, ['admin', 'technicien', 'chef de projet', 'chef_projet', 'chef-projet']);
    }

    /**
     * Affiche tous les entretiens du laboratoire
     */
    public function tousLesEntretiens($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $entretiens = \App\Models\laboratoires\EntretienReparation::whereHas('equipement', function($q) use ($code_lab) {
            $q->where('code_lab', $code_lab);
        })->with(['equipement', 'personnel.persLab', 'personnel.userExterne'])->orderByDesc('debut_entretien')->get();
        return view('laboratoires.admin.equipements.entretiens_all', compact('laboratoire', 'entretiens'));
    }

    /**
     * Affiche toutes les réservations du laboratoire
     */
    public function toutesLesReservations($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $reservations = \App\Models\laboratoires\ReservationAgent::whereHas('equipement', function($q) use ($code_lab) {
            $q->where('code_lab', $code_lab);
        })->with(['equipement', 'personnel.persLab', 'personnel.userExterne', 'userExterne'])->orderByDesc('debut_reserv')->get();
        return view('laboratoires.admin.equipements.reservations_all', compact('laboratoire', 'reservations'));
    }
}
