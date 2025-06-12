@extends("sige_app.backend.template.backend")
@section("content")
<?php $user = \Session::get("user");?>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'une UE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/ajouter_ue" method="post">
                {{ csrf_field() }}
                <div class="modal-body" style="align-content: center;">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <select name="code_sem" id="code_sem" class="form-select">
                               @foreach (\App\Models\Semestre::all() as $semestre)
                                   <option value="{{$semestre->code_sem}}"> {{$semestre->code_sem}} -- {{$semestre->label_sem}}</option>
                               @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                        <input type="text" class="form-control" placeholder="Code UE" name="code_ue" id="code_ue" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                        <input type="text" class="form-control" placeholder="Intitulé Ue" name="intitule_ue" id="intitule_ue" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <textarea class="tinymce-editor w-100" name="desc_ue" id="desc_ue" placeholder="Veuillez faire une breve description de l'ue ici" rows="8">

                            </textarea>
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
  <div class="card" style="width: 70%; margin:auto;">

    <div class="card-header" style="text-align: right;">
        <h2 style="float: left;">Nos UEs</h2>
        <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#addModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Semetre</th>
                  <th>Code UE</th>
                  <th>Intitulé</th>
                  <th>ECs</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($semestres as $semestre)
                <tr>
                    <td rowspan="{{$semestre->ues->count()+1}}"> {{$semestre->label_sem}} </td>
                </tr>
                @foreach ($semestre->ues as $ue)
                <tr>
                    <td> {{$ue->code_ue}}  </td>
                    <td><span class="badge bg-label-primary me-1"> {{$ue->intitule_ue}}</span></td>
                    <td> {{\App\Models\Ec::where("code_ue", $ue->code_ue)->count()}}</td>
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
                          <a class="dropdown-item" href="/delete_ue/{{$ue->code_ue}}"
                            ><i class="bx bx-trash me-1"></i> Supprimer</a
                          >
                        </div>
                      </div>
                    </td>
                  </tr>
                @endforeach
                @endforeach
              </tbody>
            </table>
          </div>
    </div>
  </div>
@endsection
