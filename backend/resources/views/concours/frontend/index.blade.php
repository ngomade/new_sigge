@extends("concours.frontend.template.concours")
@section("content")
<div class="card mb-3 info-ins">
    <div class="row g-0">
      <div class="col-md-1" style="display: flex; justify-content: center;">
        <img src="{{asset('share/img/logo_estlc.png')}}" class="img-fluid rounded-start" alt="Logo Université" data-aos="fade" data-aos-delay="150" style="width: 150px;">
      </div>
      <div class="col-md-11">
        <div class="card-body">
          <h5 class="card-title h3 text-center mt-3">Bienvenue sur notre plateforme d'inscription en ligne.</h5>

          <p class="card-text" style="font-size: 1.02em;">Nous vous recommandons de télécharger l'arreté de lancement du concours qui pourra vous être utile plutard. Faites-le en cliquant sur le bouton suivant en fonction de vos préférences
             &nbsp;<a href="/download/{{"arrete_F_".getdate()['year']}}" class="btn btn-outline-success">Version Fançaise<i class="ri-download-2-line"></i></a> &nbsp; &nbsp; &nbsp; <a href="/download/{{"arrete_A_".getdate()['year']}}" class="btn btn-outline-success">English Version<i class="ri-download-2-line"></i></a>.</p>
        </div>
      </div>
    </div>
</div>

<div class="row card card-inscription mb-2">
    <div class="card-header bg-success mb-1" style="color: white">
        <h5 class="card-title">Formulaire d'inscription candidat</h5>
    </div>
    <div class="row">
        <div class="col-sm-2"></div>
        <p class="card-text h6 text-danger col-sm-4 p-0 m-0"><i>Veuillez remplir correctement vos informations !!!</i></p>
        <div class="col-sm-2"></div>
        <p class="card-text h6 text-danger  col-sm-4 p-0 m-0"><i>Les champs marqués par les <span class="text-primary">*</span> sont obligatoires !!!</i></p>
    </div>
    <form action="/inscription" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="card-body">
            <fieldset class="rounded mb-2">
                <legend class="bg-success">Informations Personnelles</legend>
                <div class="row mb-4 p-2">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="ca_nom" class="col-sm-3 col-form-label">Nom <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Exp: ATANGANA MOUHAMADOU" name="ca_nom" id="ca_nom" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="ca_prenom" class="col-sm-3 col-form-label">Prénom </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Exp: Jules Firmin" name="ca_prenom" id="ca_prenom" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 p-2">
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_sexe" class="col-sm-3 col-form-label">Sexe <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="ca_sexe" id="ca_sexe" class="form-select">
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
                                    <input type="date" class="form-control" name="ca_date_naiss" required id="ca_date_naiss" aria-describedby="basic-addon1">
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
                                    <input type="text" class="form-control" name="ca_lieu_naiss" placeholder="Exp: Ambam" required id="ca_lieu_naiss" aria-describedby="basic-addon1">
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
                                    <input type="tel" class="form-control" name="ca_telephone" required id="ca_telephone" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9">
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
                                    <input type="text" class="form-control" name="ca_num_cni" required id="ca_num_cni" aria-describedby="basic-addon1" placeholder="Exp: 05815364745 ou KIT158" minlength="6">
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
                                    <input type="mail" class="form-control" name="ca_email" required placeholder="Exp: julious1254@gmail.com" id="ca_email" aria-describedby="basic-addon1">
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
                                    <input type="text" class="form-control" name="ca_adresse" id="ca_adresse" aria-describedby="basic-addon1" placeholder="Exp: Ville/Quartier">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_statut_mat" class="col-sm-5 col-form-label">Statut Matrimoniale</label>
                            <div class="col-sm-7">
                                <select name="ca_statut_mat" id="ca_statut_mat" class="form-select">
                                    <option value="MARIE">Célibataire</option>
                                    <option value="CELIBATAIRE">Marié</option>
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
                                    <select name="ca_premirere_lang" id="ca_premirere_lang" class="form-select">
                                        <option value="FRANCAIS">Français</option>
                                        <option value="ANGLAIS">Anglais</option>
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
                                    <select name="ca_nationalite" id="ca_nationalite" class="form-select" onchange="changePays(this.value)" required>
                                        <option value="">Votre Pays</option>
                                        <option value="CMR">Cameroun</option>
                                        <option value="AUTRES">Autres...</option>
                                   </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="ca_nationali">
                        <div class="row">
                            <label for="ca_national" class="col-sm-5 col-form-label">Précisez votre pays<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" placeholder="Exp: Côte D'Ivoire" name="ca_national" id="ca_national" minlength="5">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="region">
                        <div class="row">
                            <label for="region_origine" class="col-sm-5 col-form-label">Région D'origine<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select class="form-select" name="ca_region_origine" id="region_origine" onchange="changeDepart(this.value)">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="departement">
                        <div class="row">
                            <label for="depart_origine" class="col-sm-6 col-form-label">Département D'origine<span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-select" name="ca_depart_origine" id="depart_origine">

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
                                <select name="cursus_code" id="cursus" class="form-select" required><!-- onchange= chargeDiplome() -->
                                    <option value="">Veuillez choisir votre filière</option>
                                    @foreach (\App\Models\Cursus::all() as $c)
                                    <option value="{{$c->cursus_code}}">{{$c->cursus_label}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="row">
                            <label for="diplome" class="col-sm-6 col-form-label">Diplôme d'admission<span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select name="ca_diplome_admission" id="diplome" class="form-select" required  onchange="changeSerie(this.value)"><!--onchange="changeSerie(this.value)"-->
                                    <option value="">Votre Diplôme</option>
                                    <option value="BACCALAUREAT">Baccalauréat</option>
                                    <option value="GCE">GCE-- General Certificate of Education </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <label for="serie" class="col-sm-4 col-form-label">Série<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="ca_serie_diplome" id="serie" class="form-select" required >
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
                                    <option value="Assez-Bien">Assez-Bien</option>
                                    <option value="Bien">Bien</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Passable">Passable</option>
                                    <option value="Très Bien">Très Bien</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="row">
                            <label for="ca_etab_diplome" class="col-sm-5 col-form-label">Etablissement</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" placeholder="Exp: Lycée Bilingue d'Ambam" name="ca_etab_diplome" id="ca_etab_diplome">
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
                                        <option value="CAMEROUN">Cameroun</option>
                                        <option value="CIV">Côte D'Ivoire</option>
                                        <option value="TCHAD">Tchad</option>
                                        <option value="GABON">Gabon</option>
                                        <option value="GUINNE">Guinée Equatoriale</option>
                                        <option value="CAF">Republique Centrafricaine</option>
                                        <option value="RC">Republique du Congo</option>
                                        <option value="RDC">Republique Démocratique du Congo</option>
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
                                    <option value="AMBAM">Ambam</option>
                                    <option value="Bafoussan">Bafoussam</option>
                                    <option value="Bamenda">Bamenda</option>
                                    <option value="Bertoua">Bertoua</option>
                                    <option value="Buéa">Buéa</option>
                                    <option value="Ebolowa">Ebolowa</option>
                                    <option value="Douala">Douala</option>
                                    <option value="Garoua">Garoua</option>
                                    <option value="Maroua">Maroua</option>
                                    <option value="Ngaoundéré">Ngaoundéré</option>
                                    <option value="Yaoundé">Yaoundé</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="row">
                            <label for="ca_centre_depot" class="col-sm-6 col-form-label">Centre de dépôt <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select name="ca_centre_depot" id="ca_centre_depot" class="form-select" required>
                                    <option value="AMBAM">Ambam</option>
                                    <option value="DRES OUEST">DRES OUEST</option>
                                    <option value="DRES NORD-OUEST">DRES NORD-OUEST</option>
                                    <option value="DRES EST">DRES EST</option>
                                    <option value="DRES SUD OUEST">DRES SUD OUEST</option>
                                    <option value="DRES SUD">DRES SUD</option>
                                    <option value="DRES LITTORAL">DRES LITTORAL</option>
                                    <option value="DRES NORD">DRES NORD</option>
                                    <option value="DRES EXTREME-NORD">DRES EXTREME-NORD</option>
                                    <option value="DRES ADAMAOUA">DRES ADAMAOUA</option>
                                    <option value="DRES CENTRE">DRES CENTRE</option>
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
                                <input type="text" class="form-control" name="ca_nom_pere" id="ca_nom_pere" required placeholder="Exp: NVONDO Remy">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row">
                            <label for="ca_telephone_pere" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">N° Telephone du père ou tuteur <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                    <input type="tel" class="form-control" name="ca_telephone_pere" required id="ca_telephone_pere" aria-describedby="basic-addon1" placeholder="Exp: 695201518" minlength="9">
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
                                    <input type="email" class="form-control" name="ca_email_pere" id="ca_email_pere" aria-describedby="basic-addon1" placeholder="Exp:papajules@gmail.com">
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
                                        <input type="text" class="form-control" name="ca_nom_mere" id="ca_nom_mere" required placeholder="Exp: DONFACK Béatrice">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="row">
                                    <label for="ca_telephone_mere" class="col-sm-6 col-form-label" title="SMS ou WhatsApp">N° Telephone de la mère</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="ri-phone-line"></i> +237</span>
                                            <input type="tel" class="form-control" name="ca_telephone_mere" required id="ca_telephone_mere" aria-describedby="basic-addon1" placeholder="Exp: 695201518">
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
                                            <option value="Non">Non</option>
                                            <option value="Oui">Oui</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7" id="handicap_pre" style="display: none;">
                                <div class="row">
                                    <label for="ca_handicap_pre" class="col-sm-4 col-form-label" title="SMS ou WhatsApp">Précisez</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="ca_handicap_pre" id="ca_handicap_pre" placeholder="Exp: décrivez votre handicap" maxlength="50">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4 p-2">
                            <div class="col-sm-12">
                                <div class="row">
                                    <label for="ca_photo" class="col-sm-9 col-form-label">Carte Photo 4X4<span class="text-danger">*</span>  <span class="h6 text-danger" style="font-family:'Goudy Old Style' "><br>(NB: Photo sur fond Blanc. Les formats autorisés sont: <i>.jpg, .png, .jpeg</i>. Taille Max= 2Mo, Dimension maximale: 400x400 )</span></label>
                                    <div class="col-sm-3">
                                        <input type="file" class="form-control" name="ca_photo" id="ca_photo" required   onchange="previewPicture(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 p-5">
                        <img src="#" alt="" id="image_carte" style="width: 80%"  class="rounded">
                    </div>
                </div>
            </fieldset>

            <fieldset class="rounded mb-2">
                <legend class="bg-success">Comfirmations des Documents exigibles / Confirmations of Required Documents</legend>
                <div class="row m-auto mb-3">
                    <div class="col">
                        <input type="checkbox" name="confirm" required class="fom-control">&nbsp;
                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.1em; color:brown">Une photocopie certifiée d'acte de naissance datant de moins de trois (3) mois /
                        <span class="english">A certified true photocopy of the birth certificate issued within the last three (03) months;</span></span>
                    </div>
                </div>
                <div class="row m-auto mb-3">
                    <div class="col">
                        <input type="checkbox" name="confirm" required class="fom-control">&nbsp;
                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.1em; color:brown">Une photocopie certifiée conforme du diplôme/attestation de réussite du Baccalauréat ou du GCE A/L ou du diplôme équivalent ; /
                            <span class="english">A certified true copy of the required diploma;</span></span>
                    </div>
                </div>

                <div class="row m-auto mb-3">
                    <div class="col">
                        <input type="checkbox" name="confirm" required class="fom-control">&nbsp;
                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.1em; color:brown">Un certificat de scolarité de la classe de terminale/upper sixth ; /
                            <span class="english">A certificate of school attendance for "Terminale" or Upper 6th;</span></span>
                    </div>
                </div>

                <div class="row m-auto mb-3">
                    <div class="col">
                        <input type="checkbox" name="confirm" required class="fom-control">&nbsp;
                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.1em; color:brown">Un certificat médical délivré par un médecin fonctionnaire, datant de moins de trois (03) mois et certifiant que le candidat est apte à poursuivre des études supérieures ; /
                            <span class="english"> A medical certificate issued within the last three (03) months by a state medical practitioner, and testifying that the candidate is fit for higher education;</span></span>
                    </div>
                </div>

                <div class="row m-auto mb-3">
                    <div class="col">
                        <input type="checkbox" name="confirm" required class="fom-control">&nbsp;
                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.1em; color:brown">Un reçu de versement bancaire d'un montant de vingt mille (20 000) francs CFA représentant les droits d'inscription au concours de l'ESTLC de l'Université d'Ebolowa, à verser au compte SCB Cameroun, N° 10002-00059-90001487491-26 dans toutes les agences du Cameroun (Aucun autre mode de paiement ne sera accepté) ; /
                            <span class="english">A twenty thousand (20,000) CFA francs banking receipt representing examination fees of the competitive entrance at HITLC of the University of Ebolowa to be paid at SCB Cameroon account, N° 10002-00059-90001487491-26 in all agencies in Cameroon (No other method of payment will be accepted);</span>
                    </div>
                </div>

                <div class="row m-auto mb-3">
                    <div class="col">
                        <input type="checkbox" name="confirm" required class="fom-control">&nbsp;
                        <span class="text-center text-primary tex-italic" style="font-family: 'Goudy Old Style'; font-size: 1.1em; color:brown">Une enveloppe A4 timbrée au tarif réglementaire et portant l'adresse exacte du candidat ; /
                            <span class="english">A 21 x 29.7 size self-addressed envelope bearing a 400 CFA francs postal stamp</span></span>
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

<div class="row mt-4 mb-1">

    <div class="col-lg-12 col-md-12" data-aos="fade-up" data-aos-delay="100" style="display: flex; justify-content: center;" >
      <div  id="map" class="card">
        <div class="card-body">
            <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d680696.1002018142!2d11.006932526069411!3d3.1073400537897453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x108bcf7a309a7977%3A0x7f54bad35e693c51!2zWWFvdW5kw6k!3m2!1d3.8480325!2d11.5020752!4m5!1s0x1087d5c57e4aa887%3A0x616425f0212f30d5!2sAmbam!3m2!1d2.3815417!2d11.2665498!5e0!3m2!1sfr!2scm!4v1692180500127!5m2!1sfr!2scm" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </div>
@endsection
