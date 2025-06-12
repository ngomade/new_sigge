@extends("sige_app.backend.template.backend")
@section("content")
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'un EC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/ajouter_ec" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                            <select name="code_ue" id="code_ue" class="form-select">
                               @foreach (\App\Models\Ue::all() as $ue)
                                   <option value="{{$ue->code_ue}}"> {{$ue->code_ue}}: {{$ue->intitule_ue}} </option>
                               @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                        <input type="text" class="form-control" placeholder="Code EC" name="code_ec" id="code_ec" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                        <input type="text" class="form-control" placeholder="Intitulé EC" name="intitule_ec" id="intitule_ec" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                        <input type="number" class="form-control" placeholder="Nombre de crédit" name="credit_ec" id="credit_ec" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                        <input type="number" class="form-control" placeholder="Volume Horaire" name="vh_ec" id="vh_ec" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                        <input type="number" class="form-control" placeholder="Heure de CM" name="cm_ec" id="cm_ec" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                        <input type="number" class="form-control" placeholder="Heure de TD" name="td_ec" id="td_ec" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                        <input type="number" class="form-control" placeholder="Heure de TP" name="tp_ec" id="tp_ec" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                        <input type="number" class="form-control" placeholder="Heure de TPE" name="tpe_ec" id="tpe_ec" required>
                        </div>
                    </div>
                    <fieldset>
                        <legend>Ressources liées au cours</legend>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                           <select name="type_res" id="type_res" class="form-select">
                            <option value="PDF"> Document PDF</option>
                           </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                            <input type="file" class="form-control" placeholder="Heure de TPE" name="label_res" id="label_res" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                                <textarea class="tinymce-editor w-100" name="desc_res" id="desc_res" placeholder="Veuillez faire une breve description de l'ec ici" rows="8">

                                </textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
  <div class="card" style="width: 90%; margin:auto;">

    <div class="card-header" style="text-align: right;">
        <h2 style="float: left;">Nos Ecs</h2>
        <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#addModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>SEM</th>
                  <th>UEs et ECS</th>
                  {{-- <th>Code EC</th>
                  <th>Intitulé</th>
                  <th>Code UE</th>
                  <th>Crédits</th>
                  <th>VH</th>
                  <th>CM</th>
                  <th>TD</th>
                  <th>TP</th>
                  <th>TPE</th>
                  <th>Actions</th> --}}
                </tr>
              </thead>
              <tbody>
                @foreach ($semestres as $semestre)
                    <tr>
                        <td> {{$semestre->code_sem}}  </td>
                        <td>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Code UE</th>
                                    <th>Code EC</th>
                                    <th>Intitulé</th>
                                    <th>Crédits</th>
                                    <th>VH</th>
                                    <th>CM</th>
                                    <th>TD</th>
                                    <th>TP</th>
                                    <th>TPE</th>
                                    <th>Actions</th>
                                </tr>
                                @foreach ($semestre->ues as $ue)
                                    <tr>
                                        <td rowspan="{{$ue->ecs->count()+1}}"> {{$ue->code_ue}}  </td>
                                    </tr>
                                    @foreach ($ue->ecs as $ec)
                                        <tr>
                                            <td> {{$ec->code_ec}}  </td>
                                            <td><span class="badge bg-label-primary me-1"> {{$ec->intitule_ec}}</span></td>
                                            <td> {{$ec->credit_ec}}  </td>
                                            <td> {{$ec->vh_ec}}  </td>
                                            <td> {{$ec->cm_ec}}  </td>
                                            <td> {{$ec->td_ec}}  </td>
                                            <td> {{$ec->tp_ec}}  </td>
                                            <td> {{$ec->tpe_ec}}  </td>
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
                                                        <a class="dropdown-item" href="/delete_ec/{{$ec->code_ec}}"
                                                            ><i class="bx bx-trash me-1"></i> Supprimer</a
                                                        >
                                                        <a class="dropdown-item" href="/download_ec/{{$ec->code_ec}}"
                                                            target="blank"><i class="ri-folder-download-fill"></i> Télécharger</a
                                                        >
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </table>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
  </div>
@endsection
