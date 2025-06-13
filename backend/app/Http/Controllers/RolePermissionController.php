<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{

    public function index()
    {
        return view('sige_app.backend.administration.role_perm');
    }

    public function create()
    {
        return view("sige_app.backend.administration.assignation_role_perm");
    }


    public function ajouter_role(Request $request)
    {
        try {
            $exist = Role::where("name", $request->name)->count() > 0;
            if (!$exist) {
                $res = Role::create($request->all());
                if($res){
                    $success = "Rôle modifié avec success";
                    return redirect("/gestion_role_perm")->with(compact("success"));
                }else{
                    $errors= "Echec de l'enregistrement cccsc";
                    return redirect("/gestion_role_perm")->with(compact("errors"));
                }
            } else {
                $errors= "Ce rôle existe déjà";
                return redirect("/gestion_role_perm")->with(compact("errors"));
            }

        } catch (\Throwable $th) {
            $errors= "Echec de l'enregistrement".$th;
            return redirect("/gestion_role_perm")->with(compact("errors"));
        }
    }

    public function ajouter_perm(Request $request)
    {
        try {
            $exist = Permission::where("name", $request->name)->count() > 0;
            if (!$exist) {
                $res = Permission::create($request->all());
                if($res){
                    $success = "Permission modifié avec success";
                    return redirect("/gestion_role_perm")->with(compact("success"));
                }else{
                    $errors= "Echec de l'enregistrement";
                    return redirect("/gestion_role_perm")->with(compact("errors"));
                }
            }else{
                $errors= "Cette permission existe déjà";
                return redirect("/gestion_role_perm")->with(compact("errors"));
            }

        } catch (\Throwable $th) {
            $errors= "Echec de l'enregistrement".$th;
            return redirect("/gestion_role_perm")->with(compact("errors"));
        }
    }

    public function add_role_pers(Request $request)
    {
        $user = Personnel::find($request->id_user);
        if ($user == null) {
            $user = User::find($request->id_user_p);
        }
        if ($request->type_op == "add") {
            if ($user->hasRole(Role::find($request->role_name))) {
                $errors= "L'utilisateur possède déjà ce rôle";
                return redirect("/assignation_index")->with(compact("errors"));
            }else{
                $user->assignRole(Role::find($request->role_name));
                $success = "Rôle ajouter avec success";
                return redirect("/assignation_index")->with(compact("success"));
            }
        } else {
            if ($user->hasRole(Role::find($request->role_name))) {
                $user->removeRole(Role::find($request->role_name));
                $success= "Rôle Retirer avec success";
                return redirect("/assignation_index")->with(compact("success"));
            }else{
                $errors = "L'utilisateur ne possède pas ce rôle";
                return redirect("/assignation_index")->with(compact("errors"));
            }
        }
    }

    public function add_perm_pers(Request $request)
    {
        $user = Personnel::find($request->id_user_p);
        if ($user == null) {
            $user = User::find($request->id_user_p);
        }
        if ($request->type_op_p == "add") {
            if ($user->hasDirectPermission(Permission::find($request->perm_name))) {
                $errors= "L'utilisateur possède déjà cette permission";
                return redirect("/assignation_index")->with(compact("errors"));
            }else{
                $user->givePermissionTo(Permission::find($request->perm_name));
                $success = "Permission ajouter avec success";
                return redirect("/assignation_index")->with(compact("success"));
            }
        } else {
            if ($user->hasDirectPermission(Permission::find($request->perm_name))) {
                $user->revokePermissionTo(Permission::find($request->perm_name));
                $success= "Permission Retirer avec success";
                return redirect("/assignation_index")->with(compact("success"));
            }else{
                $errors = "L'utilisateur ne possède pas cette permission";
                return redirect("/assignation_index")->with(compact("errors"));
            }
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $res = Role::destroy($id);
            DB::commit();
            $success = "Rôle Supprimé avec success";
            return redirect("/gestion_role_perm")->with(compact(["success"]));
        } catch (\Throwable $th) {
            $errors = "Echec de suppression du rôle".$th;
            return redirect("/gestion_role_perm")->with(compact(["errors"]));
        }
    }


    public function delete_perm($id)
    {
        try {
            DB::beginTransaction();
            $res = Permission::destroy($id);
            DB::commit();
            $success = "Permission Supprimé avec success";
            return redirect("/gestion_role_perm")->with(compact(["success"]));
        } catch (\Throwable $th) {
            $errors = "Echec de suppression de la permission".$th;
            return redirect("/gestion_role_perm")->with(compact(["errors"]));
        }
    }
}
