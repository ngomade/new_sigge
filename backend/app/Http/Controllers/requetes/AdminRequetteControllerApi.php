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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminRequetteControllerApi extends Controller
{
    /**
     * Display a listing of all requests for admin
     */
    public function index(Request $request)
    {
        $query = Requete::with(['category', 'user', 'bureau']);

        if (Auth::Personnel()->hasRole('agent')) {
            $query->where('code_bureau', Auth::user()->code_bureau);
        }

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

        $sortBy = $request->get('sort', 'date_sousmis');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $requetes = $query->paginate(15);
        $categories = Category::all();
        $bureaux = Bureau::all();

        return response()->json([
            'requetes' => $requetes,
            'categories' => $categories,
            'bureaux' => $bureaux,
        ]);
    }

    /**
     * Show the specified request for admin
     */
    public function show(string $code_requete)
    {
        $query = Requete::with(['category', 'user', 'bureau', 'fichiers', 'reponses']);

        if (Auth::Personnel()->hasRole('agent')) {
            $query->where('code_bureau', Auth::user()->code_bureau);
        }

        $requete = $query->where('code_requete', $code_requete)->firstOrFail();

        return response()->json($requete);
    }

    /**
     * Update request status
     */
    public function updateStatus(Request $request, string $code_requete)
    {
        $request->validate([
            'status' => 'required|in:en attente,en cours,traitée,rejetée,escaladée',
            'note_interne' => 'nullable|string|max:191',
            'nouveau_bureau' => 'nullable|exists:bureau,code_bureau',
        ]);

        $query = Requete::query();

        if (Auth::Personnel()->hasRole('agent')) {
            $query->where('code_bureau', Auth::user()->code_bureau);
        }

        $requete = $query->where('code_requete', $code_requete)->firstOrFail();

        $oldStatus = $requete->status;
        $newStatus = $request->status;

        try {
            $updateData = [
                'status' => $newStatus,
                'note_interne' => $request->note_interne,
            ];

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

            if ($request->filled('nouveau_bureau') && $request->nouveau_bureau !== $requete->code_bureau) {
                $updateData['code_bureau'] = $request->nouveau_bureau;
                $updateData['status'] = 'en attente';
                $updateData['date_asignation'] = null;

                Mail::to($requete->user->email_user)->send(new RequeteAssignedMail($requete, $request->nouveau_bureau));
            }

            $requete->update($updateData);

            if ($oldStatus !== $newStatus) {
                Mail::to($requete->user->email_user)->send(new RequeteStatusChangeMail($requete, $oldStatus, $newStatus));
            }

            return response()->json(['message' => 'Statut de la requête mis à jour avec succès.']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour du statut.'], 500);
        }
    }

    /**
     * Assign request to bureau
     */
    public function assign(Request $request, string $code_requete)
    {
        $request->validate([
            'code_bureau' => 'required|exists:bureau,code_bureau',
        ]);

        $requete = Requete::where('code_requete', $code_requete)->firstOrFail();

        if ($requete->code_bureau === $request->code_bureau) {
            return response()->json(['error' => 'La requête est déjà assignée à ce bureau.'], 400);
        }

        try {
            $requete->update([
                'code_bureau' => $request->code_bureau,
                'status' => 'en attente',
                'date_asignation' => now(),
            ]);

            Mail::to($requete->user->email_user)->send(new RequeteAssignedMail($requete, $request->code_bureau));

            return response()->json(['message' => 'Requête assignée avec succès.']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'assignation de la requête.'], 500);
        }
    }

    /**
     * Add response to request
     */
    public function addResponse(Request $request, string $code_requete)
    {
        $request->validate([
            'text_repone' => 'required|string|max:180',
        ]);

        $query = Requete::query();

        if (Auth::Personnel()->hasRole('agent')) {
            $query->where('code_bureau', Auth::user()->code_bureau);
        }

        $requete = $query->where('code_requete', $code_requete)->firstOrFail();

        try {
            $reponse = Reponse::create([
                'code_res' => 'RES-'.strtoupper(Str::random(10)),
                'text_repone' => $request->text_repone,
                'code_requete' => $code_requete,
                'created_by' => Auth::Personnel()->code_pers,
            ]);

            if ($requete->status === 'en attente') {
                $requete->update(['status' => 'en cours']);
            }

            Mail::to($requete->user->email_user)->send(new RequeteResponseMail($requete, $reponse));

            return response()->json(['message' => 'Réponse ajoutée avec succès.']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'ajout de la réponse.'], 500);
        }
    }
}
