<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\concours\Candidat;
use Illuminate\Support\Facades\DB;
use Throwable;

class CandidatControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidats = Candidat::with(['site_etude', 'filiere', 'sessionconcour', 'ecoles'])->get();
        return response()->json($candidats, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'ca_code' => 'required|string|unique:candidat,ca_code',
            'id' => 'required|integer|exists:sessionconcour,id',
            'filiere_code' => 'required|string|exists:filiere,filiere_code',
            'code_site' => 'required|integer|exists:site_etude,code_site',
            'ca_nom' => 'required|string|max:255',
            'ca_prenom' => 'required|string|max:255',
            'ca_sexe' => 'required|string|in:M,F',
            'ca_date_naiss' => 'required|date',
            'ca_lieu_naiss' => 'required|string|max:255',
            'ca_statut_mat' => 'required|string|max:50',
            'ca_adresse' => 'nullable|string',
            'ca_telephone' => 'required|string|max:20',
            'ca_num_cni' => 'required|string|max:50',
            'ca_email' => 'required|email|max:255',
            'ca_premiere_lang' => 'required|string|max:50',
            'ca_nationalite' => 'required|string|max:100',
            'ca_region_origine' => 'required|string|max:100',
            'ca_depart_origine' => 'required|string|max:100',
            'ca_diplome_admission' => 'required|string|max:100',
            'ca_annee_diplome' => 'required|date',
            'ca_serie_diplome' => 'required|string|max:50',
            'ca_mention_diplome' => 'required|string|max:50',
            'ca_etab_diplome' => 'required|string|max:255',
            'ca_pays_diplome' => 'required|string|max:100',
            'ca_centre_examen' => 'required|string|max:255',
            'ca_centre_depot' => 'required|string|max:255',
            'ca_nom_pere' => 'required|string|max:255',
            'ca_telephone_pere' => 'required|string|max:20',
            'ca_nom_mere' => 'required|string|max:255',
            'ca_telephone_mere' => 'required|string|max:20',
            'ca_handicap' => 'required|string|max:50',
            'ca_email_pere' => 'nullable|email|max:255',
            'ca_deliv_cni' => 'required|string|max:255',
            'ca_num_recu' => 'required|string|max:50',
            'ca_recu' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $candidat = Candidat::create($validateData);
            DB::commit();
            return response()->json($candidat, 201);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de l\'enregistrement du candidat: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ca_code)
    {
        $candidat = Candidat::with(['site_etude', 'filiere', 'sessionconcour', 'ecoles', 'comptes'])->find($ca_code);
        
        if (!$candidat) {
            return response()->json(['erreur' => 'Candidat non trouvé'], 404);
        }
        
        return response()->json($candidat, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ca_code)
    {
        $validateData = $request->validate([
            'id' => 'sometimes|required|integer|exists:sessionconcour,id',
            'filiere_code' => 'sometimes|required|string|exists:filiere,filiere_code',
            'code_site' => 'sometimes|required|integer|exists:site_etude,code_site',
            'ca_nom' => 'sometimes|required|string|max:255',
            'ca_prenom' => 'sometimes|required|string|max:255',
            'ca_sexe' => 'sometimes|required|string|in:M,F',
            'ca_date_naiss' => 'sometimes|required|date',
            'ca_lieu_naiss' => 'sometimes|required|string|max:255',
            'ca_statut_mat' => 'sometimes|required|string|max:50',
            'ca_adresse' => 'nullable|string',
            'ca_telephone' => 'sometimes|required|string|max:20',
            'ca_num_cni' => 'sometimes|required|string|max:50',
            'ca_email' => 'sometimes|required|email|max:255',
            'ca_premiere_lang' => 'sometimes|required|string|max:50',
            'ca_nationalite' => 'sometimes|required|string|max:100',
            'ca_region_origine' => 'sometimes|required|string|max:100',
            'ca_depart_origine' => 'sometimes|required|string|max:100',
            'ca_diplome_admission' => 'sometimes|required|string|max:100',
            'ca_annee_diplome' => 'sometimes|required|date',
            'ca_serie_diplome' => 'sometimes|required|string|max:50',
            'ca_mention_diplome' => 'sometimes|required|string|max:50',
            'ca_etab_diplome' => 'sometimes|required|string|max:255',
            'ca_pays_diplome' => 'sometimes|required|string|max:100',
            'ca_centre_examen' => 'sometimes|required|string|max:255',
            'ca_centre_depot' => 'sometimes|required|string|max:255',
            'ca_nom_pere' => 'sometimes|required|string|max:255',
            'ca_telephone_pere' => 'sometimes|required|string|max:20',
            'ca_nom_mere' => 'sometimes|required|string|max:255',
            'ca_telephone_mere' => 'sometimes|required|string|max:20',
            'ca_handicap' => 'sometimes|required|string|max:50',
            'ca_email_pere' => 'nullable|email|max:255',
            'ca_deliv_cni' => 'sometimes|required|string|max:255',
            'ca_num_recu' => 'sometimes|required|string|max:50',
            'ca_recu' => 'sometimes|required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $candidat = Candidat::findOrFail($ca_code);
            $candidat->update($validateData);
            DB::commit();
            return response()->json($candidat, 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la mise à jour du candidat: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $ca_code)
    {
        try {
            DB::beginTransaction();
            $candidat = Candidat::findOrFail($ca_code);
            $candidat->delete();
            DB::commit();
            return response()->json(['succes' => 'Candidat supprimé avec succès'], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la suppression du candidat: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Récupérer les candidats par filière
     */
    public function byFiliere(string $filiere_code)
    {
        $candidats = Candidat::where('filiere_code', $filiere_code)
                            ->with(['site_etude', 'filiere', 'ecoles'])
                            ->get();
        
        return response()->json($candidats, 200);
    }

    /**
     * Récupérer les candidats par site d'étude
     */
    public function bySite(int $code_site)
    {
        $candidats = Candidat::where('code_site', $code_site)
                            ->with(['site_etude', 'filiere', 'ecoles'])
                            ->get();
        
        return response()->json($candidats, 200);
    }

    /**
     * Rechercher des candidats
     */
    public function search(Request $request)
    {
        $query = Candidat::query();

        if ($request->has('nom')) {
            $query->where('ca_nom', 'like', '%' . $request->nom . '%');
        }

        if ($request->has('prenom')) {
            $query->where('ca_prenom', 'like', '%' . $request->prenom . '%');
        }

        if ($request->has('cni')) {
            $query->where('ca_num_cni', $request->cni);
        }

        if ($request->has('email')) {
            $query->where('ca_email', $request->email);
        }

        $candidats = $query->with(['site_etude', 'filiere', 'ecoles'])->get();
        
        return response()->json($candidats, 200);
    }
}