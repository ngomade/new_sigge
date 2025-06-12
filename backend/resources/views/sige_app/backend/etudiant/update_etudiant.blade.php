@extends("sige_app.backend.template.backend")
@section("js")
    <script>
        var previewPicture  = function (e) {
        var image = document.getElementById("image_carte");
        const [picture] = e.files
        if (picture) {
            image.src = URL.createObjectURL(picture)
        }
}
    </script>
@endsection
@section("content")
<div class="card" style="width: 100%; margin:auto; background-color: rgb(255, 250, 250)">
    <div class="card-header pb-0" style="text-align: right; border-bottom: 1px solid rgb(217, 217, 217);">
        <h3 style="text-align: center; text-transform: uppercase">Mise à jour Profil</h3>
    </div>
    <div class="card-body">
        <div class="row" style="justify-content: center;">
            <div class="col-sm-3">
                {{-- @if($u->hasRole("ADMIN")) --}}
                    <div class="card mt-1" style="width:100%;">
                        <div class="card-header title_update">Changement de Filière</div>
                        <div class="card-body p-2">
                        <form action="/change_filiere" method="post" style="justify-content: center;">
                            {{ csrf_field() }}
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" readonly value="{{$fil}}" name="old_filiere">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mt-3">
                                    <select name="new_filiere" id="new_filiere" class="form-select">
                                        @foreach (\App\Models\Filiere::all() as $filiere)
                                            @if(!($filiere->code_filiere == $fil))
                                            <option value="{{$filiere->code_filiere}}" > {{$filiere->code_filiere}} -- {{$filiere->label_filiere}} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                <input type="hidden" value="{{$u->code_user}}" name="code_user">
                            <div class="row">
                                <div class="col-sm-2 m-auto mt-3 mb-0">
                                    <button type="submit" class="btn btn-outline-primary">Valider</button>
                                </div>
                            </div>
                        </form>
                        </div>
                  </div>
                {{-- @endif --}}

                <div class="card mt-1" style="width:100%;">
                    <div class="card-header title_update">Changement d'école</div>
                    <div class="card-body p-2">
                    <form action="/change_ecole" method="post" style="justify-content: center;">
                        {{ csrf_field() }}
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" readonly value="{{$u->ecole_user}}" name="old_code_ecole">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mt-3">
                                <select name="new_code_ecole" id="new_code_ecole" class="form-select">
                                    @if($u->ecole_user == "ESTLC")
                                        <option value="ISLAPE">ISLAPE</option>
                                    @endif
                                    @if($u->ecole_user == "ISLAPE")
                                        <option value="ESTLC">ESTLC</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                            <input type="hidden" value="{{$u->code_user}}" name="code_user">
                        <div class="row">
                            <div class="col-sm-2 m-auto mt-3 mb-0">
                                <button type="submit" class="btn btn-outline-primary">Valider</button>
                            </div>
                        </div>
                    </form>
                    </div>
              </div>

                <div class="card mt-3" style="width:100%;">
                    <div class="card-header title_update">Réinitilaisation de Mot de Passe</div>
                    <div class="card-body p-0">
                      <form action="/change_pwd" method="post" style="justify-content: center;">
                          {{ csrf_field() }}
                          <div class="row mt-2 mb-3 p-2">
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" required name="pwd_user" placeholder="Entrer le nouveau mot de passe">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-outline-primary">Valider</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="{{$u->code_user}}" name="code_user">
                      </form>
                    </div>
                </div>

                <div class="card mt-3" style="width:100%;">
                    <div class="card-header title_update">Photo de Profil</div>
                    <div class="card-body p-0">
                      <form action="/change_photo" method="post" style="justify-content: center;" enctype="multipart/form-data">
                          {{ csrf_field() }}
                          <p class="p-1 text-center text-secondary">Veuillez importer votre demi photo 4x4 qui sera présente sur votre carte</p>
                          <div class="row mt-2 mb-3 p-2">
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="file" accept="image/png, image/jpeg, image/gif, image/bmp" required class="form-control" required name="photo_user" placeholder="Votre photo" onchange="previewPicture(this)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-outline-primary">Valider</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5 offset-6 m-auto">
                                    <img src="#" alt="" id="image_carte" style="width: 40mm; height: 40mm;">
                                </div>
                            </div>
                            <input type="hidden" value="{{$u->code_user}}" name="code_user">
                      </form>
                    </div>
                </div>
                <?php $info_extra =\App\Models\InfoExtra::firstWhere("code_info_extra", $u->code_info_extra); ?>
                @if($info_extra != null)
                    <div class="card mt-3" style="width:100%;">
                        <div class="card-header title_update">Informations Suppélmentaires</div>
                        <div class="card-body p-0">
                            <form action="/change_info_sup" method="post" style="justify-content: center;">
                                {{ csrf_field() }}
                                <div class="row mt-2 mb-1 p-2">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" required name="nom_pere_user" placeholder="Nom du père" value="{{$info_extra->nom_pere_user}}" id="nom_pere_user">
                                            <label for="nom_pere_user">Téléphone du tuteur ou père</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2 p-2">
                                    <div class="col-sm-12 ">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="698576312" value="{{$info_extra->telephone_tuteur_user}}" id="telephone_tuteur_user"/>
                                            <label for="telephone_tuteur_user">Téléphone du tuteur ou père</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2 p-2">
                                    <div class="col-sm-12 ">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" required name="nom_mere_user" placeholder="Nom de la mère" value="{{$info_extra->nom_mere_user}}" id="nom_mere_user">
                                            <label for="nom_mere_user">Nom de la mère</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2 p-2">
                                    <div class="col-sm-12 ">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" required name="telephone_mere" placeholder="Téléphone de la mère" value="{{$info_extra->telephone_mere}}">
                                            <label for="telephone_mere">Téléphone de la mère</label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="{{$info_extra->code_info_extra}}" name="code_info_extra">
                                <div class="row">
                                    <div class="col-sm-2 m-auto mt-3 mb-0">
                                        <button type="submit" class="btn btn-outline-primary">Valider</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-sm-9">
                <div class="card mt-1" style="width:100%;">
                  <div class="card-header title_update">Informations personnelles</div>
                  <div class="card-body mb-0 p-2">
                    <form action="/change_info_pers" method="post" style="justify-content: center;">
                        {{ csrf_field() }}
                        <div class="row mt-2 mb-3 p-2">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="nom_user" class="col-sm-2 col-form-label">Nom <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{$u->nom_user}}" required name="nom_user" placeholder="Noms">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="prenom_user" class="col-sm-2 col-form-label">Prénoms </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{$u->prenom_user}}" required name="prenom_user" placeholder="Prénoms">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 p-2">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="sexe_user" class="col-sm-2 col-form-label">Sexe <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="sexe_user" id="sexe_user" class="form-select" >
                                            <option value="MASCULIN"  @if ($u->sexe_user == "MASCULIN") selected @endif>Homme</option>
                                            <option value="FEMININ" @if ($u->sexe_user == "FEMININ") selected @endif>Femme</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="date_naissance_user" class="col-sm-4 col-form-label">Date de Naissance <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-calendar-2-line"></i></span>
                                            <input type="date" class="form-control" name="date_naissance_user" required id="date_naissance_user" aria-describedby="basic-addon1" value="{{Str::substr($u->date_naissance_user, 0, 10)}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 p-2">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="lieu_naissance_user" class="col-sm-4 col-form-label">Lieu de Naissance<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-map-pin-user-fill"></i></span>
                                            <input type="text" class="form-control" name="lieu_naissance_user" placeholder="Exp: Ambam" required id="lieu_naissance_user" aria-describedby="basic-addon1" value="{{$u->lieu_naissance_user}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="first_phone_user" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">N° Telephone 1<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                            <input type="tel" class="form-control" name="first_phone_user" required id="first_phone_user" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9" value="{{$u->first_phone_user}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 p-2">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="second_phone_user" class="col-sm-3 col-form-label" title="SMS ou WhatsApp">N° Telephone 2</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                            <input type="tel" class="form-control" name="second_phone_user" id="second_phone_user" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9"  value="{{$u->second_phone_user}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="numero_cni_user" class="col-sm-4 col-form-label">N° CNI <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-bank-card-line"></i></span>
                                            <input type="text" class="form-control" name="numero_cni_user" required id="numero_cni_user" aria-describedby="basic-addon1" placeholder="Exp: 05815364745 ou KIT158" minlength="6" value="{{$u->numero_cni_user}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4 p-2">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="email_user" class="col-sm-3 col-form-label">Email<span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-mail-fill"></i></span>
                                            <input type="mail" class="form-control" name="email_user" required placeholder="Exp: julious1254@gmail.com" id="email_user" aria-describedby="basic-addon1" value="{{$u->email_user}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="lieu_resi_user" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">Résidence </label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-map-pin-fill"></i></span>
                                            <input type="text" class="form-control" name="lieu_resi_user" id="lieu_resi_user" aria-describedby="basic-addon1" placeholder="Exp: Ville/Quartier" value="{{$u->lieu_resi_user}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 p-2">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="statut_mat_user" class="col-sm-4 col-form-label">Statut Matrimoniale</label>
                                    <div class="col-sm-8">
                                        <select name="statut_mat_user" id="statut_mat_user" class="form-select">
                                            <option value="CELIBATAIRE" @if ($u->statut_mat_user == "CELIBATAIRE") selected @endif>Célibataire</option>
                                            <option value="MARIE" @if ($u->statut_mat_user == "MARIE") selected @endif>Marié</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="langue_user" class="col-sm-4 col-form-label">1 <sup>iere</sup> Langue <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-flag-fill"></i></span>
                                            <select name="langue_user" id="langue_user" class="form-select">
                                                <option value="FRANCAIS" @if ($u->langue_user == "FRANCAIS") selected @endif>Français</option>
                                                <option value="ANGLAIS" @if ($u->langue_user == "ANGLAIS") selected @endif>Anglais</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 p-2">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="nationalite_user" class="col-sm-3 col-form-label" title="SMS ou WhatsApp"> Nationalité <span class="text-danger">*</span> </label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-earth-fill"></i></span>
                                            <input type="text" class="form-control" value="{{$u->nationalite_user}}" onkeyup="chargeRegion(this.value)" placeholder="CAMEROUN" name="nationalite_user" id="nationalite_user" required >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6" id="region">
                                <div class="row">
                                    <label for="region_origine_user" class="col-sm-4 col-form-label">Région D'origine<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-select" name="region_origine_user" id="region_origine_user" onchange="changeDepart(this.value)">
                                            <option value="{{$u->region_origine_user}}">{{$u->region_origine_user}}</option>
                                        </select>
                                        {{-- <input type="text" class="form-control" value="{{$user->region_origine_user}}" placeholder="Sud" name="region_origine_user" id="region_origine"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 p-2">
                            <div class="col-sm-6" id="departement">
                                <div class="row">
                                    <label for="depart_origine_user" class="col-sm-4 col-form-label">Département D'origine<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-select" name="depart_origine_user" id="depart_origine_user" onchange="chargeArrond(this.value)">
                                            <option value="{{$u->depart_origine_user}}">{{$u->depart_origine_user}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6" id="arrondissement">
                                <div class="row">
                                    <label for="arrond_origine_user" class="col-sm-4 col-form-label">Arrond. D'origine<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" placeholder="Exp: AMBAM" name="arrond_origine_user" id="arrond_origine_user" value="{{$u->arrond_origine_user}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{$u->code_user}}" name="code_user">
                        <div class="row">
                            <div class="col-sm-2 m-auto mt-3 mb-0">
                                <button type="submit" class="btn btn-outline-primary">Valider</button>
                            </div>
                        </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
