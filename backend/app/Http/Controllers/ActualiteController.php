<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\RessourceActu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ActualiteController extends Controller
{
    public function index()
    {
        return view('sige_app.backend.actualites.form_add_actu');
    }

    public function create()
    {
        $actus = Actualite::orderBy('created_at', 'desc')->paginate(12);

        return view('sige_app.frontend.actualites.all_actu', compact('actus'));
    }

    public function list_actu()
    {
        $actus = Actualite::orderBy('created_at', 'desc')->paginate(20);

        return view('sige_app.backend.actualites.listing_actu', compact('actus'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {

        $val = $request->validate(
            [
                'actu_title' => 'required|min:30',
                'actu_content' => 'required|min:100',
            ]
        );
        $code = $this->generateActuId();
        DB::beginTransaction();
        try {
            $actu_code = $code;
            $pictfile1 = $request->file('photo1');
            $pictfile2 = $request->file('photo2');
            $pictfile3 = $request->file('photo3');
            $pictfile4 = $request->file('photo4');
            $res = Actualite::create(array_merge($request->all(), [
                'actu_code' => $actu_code,
                'code_pers' => Session::get('pers')->code_pers,
                'actu_status' => 1,
                'actu_nb_views' => 0,
            ]));
            if ($res) {
                $image_extension = ['png', 'jpg', 'gif', 'bmp'];
                $path = '/public/actualites/'.$code;
                if (! Storage::exists($path)) {

                    Storage::makeDirectory($path, 0775, true);
                }
                if (($pictfile1 != null) && (in_array($pictfile1->extension(), $image_extension))) {
                    $pictfile1->storeAs($path, "{$actu_code}".'_1'.".{$pictfile1->extension()}");
                    $r1 = RessourceActu::create([
                        'actu_code' => $actu_code,
                        'r_type' => 'photo',
                        'r_name' => "{$actu_code}".'_1'.".{$pictfile1->extension()}",
                    ]);
                }
                if (($pictfile2 != null) && (in_array($pictfile2->extension(), $image_extension))) {
                    $pictfile2->storeAs($path, "{$actu_code}".'_2'.".{$pictfile2->extension()}");
                    $r2 = RessourceActu::create([
                        'actu_code' => $actu_code,
                        'r_type' => 'photo',
                        'r_name' => "{$actu_code}".'_2'.".{$pictfile2->extension()}",
                    ]);
                }
                if (($pictfile3 != null) && (in_array($pictfile3->extension(), $image_extension))) {
                    $pictfile1->storeAs($path, "{$actu_code}".'_3'.".{$pictfile3->extension()}");
                    $r3 = RessourceActu::create([
                        'actu_code' => $actu_code,
                        'r_type' => 'photo',
                        'r_name' => "{$actu_code}".'_3'.".{$pictfile3->extension()}",
                    ]);
                }
                if (($pictfile4 != null) && (in_array($pictfile4->extension(), $image_extension))) {
                    $pictfile4->storeAs($path, "{$actu_code}".'_4'.".{$pictfile4->extension()}");
                    $r4 = RessourceActu::create([
                        'actu_code' => $actu_code,
                        'r_type' => 'photo',
                        'r_name' => "{$actu_code}".'_4'.".{$pictfile4->extension()}",
                    ]);
                }
            }
            DB::commit();
            $success = 'Votre actualité a été publié avec success';
            $request->session()->flash('success', $success);

            return back();
        } catch (Throwable $th) {
            DB::rollback();

            return back()->withInput($request->all())->withErrors($val);
        }
    }

    public function show($id)
    {

        $actualite = Actualite::find($id);
        if ($actualite) {
            $actualite->actu_nb_views++;
            $actualite->save();

            return view('sige_app.frontend.actualites.details_actu', compact(['actualite']));
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $actualite = Actualite::where('actu_code', $id);
            if ($actualite->first()) {
                $del_res = RessourceActu::where('actu_code', $id)->delete();
                $path = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'actualites'.DIRECTORY_SEPARATOR.$actualite->first()->actu_code;
                File::deleteDirectory($path);
            }
            $res = $actualite->delete();
            DB::commit();
            if ($res) {
                $success = 'Actualité supprimée avec success';

                return redirect()->back()->with(compact('success'));
            } else {
                $errors = "Echec de suppression de l'actualité";

                return redirect()->back()->with(compact('errors'));
            }
        } catch (Throwable $th) {
            DB::rollback();
            $errors = "Echec de suppression de l'actualité";

            return redirect()->back()->with(compact('errors'));
        }

    }

    public function generateActuId()
    {
        $id = Str::upper(Str::random(5));
        if (Actualite::where('actu_code', $id)->count() > 0) {
            return $this->generateActuId();
        }

        return 'ACTU_'.Str::upper($id);
    }
}
