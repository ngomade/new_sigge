@extends("sige_app.backend.template.backend")
@section("content")
<?php $user = \Session::get("user");?>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'un Personnel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/ajouter_personnel" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row mt-2 mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="nom_pers" class="col-sm-2 col-form-label">Nom <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" required name="nom_pers" placeholder="Noms">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="prenom_pers" class="col-sm-2 col-form-label">Prénoms </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" required name="prenom_pers" placeholder="Prénoms">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="sexe_pers" class="col-sm-2 col-form-label">Sexe <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="sexe_pers" id="sexe_pers" class="form-select" >
                                        <option value="MASCULIN">Homme</option>
                                        <option value="FEMININ">Femme</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="date_naissance_pers" class="col-sm-4 col-form-label">Date de Naissance <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-calendar-2-line"></i></span>
                                        <input type="date" class="form-control" name="date_naissance_pers" required id="date_naissance_pers" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="lieu_naissance_pers" class="col-sm-4 col-form-label">Lieu de Naissance<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-map-pin-user-fill"></i></span>
                                        <input type="text" class="form-control" name="lieu_naissance_pers" placeholder="Exp: Ambam" required id="lieu_naissance_pers" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="statut_mat_pers" class="col-sm-4 col-form-label">Statut Matrimoniale<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select name="statut_mat_pers" id="statut_mat_pers" class="form-select">
                                        <option value="CELIBATAIRE">Célibataire</option>
                                        <option value="MARIE">Marié</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="lieu_resi_pers" class="col-sm-4 col-form-label" title="SMS ou WhatsApp"> Lieu de Résidence </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-map-pin-fill"></i></span>
                                        <input type="text" class="form-control" name="lieu_resi_pers" id="lieu_resi_pers" aria-describedby="basic-addon1" placeholder="Exp: Ville/Quartier">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="cni_pers" class="col-sm-4 col-form-label">N° CNI ou Passport <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-bank-card-line"></i></span>
                                        <input type="text" class="form-control" name="cni_pers" required id="cni_pers" aria-describedby="basic-addon1" placeholder="Exp: 05815364745 ou KIT158" minlength="6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="email_pers" class="col-sm-3 col-form-label">Email<span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-mail-fill"></i></span>
                                        <input type="mail" class="form-control" name="email_pers" required placeholder="Exp: julious1254@gmail.com" id="email_pers" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="date_deliv_cni_pers" class="col-sm-4 col-form-label" title="Date de délivrance">Date de délivrance CNI</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-calendar-2-line"></i></span>
                                        <input type="date" class="form-control" name="date_deliv_cni_pers" id="date_deliv_cni_pers" required aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="first_phone_pers" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">N° Telephone 1<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                        <input type="tel" class="form-control" name="first_phone_pers" required id="first_phone_pers" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="second_phone_pers" class="col-sm-3 col-form-label" title="SMS ou WhatsApp">N° Telephone 2</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                        <input type="tel" class="form-control" name="second_phone_pers" id="second_phone_pers" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="login_pers" class="col-sm-3 col-form-label">Login<span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="login_pers" required placeholder="Exp: ATANGANA" id="login_pers" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="pwd_pers" class="col-sm-4 col-form-label" title="Mot de passe">Mot de Passe</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="pwd_pers" id="pwd_pers" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="nbre_enfant_pers" class="col-sm-4 col-form-label">Nombre d'enfants<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="nbre_enfant_pers" required placeholder="Exp: 2" id="nbre_enfant_pers" aria-describedby="basic-addon1" min="0" max="20">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="nationalite_pers" class="col-sm-4 col-form-label" title="Nationalité">Nationalité</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="ri-earth-fill"></i></span>
                                        <input type="text" class="form-control" onkeyup="chargeRegion(this.value)" placeholder="CAMEROUN" name="nationalite_pers" id="nationalite_pers" required >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6" id="region">
                            <div class="row">
                                <label for="region_pers" class="col-sm-5 col-form-label">Région D'origine<span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <select class="form-select" name="region_pers" id="region_pers" onchange="changeDepart(this.value)">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" id="departement">
                            <div class="row">
                                <label for="depart_pers" class="col-sm-4 col-form-label">Département D'origine<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="depart_pers" id="depart_pers" onchange="chargeArrond(this.value)">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="arrond_pers" class="col-sm-5 col-form-label">Arrondissement d'origine<span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="arrond_pers" required placeholder="Exp: AMBAM" id="arrond_pers" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="photo_pers" class="col-sm-4 col-form-label" title="Date de délivrance">Photo de Profil</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="photo_pers" id="photo_pers" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 p-2">
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="langue_pers" class="col-sm-4 col-form-label">Langue Principale<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select name="langue_per" id="langue_pers" class="form-select">
                                        <option value="Français">Français</option>
                                        <option value="Anglais">Anglais</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <label for="type_pers" class="col-sm-4 col-form-label" title="">Type de Personnel</label>
                                <div class="col-sm-5">
                                   <select name="type_pers" id="type_pers" class="form-select">
                                    @foreach (\Spatie\Permission\Models\Role::all() as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                   </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-11 m-auto">
                            <textarea class="tinymce-editor w-100" name="bibiographie_pers" id="bibiographie_pers" placeholder="Veuillez faire votre biographie ici" rows="8">
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
        <div class="row" style="justify-content: center;">
            <h2 class="col-sm-3" style="text-align: left;">Notre Personnel</h2>
            <div class="col-sm-7">
                <input type="text" id="filterInput" onkeyup="filterFunction()" placeholder="Filtre de recherche" class="form-control">
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#addModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
            </div>
        </div>
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
                  <th>Sexe</th>
                  <th>Téléphone</th>
                  <th>Email</th>
                  <th>Numéro CNI</th>
                  <th>SM</th>
                  <th>Photo</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach (\App\Models\Personnel::orderBy("nom_pers")->get() as $personnel)
                <tr>
                    <td> {{$loop->index +1}}  </td>
                    <td>{{$personnel->code_pers}}</td>
                    <td> {{$personnel->nom_pers}}  </td>
                    <td>{{$personnel->prenom_pers}}</td>
                    <td>{{\Str::substr($personnel->sexe_pers , 0,1)}}</td>
                    <td>{{$personnel->first_phone_pers}}</td>
                    <td>{{$personnel->email_pers}}</td>
                    <td>{{$personnel->cni_pers}}</td>
                    <td>{{\Str::substr($personnel->statut_mat_pers , 0,1)}}</td>
                    <td style="text-align: center;">
                        @if ($personnel->photo_pers != null)
                        <img src="{{asset("storage/profils")."/".$personnel->photo_pers}}" alt="Photo du personnel" class="rounded" width="50px" height="50px" style="border-radius:50px;">
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="/delete_personnel/{{$personnel->code_pers}}"  class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                        <a href="/update_personnel/{{$personnel->code_pers}}"  class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                        <a href="/show_personnel/{{$personnel->code_pers}}"  class="btn-outline-success rounded p-1"><i class='bx bx-eye'></i> </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
    </div>
  </div>
@endsection
