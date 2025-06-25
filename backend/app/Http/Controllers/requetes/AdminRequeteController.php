<?php

namespace App\Http\Controllers\requetes;

use App\Http\Controllers\Controller;
use App\Mail\requetes\RequeteAssignedMail;
use App\Mail\requetes\RequeteResponseMail;
use App\Mail\requetes\RequeteStatusChangeMail;
use App\Models\Bureau;
use App\Models\requetes\Category;
use App\Models\requetes\Reponse;
use App\Models\requetes\Requete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\PersRole;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class AdminRequeteController extends Controller
{
    /**
     * Display a listing of all requests for admin
     */
    public function index(Request $request)
{
    $query = Requete::with(['category', 'user', 'bureau']);
    
    // Récupération du personnel en session
    $personnel = session('pers');
    
    // // Vérification que l'utilisateur est connecté
    // if (!$personnel) {
    //     return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
    // }
    
    // Vérification des permissions d'accès
    $userRoles = $personnel->getRoleNames()->toArray();
    
    // Vérification des rôles autorisés (ADMIN etc.)
    $rolesAutorises = ['ADMIN', 'CHEF_SERV', 'CHEF_DEPT', 'CHEF_DIV', 'PERSONNEL_APPUI', 'ENSEIGNANT']; // Ajustez selon vos besoins
    if (!array_intersect($userRoles, $rolesAutorises)) {
        abort(403, 'Accès non autorisé. Vous n\'avez pas les privilèges nécessaires.');
    }

    // Tous les utilisateurs (sauf ADMIN) ne voient que les requêtes de leur(s) bureau(x)
    if (!in_array('ADMIN', $userRoles)) {
        $userBureaux = $this->getUserBureaux();
        $codesBureaux = $userBureaux->pluck('code_bureau')->toArray();
        if (!empty($codesBureaux)) {
            $query->whereIn('code_bureau', $codesBureaux);
        } else {
            // Si aucun bureau assigné, ne rien retourner
            $query->whereRaw('1 = 0');
        }
    }
    
    // Application des filtres
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    if ($request->filled('category')) {
        $query->where('code_cat', $request->category);
    }
    
    if ($request->filled('bureau')) {
        $query->where('code_bureau', $request->bureau);
    }
    
    if ($request->filled('priorite')) {
        $query->where('priorite', $request->priorite);
    }
    
    if ($request->filled('date_from')) {
        $query->whereDate('date_sousmis', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->whereDate('date_sousmis', '<=', $request->date_to);
    }
    
    // Tri
    $sortBy = $request->get('sort', 'date_sousmis');
    $sortDirection = $request->get('direction', 'desc');
    $query->orderBy($sortBy, $sortDirection);
    
    $requetes = $query->paginate(15);
    $categories = Category::all();
    
    // Les bureaux affichés dans les filtres peuvent être limités au bureau de l'utilisateur
    // ou tous les bureaux selon vos besoins
    $bureaux = Bureau::all();
    // Ou pour limiter au bureau de l'utilisateur :
    // $bureaux = Bureau::where('code', $personnel->code_bureau)->get();
    
    return view('sige_app.backend.administration.liste_requete', compact('requetes', 'categories', 'bureaux'));
}

    /**
     * Show the specified request for admin
     */
   public function show(string $code_requete)
{
    $query = Requete::with(['category', 'user', 'bureau', 'fichiers', 'reponses']);
    
    // Récupération du personnel en session
    $personnel = session('pers');
    
    // Vérification que l'utilisateur est connecté
    // if (!$personnel) {
    //     return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
    // }
    
    // Vérification des permissions d'accès
    $userRoles = $personnel->getRoleNames()->toArray();
    
    // Vérification des rôles autorisés
    $rolesAutorises = ['ADMIN', 'CHEF_SERV', 'CHEF_DEPT', 'CHEF_DIV', 'PERSONNEL_APPUI', 'ENSEIGNANT']; // Ajustez selon vos besoins
    if (!array_intersect($userRoles, $rolesAutorises)) {
        abort(403, 'Accès non autorisé. Vous n\'avez pas les privilèges nécessaires.');
    }

    // Tous les utilisateurs (sauf ADMIN) ne peuvent voir que les requêtes de leur(s) bureau(x)
    if (!in_array('ADMIN', $userRoles)) {
        $userBureaux = $this->getUserBureaux();
        $codesBureaux = $userBureaux->pluck('code_bureau')->toArray();
        if (!empty($codesBureaux)) {
            $query->whereIn('code_bureau', $codesBureaux);
        } else {
            // Si aucun bureau assigné, ne rien retourner
            $query->whereRaw('1 = 0');
        }
    }
    
    // Recherche de la requête spécifique
    $requete = $query->where('code_requete', $code_requete)->first();
    
    // Vérification que la requête existe et appartient au bureau de l'utilisateur
    if (!$requete) {
        abort(404, 'Requête non trouvée ou vous n\'avez pas l\'autorisation de la consulter.');
    }
    
    return view('sige_app.backend.administration.details_requete', compact('requete'));
}

    /**
     * Update request status
     */
    public function updateStatus(Request $request, string $code_requete)
    {
        $request->validate([
            'status'         => 'required|in:en cours,en attente,traitée,rejetée',
            'note_interne'   => 'nullable|string|max:191',
            'nouveau_bureau' => 'nullable|exists:bureau,code_bureau',
            'email_notifications' => 'nullable|boolean',
        ]);

        $query = Requete::query();
        // Récupération du personnel en session
        $personnel = session('pers');
       // Vérification des permissions d'accès
    $userRoles = $personnel->getRoleNames()->toArray();
    
    // Vérification des rôles autorisés
    $rolesAutorises = ['ADMIN', 'CHEF_SERV', 'CHEF_DEPT', 'CHEF_DIV', 'PERSONNEL_APPUI', 'ENSEIGNANT']; // Ajustez selon vos besoins
    if (!array_intersect($userRoles, $rolesAutorises)) {
        abort(403, 'Accès non autorisé. Vous n\'avez pas les privilèges nécessaires.');
    }

    // Tous les utilisateurs (sauf ADMIN) ne peuvent voir que les requêtes de leur(s) bureau(x)
    if (!in_array('ADMIN', $userRoles)) {
        $userBureaux = $this->getUserBureaux();
        $codesBureaux = $userBureaux->pluck('code_bureau')->toArray();
        if (!empty($codesBureaux)) {
            $query->whereIn('code_bureau', $codesBureaux);
        } else {
            // Si aucun bureau assigné, ne rien retourner
            $query->whereRaw('1 = 0');
        }
    }

        $requete = $query->where('code_requete', $code_requete)->firstOrFail();

        $oldStatus = $requete->status;
        $newStatus = $request->status;

        try {
            $updateData = [
                'note_interne' => $request->note_interne,
            ];

            // Only update status if it is different and is one of the allowed statuses
            if ($newStatus !== $oldStatus && in_array($newStatus, ['en cours', 'en attente', 'traitée', 'rejetée'])) {
                $updateData['status'] = $newStatus;

                // Gestion des dates selon le statut
                switch ($newStatus) {
                    case 'en cours':
                        $updateData['date_asignation'] = now();
                        // Clear date_traitement if status is set back to 'en cours'
                        $updateData['date_traitement'] = null;
                        break;

                    case 'traitée':
                    case 'rejetée':
                        $updateData['date_traitement'] = now();
                        break;
                }
            }

            // Transfert vers un autre bureau
            $statusChangedByTransfer = false;
            if ($request->filled('nouveau_bureau') && $request->nouveau_bureau !== $requete->code_bureau) {
                $updateData['code_bureau']     = $request->nouveau_bureau;
                $updateData['status']          = 'en cours'; // Automatically set to 'en cours' on transfer
                $updateData['date_asignation'] = now();
                $statusChangedByTransfer = true;
            }

            $requete->update($updateData);

            // Notification de transfert et changement de statut
            $userEmail = $requete->user->email_user ?? null;
            $sendEmail = $request->input('email_notifications', false);

            if ($request->filled('nouveau_bureau') && $request->nouveau_bureau !== $requete->code_bureau) {
                if ($userEmail && $sendEmail) {
                    try {
                        Mail::to($userEmail)->send(new RequeteAssignedMail($requete, $request->nouveau_bureau));
                    } catch (\Exception $e) {
                        Log::error('Erreur envoi mail assignation: ' . $e->getMessage());
                        return back()->with('success', 'Statut de la requête mis à jour avec succès.')->with('error', 'Le mail d\'assignation n\'a pas pu être envoyé.');
                    }
                }
            }
            if ($oldStatus !== $newStatus || $statusChangedByTransfer) {
                if ($userEmail && $sendEmail) {
                    try {
                        $emailOldStatus = $oldStatus;
                        $emailNewStatus = $newStatus;
                        if ($statusChangedByTransfer) {
                            $emailOldStatus = $oldStatus;
                            $emailNewStatus = 'en cours';
                        }
                        Mail::to($userEmail)->send(new RequeteStatusChangeMail($requete, $emailOldStatus, $emailNewStatus));
                    } catch (\Exception $e) {
                        Log::error('Erreur envoi mail changement statut: ' . $e->getMessage());
                        return back()->with('success', 'Statut de la requête mis à jour avec succès.')->with('error', 'Le mail de changement de statut n\'a pas pu être envoyé.');
                    }
                }
            }

            return back()->with('success', 'Statut de la requête mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour statut requête: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Erreur lors de la mise à jour du statut. Détails: ' . $e->getMessage());
        }
    }

    /**
     * Assign request to bureau
     */
    // public function assign(Request $request, string $code_requete)
    // {
    //     $request->validate([
    //         'code_bureau' => 'required|exists:bureau,code_bureau',
    //     ]);

    //     $requete = Requete::where('code_requete', $code_requete)->firstOrFail();

    //     if ($requete->code_bureau === $request->code_bureau) {
    //         return back()->with('error', 'La requête est déjà assignée à ce bureau.');
    //     }

    //     try {
    //         $requete->update([
    //             'code_bureau'     => $request->code_bureau,
    //             'status'          => 'en attente',
    //             'date_asignation' => now(),
    //         ]);

    //         // Notification d'assignation
    //         $user = session('user');
    //         Mail::to($user->email_user)->send(new RequeteAssignedMail($requete, $request->code_bureau));

    //         return back()->with('success', 'Requête assignée avec succès.');

    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Erreur lors de l\'assignation de la requête.');
    //     }
    // }

    /**
     * Add response to request
     */
    public function addResponse(Request $request, string $code_requete)
    {
        $request->validate([
            'text_reponse' => 'required|string|max:180',
            'email_notifications' => 'nullable|boolean',
        ]);
        

        $query = Requete::query();

         // Récupération du personnel en session
        $personnel = session('pers');
       // Vérification des permissions d'accès
    $userRoles = $personnel->getRoleNames()->toArray();
    
    // Vérification des rôles autorisés
    $rolesAutorises = ['ADMIN', 'CHEF_SERV', 'CHEF_DEPT', 'CHEF_DIV', 'PERSONNEL_APPUI', 'ENSEIGNANT']; // Ajustez selon vos besoins
    if (!array_intersect($userRoles, $rolesAutorises)) {
        abort(403, 'Accès non autorisé. Vous n\'avez pas les privilèges nécessaires.');
    }

    // Tous les utilisateurs (sauf ADMIN) ne peuvent voir que les requêtes de leur(s) bureau(x)
    if (!in_array('ADMIN', $userRoles)) {
        $userBureaux = $this->getUserBureaux();
        $codesBureaux = $userBureaux->pluck('code_bureau')->toArray();
        if (!empty($codesBureaux)) {
            $query->whereIn('code_bureau', $codesBureaux);
        } else {
            // Si aucun bureau assigné, ne rien retourner
            $query->whereRaw('1 = 0');
        }
    }

        $requete = $query->where('code_requete', $code_requete)->firstOrFail();

        try {
            $reponse = Reponse::create([
                // 'code_res'     => 'RES-' . strtoupper(Str::random(10)),
                'text_reponse' => $request->text_reponse,
                'code_requete' => $code_requete,
            ]);

            // Mise à jour du statut si nécessaire
            if ($requete->status === 'en attente') {
                $requete->update(['status' => 'en cours']);
            }

            // Notification de réponse
            $userEmail = $requete->user->email_user ?? null;
            $sendEmail = $request->input('email_notifications', false);
            if ($userEmail && $request->filled('text_reponse') && $sendEmail) {
                try {
                    Mail::to($userEmail)->send(new RequeteResponseMail($requete, $reponse));
                } catch (\Exception $e) {
                    Log::error('Erreur envoi mail réponse: ' . $e->getMessage());
                    return back()->with('success', 'Réponse ajoutée avec succès.')->with('error', 'Le mail de réponse n\'a pas pu être envoyé.');
                }
            }

            return back()->with('success', 'Réponse ajoutée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur d\'ajout de la requete: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Erreur lors de l\'ajout de la reponse. Détails: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions on requests
     */
    // public function bulkAction(Request $request)
    // {
    //     $request->validate([
    //         'action' => 'required|in:assign,status,delete',
    //         'requetes' => 'required|array',
    //         'requetes.*' => 'exists:requetes,code_requete',
    //         'bulk_bureau' => 'required_if:action,assign|exists:bureau,code_bureau',
    //         'bulk_status' => 'required_if:action,status|in:en attente,en cours,traitée,rejetée'
    //     ]);

    //     $query = Requete::whereIn('code_requete', $request->requetes);

    //     if (Auth::user()->hasRole('agent')) {
    //         $query->where('code_bureau', Auth::user()->code_bureau);
    //     }

    //     $requetes = $query->get();

    //     if ($requetes->isEmpty()) {
    //         return back()->with('error', 'Aucune requête sélectionnée ou autorisée.');
    //     }

    //     try {
    //         switch ($request->action) {
    //             case 'assign':
    //                 foreach ($requetes as $requete) {
    //                     $requete->update([
    //                         'code_bureau' => $request->bulk_bureau,
    //                         'status' => 'en attente',
    //                         'date_asignation' => now()
    //                     ]);

    //                     Mail::to($requete->user->email)->send(new RequeteAssignedMail($requete, $request->bulk_bureau));
    //                 }
    //                 $message = 'Requêtes assignées avec succès.';
    //                 break;

    //             case 'status':
    //                 foreach ($requetes as $requete) {
    //                     $oldStatus = $requete->status;
    //                     $requete->update(['status' => $request->bulk_status]);

    //                     Mail::to($requete->user->email)->send(new RequeteStatusChangedMail($requete, $oldStatus, $request->bulk_status));
    //                 }
    //                 $message = 'Statuts mis à jour avec succès.';
    //                 break;

    //             case 'delete':
    //                 foreach ($requetes as $requete) {
    //                     // Supprimer les fichiers associés
    //                     foreach ($requete->fichiers as $fichier) {
    //                         Storage::disk('public')->delete($fichier->chemin);
    //                     }
    //                     $requete->delete();
    //                 }
    //                 $message = 'Requêtes supprimées avec succès.';
    //                 break;
    //         }

    //         return back()->with('success', $message);

    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Erreur lors de l\'action groupée.');
    //     }
    // }

    /**
     * Dashboard with statistics
     */ // AdminRequeteController.php
    public function statistiques()
    {
        $userRole = $this->getCurrentUserRole();
        $userBureaux = $this->getUserBureaux();

        // Construction de la requête de base avec restrictions
        $baseQuery = Requete::query();
        $baseQuery = $this->applyRoleRestrictions($baseQuery);

        // Statistiques globales (limitées selon le rôle)
        $totalRequetes = $baseQuery->count();
        $requetesEnAttente = (clone $baseQuery)->where('status','en attente')->count();
        $requetesEnCours = (clone $baseQuery)->where('status', 'en cours')->count();
        $requetesTraitees = (clone $baseQuery)->where('status', 'traitée')->count();
        $requetesRejetees = (clone $baseQuery)->where('status', 'rejetée')->count();

        // Répartition par bureau (selon les permissions)
        $statistiquesParBureau = (clone $baseQuery)
            ->with('bureau')
            ->select('code_bureau', DB::raw('count(*) as total'))
            ->groupBy('code_bureau')
            ->get();

        // Répartition par catégorie
        $statistiquesParCategorie = (clone $baseQuery)
            ->with('category')
            ->select('code_cat', DB::raw('count(*) as total'))
            ->groupBy('code_cat')
            ->get();

        // Évolution mensuelle
        $evolutionMensuelle = (clone $baseQuery)
            ->select(
                DB::raw('YEAR(date_sousmis) as annee'),
                DB::raw('MONTH(date_sousmis) as mois'),
                DB::raw('count(*) as total')
            )
            ->where('date_sousmis', '>=', now()->subYear())
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->get();

        // Top 10 utilisateurs les plus actifs
        $utilisateursActifs = (clone $baseQuery)
            ->with('user')
            ->select('code_user', DB::raw('count(*) as total_requetes'))
            ->groupBy('code_user')
            ->orderBy('total_requetes', 'desc')
            ->limit(10)
            ->get();

        // Temps moyen de traitement par bureau
        $tempsTraitementParBureau = (clone $baseQuery)
            ->with('bureau')
            ->whereNotNull('date_traitement')
            ->select(
                'code_bureau',
                DB::raw('AVG(DATEDIFF(date_traitement, date_sousmis)) as moyenne_jours')
            )
            ->groupBy('code_bureau')
            ->get();

        // Statistiques par statut (avec rejeté)
        $statistiquesParStatut = (clone $baseQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Statistiques de performance par bureau
        $performanceParBureau = [];
        foreach ($userBureaux as $bureau) {
            $bureauStats = (clone $baseQuery)->where('code_bureau', $bureau->code_bureau);

            $performance = [
                'bureau' => $bureau,
                'total' => $bureauStats->count(),
                'en_attente' => (clone $bureauStats)->where('status', 'en attente')->count(),
                'en_cours' => (clone $bureauStats)->where('status', 'en cours')->count(),
                'traitees' => (clone $bureauStats)->where('status', 'traitée')->count(),
                'rejetees' => (clone $bureauStats)->where('status', 'rejetée')->count(),
                'temps_moyen' => $this->getTempsTraitementMoyen($bureau->code_bureau),
                'taux_resolution' => $this->getTauxResolution($bureau->code_bureau)
            ];

            $performanceParBureau[] = $performance;
        }

        // Données spécifiques selon le rôle
        $roleSpecificData = $this->getRoleSpecificData($userRole, $userBureaux);

        return view('sige_app.backend.administration.statistiques', compact(
            'totalRequetes',
            'requetesEnAttente',
            'requetesEnCours',
            'requetesTraitees',
            'requetesRejetees',
            'statistiquesParBureau',
            'statistiquesParCategorie',
            'statistiquesParStatut',
            'evolutionMensuelle',
            'utilisateursActifs',
            'tempsTraitementParBureau',
            'performanceParBureau',
            'userRole',
            'userBureaux',
            'roleSpecificData'
        ));
    }

    private function applyRoleRestrictions($query)
    {
        $personnel = session('pers');

        if (!$personnel) {
            // Si pas de personnel en session, retourner une requête vide
            return $query->whereRaw('1 = 0');
        }

        $roles = $this->getUserRoles($personnel);

        // Super Admin : accès total
        if (in_array('ADMIN', $roles) || in_array('ADMIN_GENERAL', $roles)) {
            return $query; // Pas de restriction
        }

        // Chef de département/service : accès à son bureau uniquement
        if (in_array('CHEF_DEPT', $roles) || in_array('CHEF_SERV', $roles)) {
            $userBureaux = $this->getUserBureaux();
            $codesBureaux = $userBureaux->pluck('code_bureau')->toArray();

            if (!empty($codesBureaux)) {
                return $query->whereIn('code_bureau', $codesBureaux);
            }
        }

        // Agent : accès à son bureau uniquement
        if (in_array('CHEF_DIV', $roles) || in_array('PERSONNEL_APPUI', $roles)) {
            $userBureaux = $this->getUserBureaux();
            $codesBureaux = $userBureaux->pluck('code_bureau')->toArray();

            if (!empty($codesBureaux)) {
                return $query->whereIn('code_bureau', $codesBureaux);
            }
        }

        // Par défaut, aucun accès
        return $query->whereRaw('1 = 0');
    }

    /**
     * Obtenir les rôles de l'utilisateur connecté
     */
    private function getUserRoles($personnel = null)
    {
        if (!$personnel) {
            $personnel = session('pers');
        }

        if (!$personnel) {
            return [];
        }

        // Récupérer les rôles du personnel
        $roles = PersRole::with('role')
            ->where('code_pers', $personnel->code_pers)
            ->where('statut_role', PersRole::STATUT_ACTIF)
            ->where(function ($query) {
                $query->whereNull('date_fin_role')
                    ->orWhere('date_fin_role', '>', now());
            })
            ->get()
            ->pluck('role.name')
            ->toArray();

        return $roles;
    }

    /**
     * Obtenir le rôle principal de l'utilisateur connecté
     */
    private function getCurrentUserRole()
    {
        $roles = $this->getUserRoles();

        if (empty($roles)) {
            return 'GUEST';
        }

        // Priorité des rôles
        $rolePriority = [
            'ADMIN' => 1,
            'ADMIN_GENERAL' => 2,
            'CHEF_DEPT' => 3,
            'CHEF_SERV' => 4,
            'CHEF_DIV' => 5,
            'PERSONNEL_APPUI' => 6,
            'ENSEIGNANT' => 6
        ];

        $highestRole = 'GUEST';
        $highestPriority = 999;

        foreach ($roles as $role) {
            $priority = $rolePriority[$role] ?? 999;
            if ($priority < $highestPriority) {
                $highestPriority = $priority;
                $highestRole = $role;
            }
        }

        return $highestRole;
    }

    /**
     * Obtenir les bureaux accessibles par l'utilisateur
     */
    private function getUserBureaux()
    {
        $personnel = session('pers');

        if (!$personnel) {
            return collect();
        }

        $roles = $this->getUserRoles($personnel);

        // Super Admin : tous les bureaux
        if (in_array('ADMIN', $roles) || in_array('ADMIN_GENERAL', $roles)) {
            return Bureau::all();
        }

        // Autres rôles : bureaux où le personnel a un rôle actif
        $codesBureaux = PersRole::where('code_pers', $personnel->code_pers)
            ->where('statut_role', PersRole::STATUT_ACTIF)
            ->where(function ($query) {
                $query->whereNull('date_fin_role')
                    ->orWhere('date_fin_role', '>', now());
            })
            ->pluck('code_bureau')
            ->unique();

        return Bureau::whereIn('code_bureau', $codesBureaux)->get();
    }

    /**
     * Obtenir les bureaux accessibles pour les filtres
     */
    private function getAccessibleBureaux()
    {
        $roles = $this->getUserRoles();

        // Super Admin : tous les bureaux
        if (in_array('ADMIN', $roles) || in_array('ADMIN_GENERAL', $roles)) {
            return Bureau::all();
        }

        // Autres : bureaux de l'utilisateur
        return $this->getUserBureaux();
    }

    /**
     * Vérifier si l'utilisateur peut accéder à un bureau
     */
    private function canAccessBureau($codeBureau)
    {
        $accessibleBureaux = $this->getAccessibleBureaux();
        return $accessibleBureaux->contains('code_bureau', $codeBureau);
    }

    /**
     * Obtenir le temps de traitement moyen pour un bureau
     */
    private function getTempsTraitementMoyen($codeBureau)
    {
        $moyenne = Requete::where('code_bureau', $codeBureau)
            ->whereNotNull('date_traitement')
            ->selectRaw('AVG(DATEDIFF(date_traitement, date_sousmis)) as moyenne')
            ->value('moyenne');

        return $moyenne ? round($moyenne, 1) : 0;
    }

    /**
     * Obtenir le taux de résolution pour un bureau
     */
    private function getTauxResolution($codeBureau)
    {
        $total = Requete::where('code_bureau', $codeBureau)->count();
        $resolues = Requete::where('code_bureau', $codeBureau)
            ->whereIn('status', ['traitée', 'rejetée'])
            ->count();

        return $total > 0 ? round(($resolues / $total) * 100, 1) : 0;
    }

    /**
     * Obtenir des données spécifiques selon le rôle
     */
    private function getRoleSpecificData($role, $bureaux)
    {
        $data = [];

        switch ($role) {
            case 'ADMIN':
            case 'ADMIN_GENERAL':
                $data['title'] = 'Tableau de bord - Administrateur';
                $data['scope'] = 'Toute l\'organisation';
                $data['permissions'] = ['view_all', 'manage_all', 'transfer_all'];
                break;

            case 'CHEF_DEPT':
            case 'CHEF_SERV':
                $data['title'] = 'Tableau de bord - Chef de Service';
                $data['scope'] = 'Bureaux: ' . $bureaux->pluck('label_bureau')->join(', ');
                $data['permissions'] = ['view_bureau', 'manage_bureau', 'transfer_limited'];
                break;

            case 'CHEF_DIV':
            case 'PERSONNEL_APPUI':
                $data['title'] = 'Tableau de bord - Agent';
                $data['scope'] = 'Bureau: ' . $bureaux->pluck('label_bureau')->join(', ');
                $data['permissions'] = ['view_bureau', 'respond_only'];
                break;

            default:
                $data['title'] = 'Tableau de bord';
                $data['scope'] = 'Accès limité';
                $data['permissions'] = [];
        }

        return $data;
    }
}