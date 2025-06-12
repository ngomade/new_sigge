@extends("sige_app.backend.template.backend")
@section("content")
<?php $user = \Session::get("user");?>
<div class="modal fade" id="SemestreAddModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color: white">
                <h5 class="modal-title" style="color: white;" >Ajout d'une grille</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/ajouter_grille" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-10 mb-2">
                        <input type="text" class="form-control" placeholder="Code de la grille" name="code_grille" id="code_grille" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-10">
                            <select name="code_sem" id="code_sem" class="form-select">
                               @foreach (\App\Models\Semestre::all() as $semestre)
                                   <option value="{{$semestre->code_sem}}"> {{$semestre->label_sem}}</option>
                               @endforeach
                            </select>
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
  <div class="card" style="width: 60%; margin:auto;">

    <div class="card-header" style="text-align: right;">
        <h2 style="float: left;">Nos Grilles</h2>
        <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#SemestreAddModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Code Grille</th>
                  <th>Semestre</th>
                  <th>Nombre d'UEs</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach (\App\Models\Grille::all() as $grille)
                <tr>
                    <td> {{$grille->code_grille}}  </td>
                    <td><span class="badge bg-label-primary me-1"> {{\App\Models\Semestre::where("code_sem", $grille->code_sem)->first()->label_sem}}</span></td>
                    <td> {{\App\Models\Ue::where("code_grille",$grille->code_grille)->count()}} </td>
                    <td>
                      <div class="dropdown">
                        <button
                          type="button"
                          class="btn p-0 dropdown-toggle hide-arrow"
                          data-bs-toggle="dropdown"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="javascript:void(0);"
                            ><i class="bx bx-edit-alt me-1"></i>Modifier</a
                          >
                          <a class="dropdown-item" href="/delete_grille/{{$grille->code_grille}}"
                            ><i class="bx bx-trash me-1"></i> Supprimer</a
                          >
                        </div>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
    </div>
  </div>
@endsection
