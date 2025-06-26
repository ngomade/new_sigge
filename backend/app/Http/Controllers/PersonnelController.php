<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Helper\Helper;
use Throwable;

class PersonnelController extends Controller
{
    public function index()
    {
       return view("sige_app.backend.personnel.personnel");
    }


    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $code_pers = Helper::generate_matricule_pers();
        $pictfile = $request->file('photo_pers');
        try {
            $exist = Personnel::where('first_phone_pers', $request->first_phone_pers)
                ->orWhere("cni_pers", $request->cni_pers)
                ->count();
                if ($exist) {
                    return redirect()->back()->withErrors("Echec de création du personnel. Personnel deja existant")->withInput();
                } else {
                    DB::beginTransaction();
                    $user = Personnel::create(array_merge($request->all(),[
                        'code_pers'          =>$code_pers,
                        'pwd_pers'           =>md5($request->pwd_pers),
                        'photo_pers'      => $code_pers.".".$pictfile->extension(),
                    ]));
                    $role = Role::find($request->type_pers);
                    DB::table('model_has_roles')->insert([
                        "model_type" =>"\App\Models\Personnel",
                        "model_id"  =>$user->code_pers,
                        "role_id"   =>$role->id,
                    ]);
                    $image_extension = ["png", "jpg", "bmp"];
                    if($user){
                        $path ="/public/profils/";
                        if(!Storage::exists($path)){
                            Storage::makeDirectory($path,  0775, true);
                        }
                        if(($pictfile != null) && (in_array($pictfile->extension() , $image_extension)))
                            $pictPath = $pictfile->storeAs($path, "{$code_pers}.{$pictfile->extension()}");
                    }
                    DB::commit();
                    $success="Votre inscription s'est déroulée avec success.";
                    return redirect("/insription_personnel")->with(compact("success"));
            }
        } catch (Throwable $th) {
            DB::rollback();
            $errors = "Echec de création du compte".$th;
            return redirect("/insription_personnel")->with(compact("errors"));
        }
    }

    public function destroy($code)
    {
        try {
            $personnel = Personnel::find($code);
            DB::beginTransaction();
            $res = Personnel::destroy($code);
            DB::commit();
            $path = storage_path().DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR ."profils".DIRECTORY_SEPARATOR.$personnel->photo_pers;
                File::delete($path);
            $success = "Personnel Supprimé avec success";
            return redirect("/insription_personnel")->with(compact(["success"]));
        } catch (Throwable $th) {
            $errors = "Echec de suppression du personnel".$th;
            return redirect("/insription_personnel")->with(compact(["errors"]));
        }
    }
}
