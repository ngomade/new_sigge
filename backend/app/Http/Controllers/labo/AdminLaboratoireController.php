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
                'id_user_ext' => $userExterne->id_user_ext,
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
            ->where('id_user_ext', $externe->id_user_ext)
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
            ->where('id_user_ext', $externe->id_user_ext)
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
