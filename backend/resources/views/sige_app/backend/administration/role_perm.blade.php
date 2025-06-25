@extends("sige_app.backend.template.backend")
@section("content")
<?php $user = \Session::get("user");?>

<!-- Modal d'ajout de rôle -->
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
                            <input type="hidden" class="form-control" value="web" name="guard_name" id="guard_name" required>
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

<!-- Modal de modification de rôle -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Modification d'un rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm" method="post">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <input type="text" class="form-control" placeholder="Label du rôle" name="name" id="edit_role_name" required>
                            <input type="hidden" class="form-control" value="web" name="guard_name" id="edit_role_guard_name" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'ajout de permission -->
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
                            <input type="hidden" class="form-control" value="web" name="guard_name" id="guard_name" required>
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

<!-- Modal de modification de permission -->
<div class="modal fade" id="editPermModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Modification d'une permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPermForm" method="post">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <input type="text" class="form-control" placeholder="Label de la permission" name="name" id="edit_perm_name" required>
                            <input type="hidden" class="form-control" value="web" name="guard_name" id="edit_perm_guard_name" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression de rôle -->
<div class="modal fade" id="confirmDeleteRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger p-2">
                <h5 class="modal-title text-white">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bx bx-error-circle text-danger" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Êtes-vous sûr ?</h4>
                    <p class="text-muted">Vous êtes sur le point de supprimer le rôle "<span id="roleToDelete"></span>".</p>
                    <p class="text-danger"><strong>Cette action est irréversible !</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="confirmDeleteRoleBtn" class="btn btn-danger">Oui, supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression de permission -->
<div class="modal fade" id="confirmDeletePermModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger p-2">
                <h5 class="modal-title text-white">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bx bx-error-circle text-danger" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Êtes-vous sûr ?</h4>
                    <p class="text-muted">Vous êtes sur le point de supprimer la permission "<span id="permToDelete"></span>".</p>
                    <p class="text-danger"><strong>Cette action est irréversible !</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="confirmDeletePermBtn" class="btn btn-danger">Oui, supprimer</a>
            </div>
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
                                <button class="btn-outline-danger rounded p-1 border-0" onclick="confirmDeleteRole({{$role->id}}, '{{$role->name}}')"><i class='bx bx-x-circle'></i></button>
                                <button class="btn-outline-success rounded p-1 border-0" onclick="editRole({{$role->id}}, '{{$role->name}}', '{{$role->guard_name}}')"><i class='bx bx-pencil'></i></button>
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
                                <button class="btn-outline-danger rounded p-1 border-0" onclick="confirmDeletePermission({{$permission->id}}, '{{$permission->name}}')"><i class='bx bx-x-circle'></i></button>
                                <button class="btn-outline-success rounded p-1 border-0" onclick="editPermission({{$permission->id}}, '{{$permission->name}}', '{{$permission->guard_name}}')"><i class='bx bx-pencil'></i></button>
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

<script>
function editRole(id, name, guardName) {
    document.getElementById('edit_role_name').value = name;
    document.getElementById('edit_role_guard_name').value = guardName;
    document.getElementById('editRoleForm').action = '/update_role/' + id;
    
    var modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
    modal.show();
}

function editPermission(id, name, guardName) {
    document.getElementById('edit_perm_name').value = name;
    document.getElementById('edit_perm_guard_name').value = guardName;
    document.getElementById('editPermForm').action = '/update_perm/' + id;
    
    var modal = new bootstrap.Modal(document.getElementById('editPermModal'));
    modal.show();
}

function confirmDeleteRole(id, name) {
    document.getElementById('roleToDelete').textContent = name;
    document.getElementById('confirmDeleteRoleBtn').href = '/delete_role/' + id;
    
    var modal = new bootstrap.Modal(document.getElementById('confirmDeleteRoleModal'));
    modal.show();
}

function confirmDeletePermission(id, name) {
    document.getElementById('permToDelete').textContent = name;
    document.getElementById('confirmDeletePermBtn').href = '/delete_perm/' + id;
    
    var modal = new bootstrap.Modal(document.getElementById('confirmDeletePermModal'));
    modal.show();
}
</script>

@endsection