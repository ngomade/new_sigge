<?php

namespace App\Http\Controllers\concours;

use Exception;
use Throwable;
use App\Models\Serie;
use App\Models\Diplome;
use App\Models\Filiere;
use App\Models\CentreDepot;
use Illuminate\Http\Request;
use App\Models\concours\Compte;
use App\Models\concours\Candidat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\concours\CentreExamen;
use App\Http\Requests\concours\CandidatRequest;
use App\Http\Requests\concours\UpdateCandidatRequest;
use App\Notifications\concours\GeneralNotifForCandidat;

class CandidatControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidat = Candidat::with(['site_etude', 'filiere', 'sessionconcour', 'ecoles'])->get();
        return response()->json($candidat);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws Throwable
     */
    public function store(CandidatRequest $request)
    {
        $validatedData = $request->validated();

        if (! isset($validatedData["ca_handicap"])  ) {
            $validatedData["ca_handicap"] = "Non"; // Assurer que ca_handicap est nullable
        }
        try {
            DB::beginTransaction();
            $candidat = Candidat::create($validatedData);
            $compte = Compte::where("ca_num_recu", $candidat->ca_num_recu)->first();
            if ($compte !== null) {
                Log::info('Compte found before update: ' . json_encode($compte));
                Log::info('Compte ca_code before update: ' . $compte->ca_code);
                $compte->ca_code = $candidat->ca_code;
                $compte->save();
                Log::info('Compte ca_code after update: ' . $compte->ca_code);
            } else {
                Log::warning('Compte not found for ca_num_recu: ' . $candidat->ca_num_recu);
            }
            if (!empty($validatedData['ecoles'])) {
                $candidat->ecoles()->attach($validatedData['ecoles']); // Synchroniser les écoles si fournies
            }
            DB::commit();
            return response()->json([
                "message" => "Candidat enregistré avec succès",
                "data" => $candidat
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating candidat: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de l\'enregistrement du candidat'], 500);
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
        $candidat = Candidat::where('ca_code',$candidat->ca_code)->first();
        $filiere = Filiere::find($candidat->filiere_code);
        $diplome = Diplome::find($candidat->ca_diplome_admission);
        $serie = Serie::find($candidat->ca_serie_diplome);
        $centre_examen = CentreExamen::find($candidat->ca_centre_examen);
        $centre_depot = CentreDepot::find($candidat->ca_centre_depot);
        $candidat->filiere_code = $filiere;
        $candidat->ca_diplome_admission = $diplome;
        $candidat->ca_serie_diplome = $serie;
        $candidat->ca_centre_examen = $centre_examen;
        $candidat->ca_centre_depot = $centre_depot;
        return response()->json($candidat);
    }
    public function getCandidatsBycentre(string $id_centre)
    {
        $candidats = Candidat::where("ca_centre_examen", $id_centre)->get();
        return response()->json([
            "candidats" => $candidats
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(UpdateCandidatRequest $request, string $ca_code)
    {
        $candidat = Candidat::findOrFail($ca_code);

        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $candidat->update($validatedData);
            if (!empty($validatedData['ecoles'])) {
                $candidat->ecoles()->sync($validatedData['ecoles']); // Synchroniser les écoles si fournies
            }
            DB::commit();

            $candidat->load(['site_etude', 'filiere', 'sessionconcour', 'ecoles']);

            return response()->json([
                "message" => "Candidat mis à jour avec succès",
                "data" => $candidat
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating candidat: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la mise à jour du candidat.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(string $ca_code)
    {
        $candidat = Candidat::findOrFail($ca_code);
        try {
            DB::beginTransaction();
            $candidat->delete();
            if ($candidat->ecoles()->exists()) {
                $candidat->ecoles()->detach(); // Détacher les écoles associées
            }
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting candidat: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la suppression du candidat'], 500);
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

        return response()->json($candidats);
    }

    /**
     * Récupérer les candidats par site d'étude
     */
    public function bySite(int $code_site)
    {
        $candidats = Candidat::where('code_site', $code_site)
                            ->with(['site_etude', 'filiere', 'ecoles'])
                            ->get();

        return response()->json($candidats);
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

        return response()->json($candidats);
    }

    public function sendGeneralMail(Request $request)
    {
        $validatedData = $request->validate([
            'objet' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        try {
            Candidat::all()->each(function ($candidat) use ($validatedData) {
                $candidat->notify(new GeneralNotifForCandidat($validatedData['contenu'], $validatedData['objet']));
            });
        } catch (Exception $e) {
            Log::error('Error sending general mail: ' . $e->getMessage());
            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'envoi des emails'], 500);
        }

        return response()->json(['message' => 'Emails envoyés avec succès']);
    }

    /**
     * Renvoie des statistiques détaillées sur les candidats
     */
    public function statCandidat()
    {
        try {
            $candidats = Candidat::all();

//            // Répartition par sexe
//            $candidatsParSexe = Candidat::select('ca_sexe', DB::raw('count(*) as total'))
//                ->groupBy('ca_sexe')
//                ->get()
//                ->pluck('total', 'ca_sexe')
//                ->toArray();
//
//            // Répartition par filière
//            $candidatsParFiliere = Candidat::select('filiere_code', DB::raw('count(*) as total'))
//                ->groupBy('filiere_code')
//                ->get();
//
//            // Répartition par site d'étude
//            $candidatsParSite = Candidat::select('code_site', DB::raw('count(*) as total'))
//                ->groupBy('code_site')
//                ->get();
//
//            // Répartition par nationalité
//            $candidatsParNationalite = Candidat::select('ca_nationalite', DB::raw('count(*) as total'))
//                ->groupBy('ca_nationalite')
//                ->get();
//
//            // Répartition par région d'origine
//            $candidatsParRegion = Candidat::select('ca_region_origine', DB::raw('count(*) as total'))
//                ->groupBy('ca_region_origine')
//                ->get();
//
//            // Répartition par diplôme d'admission
//            $candidatsParDiplome = Candidat::select('ca_diplome_admission', DB::raw('count(*) as total'))
//                ->groupBy('ca_diplome_admission')
//                ->get();
//
//            // Répartition par année de diplôme
//            $candidatsParAnneeDiplome = Candidat::select('ca_annee_diplome', DB::raw('count(*) as total'))
//                ->groupBy('ca_annee_diplome')
//                ->orderBy('ca_annee_diplome', 'desc')
//                ->get();
//
//            // Répartition par mention de diplôme
//            $candidatsParMention = Candidat::select('ca_mention_diplome', DB::raw('count(*) as total'))
//                ->groupBy('ca_mention_diplome')
//                ->get();
//
//            // Candidats avec handicap
//            $candidatsAvecHandicap = Candidat::whereNotNull('ca_handicap')
//                ->where('ca_handicap', '!=', '')
//                ->count();
//
//            // Candidats par centre d'examen
//            $candidatsParCentreExamen = Candidat::select('ca_centre_examen', DB::raw('count(*) as total'))
//                ->groupBy('ca_centre_examen')
//                ->get();
//
//            // Candidats par centre de dépôt
//            $candidatsParCentreDepot = Candidat::select('ca_centre_depot', DB::raw('count(*) as total'))
//                ->groupBy('ca_centre_depot')
//                ->get();
//
//            // Âge moyen des candidats (si date de naissance disponible)
//            $ageMoyen = Candidat::whereNotNull('ca_date_naiss')
//                ->select(DB::raw('AVG(YEAR(CURRENT_DATE) - YEAR(ca_date_naiss)) as age_moyen'))
//                ->first();

            // Regrouper toutes les statistiques
            $stats = [
                'total' => count($candidats),
                't_estlc'   => $candidats->where("code_site", 1)->count(),
                'nb_f_estlc'=>$candidats->where("code_site", 1)->where("ca_sexe", "Féminin")->count(),
                'nb_g_estlc'=>$candidats->where("code_site", 1)->where("ca_sexe", "Masculin")->count(),
                'total_ttl_estlc'=>$candidats->where("code_site", 1)->where("filiere_code", "TTL")->count(),
                'total_f_ttl_estlc'=>$candidats->where("code_site", 1)->where("filiere_code", "TTL")->where("ca_sexe", "Féminin")->count(),
                'total_g_ttl_estlc'=>$candidats->where("code_site", 1)->where("filiere_code", "TTL")->where("ca_sexe", "Masculin")->count(),
                'total_gltco_estlc'=>$candidats->where("code_site", 1)->where("filiere_code", "GLTCO")->count(),
                'total_f_gltco_estlc'=>$candidats->where("code_site", 1)->where("filiere_code", "GLTCO")->where("ca_sexe", "Féminin")->count(),
                'total_g_gltco_estlc'=>$candidats->where("code_site", 1)->where("filiere_code", "GLTCO")->where("ca_sexe", "Masculin")->count(),
                't_islape'  => $candidats->where("code_site", 2)->count(),
                'nb_f_islape'=>$candidats->where("code_site", 2)->where("ca_sexe", "Feminin")->count(),
                'nb_g_islape'=>$candidats->where("code_site", 2)->where("ca_sexe", "Masculin")->count(),
                'total_ttl_islape'=>$candidats->where("code_site", 2)->where("filiere_code", "TTL")->count(),
                'total_f_ttl_islape'=>$candidats->where("code_site", 2)->where("filiere_code", "TTL")->where("ca_sexe", "Féminin")->count(),
                'total_g_ttl_islape'=>$candidats->where("code_site", 2)->where("filiere_code", "TTL")->where("ca_sexe", "Masculin")->count(),
                'total_gltco_islape'=>$candidats->where("code_site", 2)->where("filiere_code", "GLTCO")->count(),
                'total_f_gltco_islape'=>$candidats->where("code_site", 2)->where("filiere_code", "GLTCO")->where("ca_sexe", "Féminin")->count(),
                'total_g_gltco_islape'=>$candidats->where("code_site", 2)->where("filiere_code", "GLTCO")->where("ca_sexe", "Masculin")->count(),
//                'repartition_par_sexe' => $candidatsParSexe,
//                'repartition_par_filiere' => $candidatsParFiliere,
//                'repartition_par_site' => $candidatsParSite,
//                'repartition_par_nationalite' => $candidatsParNationalite,
//                'repartition_par_region' => $candidatsParRegion,
//                'repartition_par_diplome' => $candidatsParDiplome,
//                'repartition_par_annee_diplome' => $candidatsParAnneeDiplome,
//                'repartition_par_mention' => $candidatsParMention,
//                'candidats_avec_handicap' => $candidatsAvecHandicap,
//                'repartition_par_centre_examen' => $candidatsParCentreExamen,
//                'repartition_par_centre_depot' => $candidatsParCentreDepot,
//                'age_moyen' => $ageMoyen?->age_moyen,
                'centre_examen' => CentreExamen::all(),
                'centre_depot' => CentreDepot::all(),
                'candidats' => $candidats,
                'diplomes' => Diplome::all(),
                'series' => Serie::all(),
                'filiere'=>Filiere::all()
            ];

            return response()->json($stats);
        } catch (Exception $e) {
            Log::error('Error retrieving candidat stats: ' . $e->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la récupération des statistiques'], 500);
        }
    }

     public function getCandidatAllinfo($id)
    {

        try {
            $candidat = Candidat::where('ca_code',$id)->first();
            $filiere = Filiere::find($candidat->filiere_code);
            $diplome = Diplome::find($candidat->ca_diplome_admission);
            $serie = Serie::find($candidat->ca_serie_diplome);
            $centre_examen = CentreExamen::find($candidat->ca_centre_examen);
            $centre_depot = CentreDepot::find($candidat->ca_centre_depot);
            $candidat->filiere_code = $filiere;
            $candidat->ca_diplome_admission = $diplome;
            $candidat->ca_serie_diplome = $serie;
            $candidat->ca_centre_examen = $centre_examen;
            $candidat->ca_centre_depot = $centre_depot;
            return response()->json($candidat, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["error" => "Ce candidat n'existe pas"], 400);
        }
    }
}
