@extends("sige_app.backend.template.backend")
@section("content")
<?php $user = \Session::get("user");?>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'un {{$type_bureau}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/ajouter_bureau" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                        <input type="text" class="form-control" placeholder="Code " name="code_bureau" id="code_bureau" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                        <input type="text" class="form-control" placeholder="Label" name="label_bureau" id="label_bureau" required>
                        </div>
                    </div>
                    <input type="hidden" value="{{$type_bureau}}" name="type_bureau">
                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                            <textarea class="tinymce-editor w-100" name="desc_bureau" id="desc_bureau" placeholder="Veuillez faire une brève description ici" rows="8">
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

<div class="modal fade" id="presentModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header bg-primary p-2" style="color: white">
              <h5 class="modal-title" style="color: white">Présentation d'un {{$type_bureau}}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="/presentation_bureau" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="modal-body">
                <div class="row mt-2">
                    <div class="col-sm-8 m-auto">
                        <select name="code_bureau" id="code_bureau" class="form-select">
                           @foreach (\App\Models\Bureau::all() as $bureau)
                               <option value="{{$bureau->code_bureau}}"> {{$bureau->label_bureau}} </option>
                           @endforeach
                        </select>
                    </div>
                </div>
                <div class="row m-auto justify-content-center">
                    <div class="col-sm-6">
                        <div class="row m-auto mt-3">
                            <div class="col-sm-5 m-auto">
                                <label for="depliant">Grade et nom du CD<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="text" class="form-control" name="nom_chef" id="nom_chef" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row m-auto mt-3">
                            <div class="col-sm-4 m-auto">
                                <label for="photo_chef">Image du chef<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-8 m-auto">
                                <input type="file" class="form-control" name="photo_chef" id="photo_chef" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row m-auto mt-3">
                            <div class="col-sm-6 m-auto">
                                <label for="depliant">Dépliant:Cursus Ingenieur<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-6 m-auto">
                                <input type="file" class="form-control" name="depliant_ingenieur" id="depliant_ingenieur" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row m-auto mt-3">
                            <div class="col-sm-6 m-auto">
                                <label for="depliant">Dépliant:Science de l'ingenieur<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-6 m-auto">
                                <input type="file" class="form-control" name="depliant_science" id="depliant_science" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="message_chef" id="message_chef" placeholder="Message du chef de département" rows="8">
                        </textarea>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="cursus_ing" id="cursus_ing" placeholder="Filière du Cursus Ingénieur" rows="8">
                        </textarea>
                    </div>
                </div>
                <div class="row m-auto mt-2 w-1" >
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 1 <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_1" id="document_1" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 2 <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_2" id="document_2" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-auto mt-2 w-1" >
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 3</label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_3" id="document_3">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 4</label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_4" id="document_4">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-auto mt-2 w-1" >
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 5</label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_5" id="document_5">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="grille_ing" id="grille_ing" placeholder="Grille des programmes du Cursus Ingénieur" rows="8">
                        </textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="science_ing" id="science_ing" placeholder="Filière du Cursus Science de l'Ingenieur" rows="8">
                        </textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="grille_science" id="grille_science" placeholder="Grille des programmes Science de l'Ingenieur" rows="8">
                        </textarea>
                    </div>
                </div>
                <div class="row m-auto mt-2 w-1" >
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 1 <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_6" id="document_6" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 2 <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_7" id="document_7" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-auto mt-2 w-1" >
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 3</label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_8" id="document_8">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 4 </label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_9" id="document_9">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-auto mt-2 w-1" >
                    <div class="col-sm-6 m-auto">
                        <div class="row m-auto">
                            <div class="col-sm-3 m-auto">
                                <label for="photo_chef">Flyer 5</label>
                            </div>
                            <div class="col-sm-7 m-auto">
                                <input type="file" class="form-control" name="document_10" id="document_10">
                            </div>
                        </div>
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
        <h2 style="float: left;">Nos {{$type_bureau}}s</h2>
        <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#addModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
        <button class="btn btn-success" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#presentModal">Présentation &nbsp; <i class="ri-add-circle-fill"></i></button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>N° </th>
                  <th>Code </th>
                  <th>Label</th>
                  <th>Description</th>
                  <th>date de création</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach (\App\Models\Bureau::where("type_bureau", $type_bureau)->get() as $bureau)
                <tr>
                    <td> {{$loop->index +1}}  </td>
                    <td> {{$bureau->code_bureau}}  </td>
                    <td>{{$bureau->label_bureau}}</td>
                    <td title="{!!$bureau->desc_bureau!!}" style="width: 30%; overflow: hidden;">{!!$bureau->desc_bureau!!}  </td>
                    <td> {{$bureau->created_at !=null? $bureau->created_at->format("d/m/Y H:i"): "" }}  </td>
                    <td style="text-align: center;">
                        <a href="/delete_bureau/{{$type_bureau}}/{{$bureau->code_bureau}}"  class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                        <a href="/update_bureau/{{$bureau->code_bureau}}"  class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
    </div>
  </div>
@endsection
