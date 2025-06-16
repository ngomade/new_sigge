<?php

namespace App\Http\Controllers\requetes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\requetes\Requete;
use App\Models\requetes\Category;
use App\Models\Bureau;
use App\Models\requetes\Reponse;
use App\Models\Personnel;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\requetes\RequeteStatusChangeMail;
use App\Mail\requetes\RequeteAssignedMail;
use App\Mail\requetes\RequeteResponseMail;

class AdminRequeteController extends Controller
{
    /**
     * Display a listing of all requests for admin
     */
    public function index(Request $request)
    {
        $query = Requete::with(['category', 'user', 'bureau']);

        // Filtres pour les agents (selon leur bureau)
        if (Auth::Personnel()->hasRole('agent')) {
            $query->where('code_bureau', Auth::user()->code_bureau);
        }

        // Filtres
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
        $bureaux = Bureau::all();
        
        return view('sige_app.backend.administration.liste_requete', compact('requetes', 'categories', 'bureaux'));
    }

    /**
     * Show the specified request for admin
     */
    public function show(string $code_requete)
    {
        $query = Requete::with(['category', 'user', 'bureau', 'fichiers', 'reponses']);

        // Restriction pour les agents
        if (Auth::Personnel()->hasRole('agent')) {
            $query->where('code_bureau', Auth::user()->code_bureau);
        }

        $requete = $query->where('code_requete', $code_requete)->firstOrFail();

        return view('sige_app.backend.administration.details_requete', compact('requete'));
    }

    /**
     * Update request status
     */
    public function updateStatus(Request $request, string $code_requete)
    {
        $request->validate([
            'status' => 'required|in:en attente,en cours,traitée,rejetée,escaladée',
            'note_interne' => 'nullable|string|max:191',
            'nouveau_bureau' => 'nullable|exists:bureau,code_bureau'
        ]);

        $query = Requete::query();
        
        // Restriction pour les agents
        if (Auth::Personnel()->hasRole('agent')) {
            $query->where('code_bureau', Auth::user()->code_bureau);
        }

        $requete = $query->where('code_requete', $code_requete)->firstOrFail();

        $oldStatus = $requete->status;
        $newStatus = $request->status;

        try {
            $updateData = [
                'status' => $newStatus,
                'note_interne' => $request->note_interne
            ];

            // Gestion des dates selon le statut
            switch ($newStatus) {
                case 'en cours':
                    if ($oldStatus === 'en attente') {
                        $updateData['date_asignation'] = now();
                    }
                    break;
                
                case 'traitée':
                case 'rejetée':
                    $updateData['date_traitement'] = now();
                    break;
            }

            // Transfert vers un autre bureau
            if ($request->filled('nouveau_bureau') && $request->nouveau_bureau !== $requete->code_bureau) {
                $updateData['code_bureau'] = $request->nouveau_bureau;
                $updateData['status'] = 'en attente'; // Reset status for new bureau
                $updateData['date_asignation'] = null;
                
                // Notification de transfert
                Mail::to($requete->user->email_user)->send(new RequeteAssignedMail($requete, $request->nouveau_bureau));
            }

            $requete->update($updateData);

            // Notification de changement de statut
            if ($oldStatus !== $newStatus) {
                Mail::to($requete->user->email_user)->send(new RequeteStatusChangeMail($requete, $oldStatus, $newStatus));
            }

            return back()->with('success', 'Statut de la requête mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du statut.');
        }
    }

    /**
     * Assign request to bureau
     */
    public function assign(Request $request, string $code_requete)
    {
        $request->validate([
            'code_bureau' => 'required|exists:bureau,code_bureau'
        ]);

        $requete = Requete::where('code_requete', $code_requete)->firstOrFail();

        if ($requete->code_bureau === $request->code_bureau) {
            return back()->with('error', 'La requête est déjà assignée à ce bureau.');
        }

        try {
            $requete->update([
                'code_bureau' => $request->code_bureau,
                'status' => 'en attente',
                'date_asignation' => now()
            ]);

            // Notification d'assignation
            Mail::to($requete->user->email_user)->send(new RequeteAssignedMail($requete, $request->code_bureau));

            return back()->with('success', 'Requête assignée avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'assignation de la requête.');
        }
    }

    /**
     * Add response to request
     */
    public function addResponse(Request $request, string $code_requete)
    {
        $request->validate([
            'text_repone' => 'required|string|max:180'
        ]);

        $query = Requete::query();
        
        if (Auth::Personnel()->hasRole('agent')) {
            $query->where('code_bureau', Auth::user()->code_bureau);
        }

        $requete = $query->where('code_requete', $code_requete)->firstOrFail();

        try {
            $reponse = Reponse::create([
                'code_res' => 'RES-' . strtoupper(Str::random(10)),
                'text_repone' => $request->text_repone,
                'code_requete' => $code_requete,
                'created_by' => Auth::Personnel()->code_pers
            ]);

            // Mise à jour du statut si nécessaire
            if ($requete->status === 'en attente') {
                $requete->update(['status' => 'en cours']);
            }

            // Notification de réponse
            Mail::to($requete->user->email_user)->send(new RequeteResponseMail($requete, $reponse));

            return back()->with('success', 'Réponse ajoutée avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout de la réponse.');
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
     */
   
}