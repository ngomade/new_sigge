@extends("sige_app.frontend.template.frontend")
@section('js')
    <script>
        function present_new(){
            $form = document.getElementById("form-new");
            $($form).fadeIn(2000);
        }
    </script>
@endsection
@section('content')
    <div class="container mt-3">
        <div class="card">
            <div class="card-header p-1 pt-2" style="background-color: green; color:white;">
                <h3>Formulaire d'inscription administrative</h3>
            </div>
          <div class="card-body" style="background-color: rgb(245,245,249);">
                <div class="alert alert-danger">
                    Débutez votre inscription en renseignant les informations utilisées lors du concours.
                    Il s'agit de votre <b>numéro de teléphone</b>ou votre <b>votre numéro de CNI</b>.
                </div>
                <form action="/recherche_candidat" class="mt-2" id="form-recherche" method="post">
                    {{ csrf_field() }}
                    <div class="input-group mb-3 w-50"  style="float: left;">
                        <input type="text" class="form-control" placeholder="Votre Matricule concours, CNI ou Numéro de téléphone" name="motif" required>
                        <button class="btn btn-success" type="submit"><i class="ri-search-line"></i></button>
                    </div>
                    <div class="form-check mb-3 w-40" style="float: right;">
                        <input type="radio" name="not_seen" id="not_seen" onclick="present_new()" class="form-check-input">
                        <label for="not_seen">Je ne retrouve pas mes informations</label>
                    </div>
                </form>
                <hr>
                @isset($exist)
                <div  class="card w-50 m-auto bg-warning" style="padding: 20px; text-align: justify; clear: both;" data-aos="zoom-in-up" data-aos-easing="ease-out-cubic" data-aos-duration="800">
                    {{$exist}}
                </div>
                @endisset
                @isset($user)
                    @if($user != null)
                    <?php $u = $user ?>
                    <div class="card-form" style="clear: both;">
                        <form action="/inscription_administrative" method="POST">
                            {{ csrf_field() }}
                            <div class="card-body p-0">
                                <fieldset class="rounded mb-2">
                                    <legend class="bg-success">Informations Personnelles</legend>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="nom_user" class="col-sm-3 col-form-label">Nom <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Exp: ATANGANA MOUHAMADOU" name="nom_user" id="nom_user"  value="{{$u->ca_nom}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="prenom_user" class="col-sm-4 col-form-label">Prénom </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" placeholder="Exp: Jules Firmin" name="prenom_user" id="prenom_user"value="{{$u->ca_prenom}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="sexe_user" class="col-sm-3 col-form-label">Sexe <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select name="sexe_user" id="sexe_user" class="form-select" >
                                                        <option value="MASCULIN"  @if ($u->ca_sexe == "MASCULIN") selected @endif>Homme</option>
                                                        <option value="FEMININ" @if ($u->ca_sexe == "FEMININ") selected @endif>Femme</option>
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
                                                        <input type="date" class="form-control" name="date_naissance_user" required id="date_naissance_user" aria-describedby="basic-addon1" value="{{Str::substr($u->ca_date_naiss, 0, 10)}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="lieu_naissance_user" class="col-sm-4 col-form-label">Lieu de Naissance<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon1"><i class="ri-map-pin-user-fill"></i></span>
                                                        <input type="text" class="form-control" name="lieu_naissance_user" placeholder="Exp: Ambam" required id="lieu_naissance_user" aria-describedby="basic-addon1" value="{{$u->ca_lieu_naiss}}">
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
                                                        <input type="tel" class="form-control" name="first_phone_user" required id="first_phone_user" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9" value="{{$u->ca_telephone}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="second_phone_user" class="col-sm-3 col-form-label" title="SMS ou WhatsApp">N° Telephone 2</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                                        <input type="tel" class="form-control" name="second_phone_user" id="second_phone_user" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9">
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
                                                        <input type="text" class="form-control" name="numero_cni_user" required id="numero_cni_user" aria-describedby="basic-addon1" placeholder="Exp: 05815364745 ou KIT158" minlength="6" value="{{$u->ca_num_cni}}">
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
                                                        <input type="mail" class="form-control" name="email_user" required placeholder="Exp: julious1254@gmail.com" id="email_user" aria-describedby="basic-addon1" value="{{$u->ca_email}}">
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
                                                        <input type="text" class="form-control" name="lieu_resi_user" id="lieu_resi_user" aria-describedby="basic-addon1" placeholder="Exp: Ville/Quartier" value="{{$u->ca_adresse}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="statut_mat_user" class="col-sm-4 col-form-label">Statut Matrimoniale</label>
                                                <div class="col-sm-8">
                                                    <select name="statut_mat_user" id="statut_mat_user" class="form-select">
                                                        <option value="CELIBATAIRE" @if ($u->ca_statut_mat == "CELIBATAIRE") selected @endif>Célibataire</option>
                                                        <option value="MARIE" @if ($u->ca_statut_mat == "MARIE") selected @endif>Marié</option>
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
                                                            <option value="FRANCAIS" @if ($u->ca_premirere_lang == "FRANCAIS") selected @endif>Français</option>
                                                            <option value="ANGLAIS" @if ($u->ca_premirere_lang == "ANGLAIS") selected @endif>Anglais</option>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">

                                            <div class="row">
                                                <label for="nationalite_user" class="col-sm-3 col-form-label" title="SMS ou WhatsApp"> Nationalité <span class="text-danger">*</span> </label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon1"><i class="ri-earth-fill"></i></span>
                                                        <select name="nationalite_user" id="nationalite_user" class="form-select" onchange="changePays(this.value)" required>
                                                            <option value="">Votre Pays</option>
                                                            <option value="CMR" @if ($u->ca_nationalite == "CMR") selected @endif>Cameroun</option>
                                                            <option value="AUTRES" @if ($u->ca_nationalite == "AUTRES") selected @endif>Autres...</option>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" id="ca_nationali">
                                            <div class="row">
                                                <label for="user_national" class="col-sm-4 col-form-label">Précisez votre pays<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" placeholder="Exp: Côte D'Ivoire" name="user_national" id="user_national" minlength="5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6" id="region">
                                            <div class="row">
                                                <label for="region_origine_user" class="col-sm-3 col-form-label">Région D'origine<span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <select class="form-select" name="region_origine_user" id="region_origine_user" onchange="changeDepart(this.value)">
                                                        <option value="{{$u->ca_region_origine}}">{{$u->ca_region_origine}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" id="departement">
                                            <div class="row">
                                                <label for="depart_origine_user" class="col-sm-4 col-form-label">Département D'origine<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <select class="form-select" name="depart_origine_user" id="depart_origine_user" onchange="chargeArrond(this.value)">
                                                        <option value="{{$u->ca_depart_origine}}">{{$u->ca_depart_origine}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6" id="arrondissement">
                                            <div class="row">
                                                <label for="arrond_origine_user" class="col-sm-4 col-form-label">Arrond. D'origine<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" placeholder="Exp: AMBAM" name="arrond_origine_user" id="arrond_origine_user" required>
                                                    <!--<select class="form-select" name="arrond_origine_user" id="arrond_origine_user">

                                                    </select>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">

                                            <div class="row">
                                                <label for="date_deliv_cni_user" class="col-sm-4 col-form-label" title="date de delivrance cni"> Date Delivrance CNI <span class="text-danger">*</span> </label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control" placeholder="Exp: Côte D'Ivoire" name="date_deliv_cni_user" id="date_deliv_cni_user" minlength="5" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="rounded mb-2">
                                    <legend class="bg-success">Informations Académique</legend>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="code_filiere" class="col-sm-4 col-form-label">Filière <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <select name="code_filiere" id="cursus" class="form-select" required><!-- onchange= chargeDiplome() -->
                                                        @foreach (\App\Models\Cursus::all() as $c)
                                                            <option value="{{$c->cursus_code}}"  @if ($u->cursus_code == $c->cursus_code) selected @endif>{{$c->cursus_label}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="label_di" class="col-sm-6 col-form-label">Diplôme d'admission<span class="text-danger">*</span></label>
                                                <div class="col-sm-6">
                                                    <input type="hidden" name="specialite_dip"  value="{{$u->ca_serie_diplome}}">
                                                    <input type="hidden" name="label_dip"  value="{{$u->ca_diplome_admission}}">
                                                    <input type="text" class="form-control" placeholder="BACC A" name="label_di" id="label_di" minlength="5" readonly value="{{$u->ca_diplome_admission}} {{$u->ca_serie_diplome}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="annee_dip" class="col-sm-6 col-form-label">Année Diplôme <span class="text-danger">*</span></label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" placeholder="Exp: 2023" name="annee_dip" id="annee_dip" minlength="5" readonly value="{{$u->ca_annee_diplome}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="mention_dip" class="col-sm-4 col-form-label">Mention<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" placeholder="Exp: Bien" name="mention_dip" id="mention_dip" minlength="5" readonly value="{{$u->ca_mention_diplome}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="institution_dip" class="col-sm-5 col-form-label">Etablissement</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" placeholder="Exp: Lycée Bilingue d'Ambam" name="institution_dip" id="institution_dip" value="{{$u->ca_etab_diplome}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="pays_dip" class="col-sm-5 col-form-label">Pays du Diplôme<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" placeholder="Exp: Cameroun" name="pays_dip" id="pays_dip" value="{{$u->ca_pays_diplome}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="rounded mb-2">
                                    <legend class="bg-success">Autres Informations</legend>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="nom_pere_user" class="col-sm-4 col-form-label">Nom du père ou Tuteur <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="nom_pere_user" id="nom_pere_user" required placeholder="Exp: NVONDO Remy" value="{{$u->ca_nom_pere}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="telephone_tuteur_user" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">N° Telephone du père ou tuteur <span class="text-danger">*</span></label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                                        <input type="tel" class="form-control" name="telephone_tuteur_user" required id="telephone_tuteur_user" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9" maxlength="9" value="{{$u->ca_telephone_pere}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="email_tuteur_user" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">Email père ou Tuteur </label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i></span>
                                                        <input type="email" class="form-control" name="email_tuteur_user" id="email_tuteur_user" aria-describedby="basic-addon1" placeholder="Exp:papajules@gmail.com" value="{{$u->ca_email_pere}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="nom_mere_user" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">Nom de la mère</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="nom_mere_user" id="nom_mere_user"  placeholder="Exp: TAMO Jeanne" value="{{$u->ca_nom_mere}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="telephone_mere" class="col-sm-5 col-form-label">N Téléphone de la mère</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="telephone_mere" id="telephone_mere"  placeholder="Exp: 698575896" value="{{$u->ca_telephone_mere}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="nbre_enfant_user" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">Combien d'enfant avez-vous?</label>
                                                <div class="col-sm-6">
                                                        <input type="number" class="form-control" name="nbre_enfant_user" required id="nbre_enfant_user" aria-describedby="basic-addon1" placeholder="Exp: 0" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="pwd_user" class="col-sm-5 col-form-label">Mot de Passe<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="password" class="form-control" name="pwd_user" id="pwd_user" required placeholder="Exp: vfgssrp123ae" >
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="col-sm-6">
                                            <div class="row">
                                                <label for="conf_pwd_user" class="col-sm-6 col-form-label" title="Exp vfgssrp123ae">Confirmer votre mot de Passe<span class="text-danger">*</span></label>
                                                <div class="col-sm-6">
                                                        <input type="password" class="form-control" name="conf_pwd_user" required id="conf_pwd_user" aria-describedby="basic-addon1" placeholder="Exp: vfgssrp123ae">
                                                </div>
                                            </div>
                                        </div>-->
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row mb-4 p-2">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <label for="ca_handicap" class="col-sm-6 col-form-label">Avez-vous un Handicap ? <span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                            <select name="ca_handicap" id="ca_handicap" class="form-select" required onchange="changeHandicap(this.value)">
                                                                <option value="Non">Non</option>
                                                                <option value="Oui">Oui</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" id="handicap_pre" style="display: none;">
                                                    <div class="row">
                                                        <label for="ca_handicap_pre" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">Précisez</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="ca_handicap_pre" id="ca_handicap_pre" placeholder="Exp: décrivez votre handicap" maxlength="50">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="rounded mb-2">
                                    <legend class="bg-danger">Note à l'attention de tout étudiant inscris</legend>
                                    <div class="row m-auto mb-3">
                                        <div class="col">
                                            1.
                                            <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em; color:brown">
                                                Il faut bien vérifier vos informations avant de valider le formulaire. Une erreur de votre part pourrais avoir des incidences
                                                jusqu'au niveau de votre diplome.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row m-auto mb-3">
                                        <div class="col">
                                            2.
                                            <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em; color:brown">
                                                Une fois votre inscription terminée, vous devez impérativement télécharger votre fiche d'inscription administrative ainsi
                                                que vos quitus de paiement des droits universitaires et des frais médicaux.
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row m-auto mb-3">
                                        <div class="col">
                                            3.
                                            <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em; color:brown">
                                                La fiche d'inscription sera imprimée en 03 exemplaires et signée par vous. Chaque fiche possèdera votre photo. Elle
                                                sera déposée à la scolarité.
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row m-auto mb-3">
                                        <div class="col">
                                            4.
                                            <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em; color:brown">
                                                Votre inscription ne sera complète que lorsque vous payerez vos droits universitaires et déposerez les reçus au sein de l'école.
                                            </span>
                                        </div>
                                    </div>

                                </fieldset>

                                <div class="row text-center">
                                <div class="col">
                                    <input type="checkbox" name="confirm" required class="fom-control">&nbsp;
                                    <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em">Je confirme l'exactitude des informations remplies ci-dessus!!!</span>
                                </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="reset" class="btn btn-danger m-3" >Annuler</button>
                                <button type="submit" class="btn btn-primary">S'Inscrire</button>
                            </div>
                        </form>
                    </div>
                    @else
                        <div class="card w-50 m-auto " style="clear: both;" data-aos="zoom-in-up" data-aos-easing="ease-out-cubic" data-aos-duration="1200">
                            <div class="card-header bg-danger">
                                Informations non trouvées
                            </div>
                        <div class="card-body">
                                <p class="h4 text-justify">Désolé, nous ne trouvons aucune information relative à votre recherche dans notre base de donnnées</p>
                        </div>
                        </div>
                    @endif
                @endisset


                <div class="card-form" id="form-new" style="clear: both; display: none;">
                    <form action="/inscription_administrative" method="POST">
                        {{ csrf_field() }}
                        <div class="card-body p-0">
                            <fieldset class="rounded mb-2">
                                <legend class="bg-success">Informations Personnelles</legend>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="nom_user" class="col-sm-3 col-form-label">Nom <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                            <input type="text" class="form-control" placeholder="Exp: ATANGANA MOUHAMADOU" name="nom_user" id="nom_user" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="prenom_user" class="col-sm-4 col-form-label">Prénom </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" placeholder="Exp: Jules Firmin" name="prenom_user" id="prenom_user">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="sexe_user" class="col-sm-3 col-form-label">Sexe <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <select name="sexe_user" id="sexe_user" class="form-select" >
                                                    <option value="MASCULIN" >Homme</option>
                                                    <option value="FEMININ">Femme</option>
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
                                                    <input type="date" class="form-control" name="date_naissance_user" required id="date_naissance_user" aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="lieu_naissance_user" class="col-sm-4 col-form-label">Lieu de Naissance<span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1"><i class="ri-map-pin-user-fill"></i></span>
                                                    <input type="text" class="form-control" name="lieu_naissance_user" placeholder="Exp: Ambam" required id="lieu_naissance_user" aria-describedby="basic-addon1">
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
                                                    <input type="tel" class="form-control" name="first_phone_user" required id="first_phone_user" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="second_phone_user" class="col-sm-3 col-form-label" title="SMS ou WhatsApp">N° Telephone 2</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                                    <input type="tel" class="form-control" name="second_phone_user" id="second_phone_user" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9">
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
                                                    <input type="text" class="form-control" name="numero_cni_user" required id="numero_cni_user" aria-describedby="basic-addon1" placeholder="Exp: 05815364745 ou KIT158" minlength="6">
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
                                                    <input type="mail" class="form-control" name="email_user" required placeholder="Exp: julious1254@gmail.com" id="email_user" aria-describedby="basic-addon1">
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
                                                    <input type="text" class="form-control" name="lieu_resi_user" id="lieu_resi_user" aria-describedby="basic-addon1" placeholder="Exp: Ville/Quartier">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="statut_mat_user" class="col-sm-4 col-form-label">Statut Matrimoniale</label>
                                            <div class="col-sm-8">
                                                <select name="statut_mat_user" id="statut_mat_user" class="form-select">
                                                    <option value="CELIBATAIRE" >Célibataire</option>
                                                    <option value="MARIE">Marié</option>
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
                                                        <option value="FRANCAIS">Français</option>
                                                        <option value="ANGLAIS">Anglais</option>
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">

                                        <div class="row">
                                            <label for="nationalite_user" class="col-sm-3 col-form-label" title="SMS ou WhatsApp"> Nationalité <span class="text-danger">*</span> </label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1"><i class="ri-earth-fill"></i></span>
                                                    <select name="nationalite_user" id="nationalite_user" class="form-select" onchange="changePays(this.value)" required>
                                                        <option value="">Votre Pays</option>
                                                        <option value="CMR" >Cameroun</option>
                                                        <option value="AUTRES">Autres...</option>
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="ca_nationali">
                                        <div class="row">
                                            <label for="user_national" class="col-sm-4 col-form-label">Précisez votre pays<span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" placeholder="Exp: Côte D'Ivoire" name="user_national" id="user_national" minlength="5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6" id="region">
                                        <div class="row">
                                            <label for="region_origine_user" class="col-sm-3 col-form-label">Région D'origine<span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="region_origine_user" id="region_origine_user" onchange="changeDepart(this.value)">

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="departement">
                                        <div class="row">
                                            <label for="depart_origine_user" class="col-sm-4 col-form-label">Département D'origine<span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <select class="form-select" name="depart_origine_user" id="depart_origine_user" onchange="chargeArrond(this.value)">

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6" id="arrondissement">
                                        <div class="row">
                                            <label for="arrond_origine_user" class="col-sm-4 col-form-label">Arrond. D'origine<span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" placeholder="Exp: AMBAM" name="arrond_origine_user" id="arrond_origine_user" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                        <div class="row">
                                            <label for="date_deliv_cni_user" class="col-sm-4 col-form-label" title="date de delivrance cni"> Date Delivrance CNI <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control" placeholder="Exp: Côte D'Ivoire" name="date_deliv_cni_user" id="date_deliv_cni_user" minlength="5" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="rounded mb-2">
                                <legend class="bg-success">Informations Académique</legend>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <label for="code_filiere" class="col-sm-4 col-form-label">Filière <span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <select name="code_filiere" id="cursus" class="form-select" required><!-- onchange= chargeDiplome() -->
                                                    @foreach (\App\Models\Cursus::all() as $c)
                                                        <option value="{{$c->cursus_code}}">{{$c->cursus_label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="row">
                                            <label for="label_di" class="col-sm-6 col-form-label">Diplôme d'admission<span class="text-danger">*</span></label>
                                            <div class="col-sm-6">
                                                <!--<input type="hidden" name="specialite_dip"  value="">
                                                <input type="hidden" name="label_dip"  value="">-->
                                                <input type="text" class="form-control" placeholder="BACCALAUREAT" name="label_dip" id="label_dip" minlength="5">
                                                <input type="text" class="form-control" placeholder="D, A, CG, F4, ..." name="specialite_dip" id="specialite_dip" minlength="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <label for="annee_dip" class="col-sm-6 col-form-label">Année Diplôme <span class="text-danger">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="number" class="form-control" placeholder="Exp: 2023" name="annee_dip" id="annee_dip" minlength="5" >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4 p-2">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <label for="mention_dip" class="col-sm-4 col-form-label">Mention<span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" placeholder="Exp: Bien" name="mention_dip" id="mention_dip" minlength="5">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="row">
                                            <label for="institution_dip" class="col-sm-5 col-form-label">Etablissement</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" placeholder="Exp: Lycée Bilingue d'Ambam" name="institution_dip" id="institution_dip">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="row">
                                            <label for="pays_dip" class="col-sm-5 col-form-label">Pays du Diplôme<span class="text-danger">*</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" placeholder="Exp: Cameroun" name="pays_dip" id="pays_dip">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="rounded mb-2">
                                <legend class="bg-success">Autres Informations</legend>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="nom_pere_user" class="col-sm-4 col-form-label">Nom du père ou Tuteur <span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="nom_pere_user" id="nom_pere_user" required placeholder="Exp: NVONDO Remy">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="telephone_tuteur_user" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">N° Telephone du père ou tuteur <span class="text-danger">*</span></label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                                    <input type="tel" class="form-control" name="telephone_tuteur_user" required id="telephone_tuteur_user" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9" maxlength="9">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="email_tuteur_user" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">Email père ou Tuteur </label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i></span>
                                                    <input type="email" class="form-control" name="email_tuteur_user" id="email_tuteur_user" aria-describedby="basic-addon1" placeholder="Exp:papajules@gmail.com">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="nom_mere_user" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">Nom de la mère</label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="nom_mere_user" id="nom_mere_user"  placeholder="Exp: TAMO Jeanne">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="telephone_mere" class="col-sm-5 col-form-label">N Téléphone de la mère</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="telephone_mere" id="telephone_mere"  placeholder="Exp: 698575896">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="nbre_enfant_user" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">Combien d'enfant avez-vous?</label>
                                            <div class="col-sm-6">
                                                    <input type="number" class="form-control" name="nbre_enfant_user" required id="nbre_enfant_user" aria-describedby="basic-addon1" placeholder="Exp: 0" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 p-2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <label for="pwd_user" class="col-sm-5 col-form-label">Mot de Passe<span class="text-danger">*</span></label>
                                            <div class="col-sm-7">
                                                <input type="password" class="form-control" name="pwd_user" id="pwd_user" required placeholder="Exp: vfgssrp123ae" >
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-6">
                                        <div class="row">
                                            <label for="conf_pwd_user" class="col-sm-6 col-form-label" title="Exp vfgssrp123ae">Confirmer votre mot de Passe<span class="text-danger">*</span></label>
                                            <div class="col-sm-6">
                                                    <input type="password" class="form-control" name="conf_pwd_user" required id="conf_pwd_user" aria-describedby="basic-addon1" placeholder="Exp: vfgssrp123ae">
                                            </div>
                                        </div>
                                    </div>-->
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row mb-4 p-2">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <label for="ca_handicap" class="col-sm-6 col-form-label">Avez-vous un Handicap ? <span class="text-danger">*</span></label>
                                                    <div class="col-sm-6">
                                                        <select name="ca_handicap" id="ca_handicap" class="form-select" required onchange="changeHandicap(this.value)">
                                                            <option value="Non">Non</option>
                                                            <option value="Oui">Oui</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6" id="handicap_pre" style="display: none;">
                                                <div class="row">
                                                    <label for="ca_handicap_pre" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">Précisez</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" name="ca_handicap_pre" id="ca_handicap_pre" placeholder="Exp: décrivez votre handicap" maxlength="50">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="rounded mb-2">
                                <legend class="bg-danger">Note à l'attention de tout étudiant inscris</legend>
                                <div class="row m-auto mb-3">
                                    <div class="col">
                                        1.
                                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em; color:brown">
                                            Il faut bien vérifier vos informations avant de valider le formulaire. Une erreur de votre part pourrais avoir des incidences
                                            jusqu'au niveau de votre diplome.
                                        </span>
                                    </div>
                                </div>
                                <div class="row m-auto mb-3">
                                    <div class="col">
                                        2.
                                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em; color:brown">
                                            Une fois votre inscription terminée, vous devez impérativement télécharger votre fiche d'inscription administrative ainsi
                                            que vos quitus de paiement des droits universitaires et des frais médicaux.
                                        </span>
                                    </div>
                                </div>

                                <div class="row m-auto mb-3">
                                    <div class="col">
                                        3.
                                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em; color:brown">
                                            La fiche d'inscription sera imprimée en 03 exemplaires et signée par vous. Chaque fiche possèdera votre photo. Elle
                                            sera déposée à la scolarité.
                                        </span>
                                    </div>
                                </div>

                                <div class="row m-auto mb-3">
                                    <div class="col">
                                        4.
                                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em; color:brown">
                                            Votre inscription ne sera complète que lorsque vous payerez vos droits universitaires et déposerez les reçus au sein de l'école.
                                        </span>
                                    </div>
                                </div>

                            </fieldset>

                            <div class="row text-center">
                            <div class="col">
                                <input type="checkbox" name="confirm" required class="fom-control">&nbsp;
                                <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.5em">Je confirme l'exactitude des informations remplies ci-dessus!!!</span>
                            </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="reset" class="btn btn-danger m-3" >Annuler</button>
                            <button type="submit" class="btn btn-primary">S'Inscrire</button>
                        </div>
                    </form>
                </div>
          </div>
        </div>

    </div>
@endsection
