@extends("sige_app.backend.template.backend")
@section("content")
<?php $user = \Session::get("user");?>
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'un rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/ajouter_role" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <input type="text" class="form-control" placeholder="Label du rôle" name="name" id="name" required>
                            <input type="hidden" class="form-control" placeholder="Label du rôle" name="guard_name" id="guard_name" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addPermModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'une permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/ajouter_perm" method="post" >
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <input type="text" class="form-control" placeholder="Label de la permission" name="name" id="name" required>
                            <input type="hidden" class="form-control" placeholder="Label de la permission" name="guard_name" id="guard_name" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
  <div class="row" style="justify-content: center;">
        <div class="col-sm-5">
            <div class="card" style="width: 90%; margin:auto;">

                <div class="card-header" style="text-align: right;">
                    <h2 style="float: left;">Nos Rôles</h2>
                    <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#addRoleModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>N° </th>
                              <th>Label</th>
                              <th>Garde</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach (\Spatie\Permission\Models\Role::all() as $role)
                            <tr>
                                <td> {{$role->id}}  </td>
                                <td> {{$role->name}}  </td>
                                <td>{{$role->guard_name}}</td>
                                <td style="text-align: center;">
                                    <a href="/delete_role/{{$role->id}}"  class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                                    <a href="/update_role/{{$role->id}}"  class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                </div>
              </div>
        </div>
        <div class="col-sm-5">
            <div class="card">

                <div class="card-header" style="text-align: right;">
                    <h2 style="float: left;">Nos permissions</h2>
                    <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#addPermModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>N° </th>
                              <th>Label</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach (\Spatie\Permission\Models\Permission::all() as $permission)
                            <tr>
                                <td> {{$permission->id}}  </td>
                                <td> {{$permission->name}}  </td>
                                <td style="text-align: center;">
                                    <a href="/delete_perm/{{$permission->id}} "  class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                                    <a href="/update_perm/{{$permission->id}} "  class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
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
