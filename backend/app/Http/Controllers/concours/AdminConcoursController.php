<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\Candidat;
use App\Models\concours\SessionConcours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AdminConcoursController extends Controller
{
    public function index()
    {
        $r_candidat = Candidat::orderBy('ca_nom')->get();

        return view('concours.backend.index', compact('r_candidat'))->render();
    }

    public function show_session()
    {
        $sessions = SessionConcours::all();

        return view('concours.backend.ouvrir_fermer', compact('sessions'))->render();
    }

    public function create()
    {
        $candidats = Candidat::orderBy('ca_nom', 'asc')->get();

        return view('concours.backend.candidat_management', compact('candidats'))->render();
    }

    /**
     * @throws Throwable
     */
    public function add_session(Request $request)
    {
        DB::beginTransaction();
        try {

            $test = SessionConcours::where('annee', $request->annee)->count();
            if ($test == 0) {
                $res = SessionConcours::create(array_merge($request->all(), [
                    'ad_code' => Session::get('admin')->ad_code,
                ]));
                DB::commit();
                $request->session()->flash('success', 'Session créer avec success');

                return back();
            } else {
                $request->session()->flash('errors', 'Cette session a déja été créer');

                return back();
            }
        } catch (Throwable $th) {
            $request->session()->flash('errors', "Echec de l'opération");

            return back();
        }
    }

    public function delete_session(Request $request)
    {
        DB::beginTransaction();
        try {
            $test = Candidat::where('id', $request->id_session)->count();
            if ($test == 0) {
                $res = SessionConcours::find($request->id_session)->delete();
                DB::commit();
                $request->session()->flash('success', 'Session Supprimée avec success');

                return back();
            } else {
                $request->session()->flash('errors', 'Cette session ne peut être supprimée car des candidats y sont déja ');

                return back();
            }

        } catch (Throwable $th) {
            $request->session()->flash('errors', "Echec de l'opération");

            return back();
        }
    }

    /**
     * @throws Throwable
     */
    public function update_session(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id_session_edit;
            $annee = $request->annee_edit;
            $request->request->remove('_token');
            $request->request->remove('id_session_edit');
            $request->request->remove('annee_edit');
            $res = SessionConcours::where('id', $id)
                ->update(array_merge($request->all(), ['annee' => $annee]));
            DB::commit();
            $request->session()->flash('success', 'Session mis à jour avec success');

            return back();

        } catch (Throwable $th) {
            $request->session()->flash('errors', "Echec de l'opération");

            return back();
        }
    }

    public function search(Request $request)
    {
        $query = $request->mot_cle;
        $candidats = Candidat::where('ca_nom', 'LIKE', '%'.$query.'%')
            ->andWhere('ca_prenom', 'LIKE', '%'.$query.'%')
            ->andWhere('ca_code', 'LIKE', '%'.$query.'%')->get();

        return view('concours.backend.result_research', compact('candidats'))->render();
    }

    public function search_imp(Request $request)
    {
        $filiere = $request->filiere;
        $exam = $request->ca_centre_examen;
        $depot = $request->ca_centre_depot;
        if ($filiere == '') {
            $candidats = Candidat::where('ca_centre_examen', 'LIKE', '%'.$exam.'%')
                ->where('ca_centre_depot', 'LIKE', '%'.$depot.'%')->orderBy('created_at', 'desc')->get();
        }
        if ($exam == '') {
            $candidats = Candidat::where('cursus_code', 'LIKE', '%'.$filiere.'%')
                ->Where('ca_centre_depot', 'LIKE', '%'.$depot.'%')->orderBy('created_at', 'desc')->get();
        }
        if ($depot == '') {
            $candidats = Candidat::where('cursus_code', 'LIKE', '%'.$filiere.'%')
                ->where('ca_centre_examen', 'LIKE', '%'.$exam.'%')->orderBy('created_at', 'desc')->get();
        } else {
            if ($exam == '') {
                $candidats = Candidat::where('cursus_code', 'LIKE', '%'.$filiere.'%')
                    ->where('ca_centre_examen', 'LIKE', '%'.$exam.'%')
                    ->where('ca_centre_depot', 'LIKE', '%'.$depot.'%')->orderBy('created_at', 'desc')->get();
            }
        }

        return view('concours.backend.candidat_management', compact(['candidats', 'request']))->render();
    }

    /**
     * @throws Throwable
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $cand = Candidat::find($request->cand_code);
            $path_cand = 'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'cartes'.DIRECTORY_SEPARATOR.getdate()['year'].DIRECTORY_SEPARATOR.$cand->ca_photo;
            /*dd(Storage::exists(storage_path($path_cand)));
            if(Storage::exists(storage_path($path_cand))){*/
            $this->effacer(storage_path($path_cand));
            // }
            $cand->delete();
            DB::commit();
            Session::flash('success', 'Suprrimé avec success');

            return back();
        } catch (Throwable $th) {
            DB::rollback();
            Session::flash('errors', "Echec de l'opération");

            return back();
        }
    }

    public function effacer($fichier)
    {
        if (file_exists($fichier)) {
            if (is_dir($fichier)) {
                $id_dossier = opendir($fichier);
                while ($element = readdir($id_dossier)) {
                    if ($element != '.' && $element != '..') {
                        $this->effacer($fichier.'/'.$element);
                    }
                }
                closedir($id_dossier);
                rmdir($fichier);
            } else {
                unlink($fichier);
            }
        }
    }
}
