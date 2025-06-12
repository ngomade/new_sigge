@extends("sige_app.backend.template.backend")
@section("js")
    <script>
        function chargeRole(code){
            val = code.split("-")[0]
            type = code.split("-")[1]
            document.getElementById("id_user").value = val
            document.getElementById("type_op").value = type
            $("#addRoleModal").modal("show");
        }

        function chargePerm(code){
            val = code.split("-")[0]
            type = code.split("-")[1]
            document.getElementById("id_user_p").value = val
            document.getElementById("type_op_p").value = type
            $("#addPermModal").modal("show");
        }
    </script>
@endsection
@section("content")
<?php $user = \Session::get("user");?>

<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'un  rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/add_role_pers" method="post" >
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <select name="role_name" id="role_name" class="form-select">
                                @foreach (Spatie\Permission\Models\Role::all() as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="id_user" id="id_user">
                        <input type="hidden" name="type_op" id="type_op">
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-success p-1">Valider <i class='bx bxs-plus-circle'></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addPermModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'un  rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/add_perm_pers" method="post" >
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <select name="perm_name" id="perm_name" class="form-select">
                                @foreach (Spatie\Permission\Models\Permission::all() as $perm)
                                    <option value="{{$perm->id}}">{{$perm->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="id_user_p" id="id_user_p">
                        <input type="hidden" name="type_op_p" id="type_op_p">
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-success p-1">Valider <i class='bx bxs-plus-circle'></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

  <div class="row" style="justify-content: center;">
        <div class="col-sm-12">
            <div class="card" style="width: 90%; margin:auto;">

                <div class="card-header" style="text-align: right;">
                    <h2 style="float: left;">Nos Personnels</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="filterTable">
                          <thead>
                            <tr>
                              <th>N° </th>
                              <th>Code</th>
                              <th>Noms</th>
                              <th>Prénoms</th>
                              <th>Roles</th>
                              <th>Role+</th>
                              <th>Role-</th>
                              <th>Permissions</th>
                              <th>Perm+</th>
                              <th>Perm-</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach (\App\Models\Personnel::orderBy("nom_pers")->get() as $user)
                            <tr>
                                <td> {{$loop->index +1}}  </td>
                                <td>{{$user->code_pers}}</td>
                                <td> {{$user->nom_pers}}  </td>
                                <td>{{$user->prenom_pers}}</td>
                                <td>
                                    @foreach ($user->roles->pluck('name') as $r)
                                            <li>{{$r}}</li>
                                    @endforeach
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-outline-primary rounded p-1" onclick="chargeRole('{{$user->code_pers}}'+'-add')"><i class='bx bxs-plus-circle'></i> </button>
                                </td>
                                <td>
                                    <button onclick="chargeRole('{{$user->code_pers}}'+'-minus')"  class="btn btn-outline-danger rounded p-1"><i class='bx bxs-minus-circle'></i></button>
                                </td>
                                <td>
                                    <ul>
                                        @foreach ($user->permissions->pluck('name') as $r)
                                            <li>{{$r}}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <button  onclick="chargePerm('{{$user->code_pers}}'+'-add')"  class="btn btn-outline-primary rounded p-1"><i class='bx bxs-plus-circle'></i></button>
                                </td>
                                <td>
                                    <button onclick="chargePerm('{{$user->code_pers}}'+'-minus')"  class="btn btn-outline-danger rounded p-1"><i class='bx bxs-minus-circle'></i></button>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                </div>
              </div>
        </div>
  </div>
@endsection
