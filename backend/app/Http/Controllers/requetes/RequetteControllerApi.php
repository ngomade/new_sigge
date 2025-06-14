<?php

namespace App\Http\Controllers\requetes;

use App\Http\Controllers\Controller;
use App\Models\requetes\Requete;
use App\Models\requetes\Category;
use App\Models\Bureau;
use App\Models\requetes\FichierRequete;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\requetes\RequeteSubmittedMail;

class RequetteControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Requete::with(['category', 'user', 'bureau'])
            ->where('code_user', Auth::user()->code_user);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('code_cat', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_sousmis', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_sousmis', '<=', $request->date_to);
        }

        $requetes = $query->orderBy('date_sousmis', 'desc')->paginate(10);

        return response()->json($requetes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre_requete' => 'required|string|max:180',
            'desc_requete' => 'required|string|max:180',
            'code_cat' => 'required|exists:categories,code_cat',
            'code_bureau' => 'required|exists:bureau,code_bureau',
            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'priorite' => 'in:urgent,standard'
        ]);

        try {
            $codeRequete = 'REQ-' . date('Ymd') . '-' . strtoupper(Str::random(8));

            $requete = Requete::create([
                'code_requete' => $codeRequete,
                'titre_requete' => $request->titre_requete,
                'desc_requete' => $request->desc_requete,
                'status' => 'en attente',
                'date_sousmis' => now(),
                'code_cat' => $request->code_cat,
                'code_user' => Auth::user()->code_user,
                'code_bureau' => $request->code_bureau,
                'priorite' => $request->priorite ?? 'standard'
            ]);

            if ($request->hasFile('fichiers')) {
                foreach ($request->file('fichiers') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('requetes_fichiers', $filename, 'public');

                    FichierRequete::create([
                        'id_fichier' => 'FILE-' . strtoupper(Str::random(10)),
                        'chemin' => $path,
                        'code_requete' => $codeRequete,
                        'nom_original' => $file->getClientOriginalName(),
                        'taille' => $file->getSize()
                    ]);
                }
            }

            Mail::to(Auth::user()->email_user)->send(new RequeteSubmittedMail($requete));

            return response()->json([
                'message' => 'Votre requête a été soumise avec succès.',
                'code_requete' => $codeRequete
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la soumission de la requête. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code_requete)
    {
        $requete = Requete::with(['category', 'user', 'bureau', 'fichiers', 'reponses'])
            ->where('code_requete', $code_requete)
            ->where('code_user', Auth::user()->code_user)
            ->firstOrFail();

        return response()->json($requete);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_requete)
    {
        $requete = Requete::where('code_requete', $code_requete)
            ->where('code_user', Auth::user()->code_user)
            ->where('status', 'en attente')
            ->firstOrFail();

        $request->validate([
            'titre_requete' => 'required|string|max:180',
            'desc_requete' => 'required|string|max:180',
            'code_cat' => 'required|exists:categories,code_cat',
            'code_bureau' => 'required|exists:bureau,code_bureau',
            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'priorite' => 'in:urgent,standard'
        ]);

        try {
            $requete->update([
                'titre_requete' => $request->titre_requete,
                'desc_requete' => $request->desc_requete,
                'code_cat' => $request->code_cat,
                'code_bureau' => $request->code_bureau,
                'priorite' => $request->priorite ?? 'standard'
            ]);

            if ($request->hasFile('fichiers')) {
                foreach ($request->file('fichiers') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('requetes_fichiers', $filename, 'public');

                    FichierRequete::create([
                        'id_fichier' => 'FILE-' . strtoupper(Str::random(10)),
                        'chemin' => $path,
                        'code_requete' => $code_requete,
                        'nom_original' => $file->getClientOriginalName(),
                        'taille' => $file->getSize()
                    ]);
                }
            }

            return response()->json([
                'message' => 'Votre requête a été mise à jour avec succès.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour de la requête.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_requete)
    {
        $requete = Requete::where('code_requete', $code_requete)
            ->where('code_user', Auth::user()->code_user)
            ->where('status', 'en attente')
            ->firstOrFail();

        try {
            foreach ($requete->fichiers as $fichier) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($fichier->chemin);
                $fichier->delete();
            }

            $requete->delete();

            return response()->json([
                'message' => 'Requête supprimée avec succès.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression de la requête.'
            ], 500);
        }
    }
}
