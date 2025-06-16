@extends("concours.frontend.template.concours")
@section("content")
@php
    $ca = Session::get("user");
@endphp
<div class="row card card-inscription mb-2" style="margin-top: 5%;">
    <div class="card-header bg-success mb-1" style="color: white">
        <h5 class="card-title">Formulaire de mise à jour</h5>
    </div>
    <div class="row">
        <div class="col-sm-2"></div>
        <p class="card-text h6 text-danger col-sm-4 p-0 m-0"><i>Veuillez vous rassurer des informations remplies</i></p>
        <div class="col-sm-2"></div>
        <p class="card-text h6 text-danger  col-sm-4 p-0 m-0"><i>Il n'est possible que de modifier certaines informations!!!</i></p>
    </div>
    <form action="/update_validate" method="post" enctype="multipart/form-data">
        <input type="hidden" name ="ca_code", value="{{$ca->ca_code}}">
        {{ csrf_field() }}
        <div class="card-body">
            <fieldset class="rounded mb-2">
                <legend class="bg-success">Informations Personnelles</legend>
                <div class="row mb-4 p-2">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="ca_nom" class="col-sm-3 col-form-label">Nom <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Exp: ATANGANA MOUHAMADOU" name="ca_nom" id="ca_nom" value="{{$ca->ca_nom}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="ca_prenom" class="col-sm-3 col-form-label">Prénom </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Exp: Jules Firmin" name="ca_prenom" id="ca_prenom" value="{{$ca->ca_prenom}}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 p-2">
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_sexe" class="col-sm-3 col-form-label">Sexe <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="ca_sexe" id="ca_sexe" class="form-select" value="{{$ca->ca_sexe}}">
                                    <option value="MASCULIN">Homme</option>
                                    <option value="FEMININ">Femme</option>
                               </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_date_naiss" class="col-sm-5 col-form-label">Date de Naissance <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-calendar-2-line"></i></span>
                                    <input type="date" class="form-control" name="ca_date_naiss" required id="ca_date_naiss" aria-describedby="basic-addon1" value="{{Str::substr($ca->ca_date_naiss, 0, 10)}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_lieu_naiss" class="col-sm-5 col-form-label">Lieu de Naissance <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-map-pin-user-fill"></i></span>
                                    <input type="text" class="form-control" name="ca_lieu_naiss" placeholder="Exp: Ambam" required id="ca_lieu_naiss" aria-describedby="basic-addon1" value="{{$ca->ca_lieu_naiss}}" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 p-2">
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_telephone" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">N° Telephone <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                    <input type="tel" class="form-control" name="ca_telephone" required id="ca_telephone" aria-describedby="basic-addon1" placeholder="Exp: 695201518" value="{{$ca->ca_telephone}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_num_cni" class="col-sm-3 col-form-label">N° CNI <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-bank-card-line"></i></span>
                                    <input type="text" class="form-control" name="ca_num_cni" required id="ca_num_cni" aria-describedby="basic-addon1" placeholder="Exp: 05815364745 ou KIT158" value="{{$ca->ca_num_cni}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-mail-fill"></i></span>
                                    <input type="mail" class="form-control" name="ca_email" placeholder="Exp: julious1254@gmail.com" id="ca_email" aria-describedby="basic-addon1" value="{{$ca->ca_email}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 p-2">
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_adresse" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">Résidence </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-map-pin-fill"></i></span>
                                    <input type="text" class="form-control" name="ca_adresse" id="ca_adresse" aria-describedby="basic-addon1" placeholder="Exp: Ville/Quartier" value="{{$ca->ca_adresse}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_statut_mat" class="col-sm-5 col-form-label">Statut Matrimoniale</label>
                            <div class="col-sm-7">
                                <select name="ca_statut_mat" id="ca_statut_mat" class="form-select" value="{{$ca->ca_statut_mat}}">
                                    <option value="Célibataire" @if ($ca->ca_statut_mat == "Célibataire") selected @endif>Célibataire</option>
                                    <option value="Marié" @if ($ca->ca_statut_mat == "Marié") selected @endif>Marié</option>
                               </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_premirere_lang" class="col-sm-4 col-form-label">1 <sup>iere</sup> Langue <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-flag-fill"></i></span>
                                    <select name="ca_premirere_lang" id="ca_premirere_lang" class="form-select" value="{{$ca->ca_premirere_lang}}">
                                        <option value="FRANCAIS" @if ($ca->ca_premirere_lang == "FRANCAIS") selected @endif>Français</option>
                                        <option value="ANGLAIS" @if ($ca->ca_premirere_lang == "ANGLAIS") selected @endif>Anglais</option>
                                   </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 p-2">
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_nationalite" class="col-sm-4 col-form-label" title="SMS ou WhatsApp"> Nationalité <span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-earth-fill"></i></span>
                                    <select name="ca_nationalite" id="ca_nationalite" class="form-select" onchange="changePays(this.value)" required value="{{$ca->ca_nationalite}}">
                                        <option value="">Votre Pays</option>
                                        <option value="CMR" @if ($ca->ca_nationalite == "CMR") selected @endif>Cameroun</option>
                                        <option value="CIV" @if ($ca->ca_nationalite == "CIV") selected @endif>Côte D'Ivoire</option>
                                        <option value="TCHAD" @if ($ca->ca_nationalite == "TCHAD") selected @endif>Tchad</option>
                                        <option value="GABON" @if ($ca->ca_nationalite == "GABON") selected @endif>Gabon</option>
                                        <option value="GUINNE" @if ($ca->ca_nationalite == "GUINNE") selected @endif>Guinée Equatoriale</option>
                                        <option value="CAF" @if ($ca->ca_nationalite == "CAF") selected @endif>Republique Centrafricaine</option>
                                        <option value="RC" @if ($ca->ca_nationalite == "RC") selected @endif>Republique du Congo</option>
                                        <option value="RDC" @if ($ca->ca_nationalite == "RDC") selected @endif>Republique Démocratique du Congo</option>
                                   </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="region">
                        <div class="row">
                            <label for="region_origine" class="col-sm-5 col-form-label">Région D'origine<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select class="form-select" name="ca_region_origine" id="region_origine" required onchange="changeDepart(this.value)">
                                    <option value="{{$ca->ca_region_origine}}">{{$ca->ca_region_origine}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="departement">
                        <div class="row">
                            <label for="depart_origine" class="col-sm-6 col-form-label">Département D'origine<span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-select" name="ca_depart_origine" id="depart_origine" required>
                                    <option value="{{$ca->ca_depart_origine}}">{{$ca->ca_depart_origine}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="rounded mb-2">
                <legend class="bg-success">Informations Académique</legend>
                <div class="row mb-4 p-2">
                    <div class="col-sm-5">
                        <div class="row">
                            <label for="cursus" class="col-sm-4 col-form-label">Choix de la filière <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="cursus_code" id="cursus" class="form-select" required onchange="changeCursus()">
                                    <option value="">Veuillez choisir votre filière</option>
                                    @foreach (\App\Models\Cursus::all() as $c)
                                    <option value="{{$c->cursus_code}}" @if ($ca->cursus_code == $c->cursus_code) selected @endif>{{$c->cursus_label}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="row">
                            <label for="diplome" class="col-sm-6 col-form-label">Diplôme d'admission<span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select name="ca_diplome_admission" id="diplome" class="form-select" required  onchange="changeSerie(this.value)">
                                    <option value="{{$ca->ca_diplome_admission}}">{{$ca->ca_diplome_admission}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <label for="serie" class="col-sm-4 col-form-label">Série<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="ca_serie_diplome" id="serie" class="form-select" required >
                                    <option value="{{$ca->ca_serie_diplome}}">{{$ca->ca_serie_diplome}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 p-2">
                    <div class="col-sm-3">
                        <div class="row">
                            <label for="ca_annee_diplome" class="col-sm-6 col-form-label">Année Diplôme <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select name="ca_annee_diplome" id="ca_annee_diplome" class="form-select" required>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="row">
                            <label for="ca_mention_diplome" class="col-sm-5 col-form-label">Mention<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select name="ca_mention_diplome" id="ca_mention_diplome" class="form-select" required>
                                    <option value="Assez-Bien" @if ($ca->ca_mention_diplome == "Assez-Bien") selected @endif>Assez-Bien</option>
                                    <option value="Bien" @if ($ca->ca_mention_diplome == "Bien") selected @endif>Bien</option>
                                    <option value="Excellent" @if ($ca->ca_mention_diplome == "Excellent") selected @endif>Excellent</option>
                                    <option value="Passable" @if ($ca->ca_mention_diplome == "Passable") selected @endif>Passable</option>
                                    <option value="Très Bien" @if ($ca->ca_mention_diplome == "Très Bien") selected @endif>Très Bien</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_etab_diplome" class="col-sm-5 col-form-label">Etablissement</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" placeholder="Exp: Lycée Bilingue d'Ambam" name="ca_etab_diplome" id="ca_etab_diplome" value="{{$ca->ca_etab_diplome}}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="row">
                            <label for="ca_pays_diplome" class="col-sm-5 col-form-label">Pays du Diplôme<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-earth-fill"></i></span>
                                    <select name="ca_pays_diplome" id="ca_pays_diplome" class="form-select" required>
                                        <option value="CMR" @if ($ca->ca_pays_diplome == "CMR") selected @endif>Cameroun</option>
                                        <option value="CIV" @if ($ca->ca_pays_diplome == "CIV") selected @endif>Côte D'Ivoire</option>
                                        <option value="TCHAD" @if ($ca->ca_pays_diplome == "TCHAD") selected @endif>Tchad</option>
                                        <option value="GABON" @if ($ca->ca_pays_diplome == "GABON") selected @endif>Gabon</option>
                                        <option value="GUINNE" @if ($ca->ca_pays_diplome == "GUINNE") selected @endif>Guinée Equatoriale</option>
                                        <option value="CAF" @if ($ca->ca_pays_diplome == "CAF") selected @endif>Republique Centrafricaine</option>
                                        <option value="RC" @if ($ca->ca_pays_diplome == "RC") selected @endif>Republique du Congo</option>
                                        <option value="RDC" @if ($ca->ca_pays_diplome == "RDC") selected @endif>Republique Démocratique du Congo</option>
                                   </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 p-2">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-3">
                        <div class="row">
                            <label for="ca_centre_examen" class="col-sm-6 col-form-label">Centre d'examen <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select name="ca_centre_examen" id="ca_centre_examen" class="form-select" required>
                                    <option value="AMBAM" @if ($ca->ca_centre_examen == "AMBAM") selected @endif>Ambam</option>
                                    <option value="Bafoussan" @if ($ca->ca_centre_examen == "Bafoussan") selected @endif>Bafoussan</option>
                                    <option value="Bamenda" @if ($ca->ca_centre_examen == "Bamenda") selected @endif>Bamenda</option>
                                    <option value="Bertoua" @if ($ca->ca_centre_examen == "Bertoua") selected @endif>Bertoua</option>
                                    <option value="Buéa" @if ($ca->ca_centre_examen == "Buéa") selected @endif>Buéa</option>
                                    <option value="Ebolowa" @if ($ca->ca_centre_examen == "Ebolowa") selected @endif>Ebolowa</option>
                                    <option value="Douala" @if ($ca->ca_centre_examen == "Douala") selected @endif> Douala</option>
                                    <option value="Garoua" @if ($ca->ca_centre_examen == "Garoua") selected @endif>Garoua</option>
                                    <option value="Maroua" @if ($ca->ca_centre_examen == "Maroua") selected @endif>Maroua</option>
                                    <option value="Ngaoundéré" @if ($ca->ca_centre_examen == "Ngaoundéré") selected @endif>Ngaoundéré</option>
                                    <option value="Yaoundé" @if ($ca->ca_centre_examen == "Yaoundé") selected @endif>Yaoundé</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="row">
                            <label for="ca_centre_depot" class="col-sm-6 col-form-label">Centre de dépôt <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select name="ca_centre_depot" id="ca_centre_depot" class="form-select" required>
                                    <option value="AMBAM" @if ($ca->ca_centre_examen == "AMBAM") selected @endif>Ambam</option>
                                    <option value="DRES OUEST" @if ($ca->ca_centre_examen == "DRES OUEST") selected @endif>DRES OUEST</option>
                                    <option value="DRES NORD-OUEST" @if ($ca->ca_centre_examen == "DRES NORD-OUEST") selected @endif>DRES NORD-OUEST</option>
                                    <option value="DRES EST" @if ($ca->ca_centre_examen == "DRES EST") selected @endif>DRES EST</option>
                                    <option value="DRES SUD OUEST" @if ($ca->ca_centre_examen == "DRES SUD OUEST") selected @endif>DRES SUD OUEST</option>
                                    <option value="DRES SUD" @if ($ca->ca_centre_examen == "DRES SUD") selected @endif>DRES SUD</option>
                                    <option value="DRES LITTORAL" @if ($ca->ca_centre_examen == "DRES NORD") selected @endif>DRES LITTORAL</option>
                                    <option value="DRES NORD" @if ($ca->ca_centre_examen == "DRES NORD") selected @endif>DRES NORD</option>
                                    <option value="DRES EXTREME-NORD" @if ($ca->ca_centre_examen == "DRES EXTREME-NORD") selected @endif>DRES EXTREME-NORD</option>
                                    <option value="DRES ADAMAOUA" @if ($ca->ca_centre_examen == "DRES ADAMAOUA") selected @endif>DRES ADAMAOUA</option>
                                    <option value="DRES CENTRE" @if ($ca->ca_centre_examen == "DRES CENTRE") selected @endif>DRES CENTRE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="rounded mb-2">
                <legend class="bg-success">Autres Informations</legend>
                <div class="row mb-4 p-2">
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_nom_pere" class="col-sm-5 col-form-label">Nom du père ou Tuteur <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="ca_nom_pere" id="ca_nom_pere" value="{{$ca->ca_nom_pere}}" placeholder="Exp: NVONDO Remy">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row">
                            <label for="ca_telephone_pere" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">N° Telephone du père ou tuteur <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                    <input type="tel" class="form-control" name="ca_telephone_pere" value="{{$ca->ca_telephone_pere}}" required id="ca_telephone_pere" aria-describedby="basic-addon1" placeholder="Exp: 695201518">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <label for="ca_email_pere" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">Email </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i></span>
                                    <input type="email" class="form-control" name="ca_email_pere" value="{{$ca->ca_email_pere}}" id="ca_email_pere" aria-describedby="basic-addon1" placeholder="Exp:papajules@gmail.com">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-10">
                        <div class="row mb-4 p-2">
                            <div class="col-sm-5">
                                <div class="row">
                                    <label for="ca_nom_mere" class="col-sm-5 col-form-label">Nom de la mère<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="ca_nom_mere" id="ca_nom_mere" value="{{$ca->ca_nom_mere}}" required placeholder="Exp: DONFACK Béatrice">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="row">
                                    <label for="ca_telephone_mere" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">N° Telephone de la mère</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                            <input type="tel" class="form-control" name="ca_telephone_mere" required value="{{$ca->ca_telephone_mere}}" id="ca_telephone_mere" aria-describedby="basic-addon1" placeholder="Exp: 695201518">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4 p-2">
                            <div class="col-sm-5">
                                <div class="row">
                                    <label for="ca_handicap" class="col-sm-6 col-form-label">Avez-vous un Handicap ? <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <select name="ca_handicap" id="ca_handicap" class="form-select" required onchange="changeHandicap(this.value)">
                                            <option value="Non" @if ($ca->ca_handicap == "Non") selected @endif>Non</option>
                                            <option value="Oui"@if ($ca->ca_handicap == "Oui") selected @endif>Oui</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7" id="handicap_pre" style="display: none;">
                                <div class="row">
                                    <label for="ca_handicap_pre" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">Précisez</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="{{$ca->ca_handicap_pre}}" name="ca_handicap_pre" id="ca_handicap_pre" placeholder="Exp: décrivez votre handicap" maxlength="50">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 p-5">
                        <img src="{{asset("storage/cartes/".getdate()['year']."/".$ca->ca_photo)}}" alt="test" id="image_carte_update" style="width: 80%;"  class="rounded">
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
            <button type="submit" class="btn btn-success">Valider &nbsp;<i class="ri-checkbox-circle-fill"></i></button>
        </div>
    </form>
</div>
@endsection
