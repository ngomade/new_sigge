@extends("sige_app.backend.template.backend")
@section("content")
<?php $user = \Session::get("user");?>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'un service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/ajouter_service" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                            <select name="code_division" id="code_division" class="form-select">
                               @foreach (\App\Models\Division::all() as $division)
                                   <option value="{{$division->code_division}}"> {{$division->label_div}} </option>
                               @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                        <input type="text" class="form-control" placeholder="Code Service" name="code_serv" id="code_serv" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                        <input type="text" class="form-control" placeholder="Label du service" name="label_serv" id="label_serv" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                            <textarea class="tinymce-editor w-100" name="desc_serv" id="desc_serv" placeholder="Veuillez faire une breve description du service ici" rows="8">
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
  <div class="card" style="width: 90%; margin:auto;">

    <div class="card-header" style="text-align: right;">
        <h2 style="float: left;">Nos Services</h2>
        <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#addModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>N° </th>
                  <th>Division</th>
                  <th>Code Service</th>
                  <th>Label</th>
                  <th>Description</th>
                  <th>date de création</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach (\App\Models\Service::orderBy("code_division")->get() as $service)
                <tr>
                    <td> {{$loop->index +1}}  </td>
                    <td>{{$service->code_division}}</td>
                    <td> {{$service->code_serv}}  </td>
                    <td>{{$service->label_serv}}</td>
                    <td style="width: 30%; overflow: hidden;"> {!!$service->desc_serv!!}  </td>
                    <td> {{$service->created_at->format("d/m/Y H:i")}}  </td>
                    <td style="text-align: center;">
                        <a href="/delete_service/{{$service->code_serv}}"  class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                        <a href="/update_service/{{$service->code_serv}}"  class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
    </div>
  </div>
@endsection
