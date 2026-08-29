<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Image;
use Throwable;

class CarousselController extends Controller
{
    public function index()
    {
        return view('sige_app.backend.caroussel.form_add_slide');
    }

    public function list_slide()
    {
        $slides = Slide::orderBy('id', 'desc')->paginate(20);

        return view('sige_app.backend.caroussel.listing_slide', compact('slides'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $val = $this->validate(
            $request,
            [
                'first_title' => 'required|min:10',
                'second_title' => 'required|min:10',
            ]
        );
        try {
            DB::beginTransaction();
            $id = $this->generateSlideId();
            $pictfile = $request->file('photo');
            $nom = $id.'.'.$pictfile->extension();
            $res = Slide::create(array_merge($request->all(), [
                'code_pers' => Session::get('pers')->code_pers,
                'photo' => $nom,
            ]));
            if ($res) {
                $image_extension = ['png', 'jpg', 'gif', 'bmp'];
                $path = '/public/slides/';
                if (! Storage::exists($path)) {
                    Storage::makeDirectory($path, 0775, true);
                }
                $nom = 'slide_'.$res->id.'.'.$pictfile->extension();
                $res->update(['photo' => $nom]);
                if (($pictfile != null) && (in_array($pictfile->extension(), $image_extension))) {
                    $pictfile->storeAs($path, $nom);
                    $file = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'slides'.DIRECTORY_SEPARATOR.$nom;
                    $img = Image::make($file)->resize(null, 768, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($file);
                }
            }
            DB::commit();
            $success = 'Votre Photo  a été publié avec success';
            $request->session()->flash('success', $success);

            return back();
        } catch (Throwable $th) {
            DB::rollback();

            return back()->withInput($request->all())->withErrors($val);
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $res = Slide::where('id', $id);
            if ($res) {
                if ($res->first()) {
                    $path = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'slides'.DIRECTORY_SEPARATOR.$res->first()->photo;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }
                $res->delete();
                DB::commit();
                $success = 'Slide supprimée avec success';

                return redirect()->back()->with(compact('success'));
            } else {
                $errors = 'Echec de suppression du Slide';

                return redirect()->back()->with(compact('errors'));
            }
        } catch (Throwable $th) {
            DB::rollback();
            $errors = 'Echec de suppression du Slide';

            return redirect()->back()->with(compact('errors'));
        }
    }

    public function generateSlideId()
    {
        $nb = Slide::count();

        return 'slide_'.($nb + 1);
    }
}
