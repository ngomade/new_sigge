<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Response;
use App\Models\Bureau;
use App\Models\Presentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Image;
use Throwable;

class BureauController extends Controller
{

    public function index(string $type_bureau)
    {
        return view("sige_app.backend.administration.bureau", compact("type_bureau"));
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $bureau = Bureau::create($request->all());
            DB::commit();
            if ($bureau != null) {
                $success = $request->type_bureau . " créé avec success";
                return redirect("/bureau/" . $request->type_bureau)->with(compact("success"));
            }
            return redirect()->back()->withErrors("Echec de création du " . $request->type_bureau)->withInput();
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de création du " . $request->type_bureau . " " . $th)->withInput();
        }
    }

    public function store_present(Request $request)
    {

        try {
            DB::beginTransaction();
            $exist = Presentation::where("code_bureau", $request->code_bureau)->count();
            if ($exist == 0) {
                $photo_chef = $request->file('photo_chef');
                $depliant_ingenieur = $request->file('depliant_ingenieur');
                $depliant_science = $request->file('depliant_science');
                $nom_photo = "photo_" . $request->code_bureau . "." . $photo_chef->extension();
                $res = Presentation::create(array_merge($request->all(), [
                    "photo_chef" => $nom_photo
                ]));
                if ($res) {
                    $image_extension = ["png", "jpg", "gif", "bmp"];
                    $path = "/public/departements/" . $request->code_bureau . "/";
                    if (!Storage::exists($path)) {

                        Storage::makeDirectory($path, 0775, true);
                    }
                    if (($photo_chef != null) && (in_array($photo_chef->extension(), $image_extension))) {
                        $photo_chef->storeAs($path, $nom_photo);
                        $file = storage_path() . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "departements" . DIRECTORY_SEPARATOR . $request->code_bureau . DIRECTORY_SEPARATOR . $nom_photo;
                        $img = Image::make($file)->resize(300, 300, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $img->save($file);
                    }
                    DB::commit();
                    $depliant_ingenieur->storeAs($path, "depliant_ingenieur" . "." . $depliant_science->extension());
                    $depliant_science->storeAs($path, "depliant_science" . "." . $depliant_science->extension());
                    for ($i = 1; $i <= 10; $i++) {
                        $nom_fichier = "flyer_science_" . $i;
                        if ($request->file("document_" . $i) != null) {
                            $nom_fichier = "flyer_ingenieur" . $i;
                            if ($i <= 5) {
                                $request->file("document_" . $i)->storeAs($path, $nom_fichier . ".{$request->file("document_".$i)->extension()}");
                            } else {
                                $request->file("document_" . $i)->storeAs($path, $nom_fichier . ".{$request->file("document_".$i)->extension()}");
                            }
                            Document::create([
                                'code_bureau' => $request->code_bureau,
                                'label_doc' => "Flyer " . $i . " Pour le département" . $request->code_bureau,
                                'type_doc' => "Image",
                                'nom_fichier' => $nom_fichier . "." . $request->file("document_" . $i)->extension()
                            ]);
                        }
                    }
                    $success = $request->type_bureau . "Présentation mis créée avec success";
                    return redirect("/bureau/Departement")->with(compact("success"));
                }
                return redirect()->back()->withErrors("Echec de création de la présentation ")->withInput();
            } else {
                return redirect()->back()->withErrors("Cette présentation existe déja veuiller plutot la modifier")->withInput();
            }
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de création  de la présentation" . $th)->withInput();
        }
    }

    public function download_grille($dept, $nom)
    {
        return Response::download(storage_path("app" . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "departements" . DIRECTORY_SEPARATOR . $dept . DIRECTORY_SEPARATOR . $nom . ".pdf"));
    }


    public function presentation_departement(string $id)
    {
        $presentation = Presentation::where("code_bureau", $id)->first();
        $bureau = Bureau::where("code_bureau", $presentation->code_bureau)->first();
        return view("sige_app.frontend.departement.presentation_departement", compact(["presentation", "bureau"]));
    }

    public function destroy(string $type_bureau, string $code_bureau)
    {
        try {
            DB::beginTransaction();
            $res = Bureau::destroy($code_bureau);
            DB::commit();
            $success = $type_bureau . " Supprimé avec success";
            return redirect("/bureau/" . $type_bureau)->with(compact(["success"]));
        } catch (Throwable $th) {
            $errors = "Echec de suppression";
            return redirect("/index_bureau")->with(compact(["errors", "type_bureau"]));
        }
    }
}
