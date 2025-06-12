@extends("sige_app.backend.template.backend")
@section("js")
<script>
    function formatDate(date) {
    let year = date.getFullYear();
    let month = String(date.getMonth() + 1).padStart(2, '0'); // Ajoute un zéro devant si nécessaire
    let day = String(date.getDate()).padStart(2, '0');         // Ajoute un zéro devant si nécessaire
    return `${year}-${month}-${day}`;
    }
    function selectOptionByValue(selectId, valueToSelect) {
    let selectElement = document.getElementById(selectId);

    for (let option of selectElement.options) {
        if (option.value.toUpperCase() === valueToSelect.toUpperCase()) {
            option.selected = true;
            break;
        }
    }
}
    function showInscriptionForm(candidat){
        document.getElementById("nom_user").value = candidat.ca_nom
        document.getElementById("prenom_user").value = candidat.ca_prenom
        date = new Date(candidat.ca_date_naiss)
        document.getElementById("date_naissance_user").value =formatDate(date)
        selectOptionByValue("sexe_user", candidat.ca_sexe)
        selectOptionByValue("statut_mat_user", candidat.ca_statut_mat)
        selectOptionByValue("langue_user", candidat.ca_premiere_lang)
        selectOptionByValue("cursus", candidat.filiere_code)
        selectOptionByValue("ca_handicap", candidat.ca_handicap)
        selectOptionByValue("ecole_user", candidat.code_site==1?"ESTLC":"ISLAPE")
        let nat = candidat.ca_nationalite =="Cameroun"?"CMR":"Cameroun"
        selectOptionByValue("nationalite_user", nat)
        document.getElementById("region_origine_user").innerHTML = "<option value="+candidat.ca_region_origine+">"+candidat.ca_region_origine+"</option>"
        document.getElementById("depart_origine_user").innerHTML = "<option value="+candidat.ca_depart_origine+">"+candidat.ca_depart_origine+"</option>"
        document.getElementById("lieu_naissance_user").value = candidat.ca_lieu_naiss
        document.getElementById("first_phone_user").value = candidat.ca_telephone
        document.getElementById("numero_cni_user").value = candidat.ca_num_cni
        document.getElementById("email_user").value = candidat.ca_email
        document.getElementById("lieu_resi_user").value = candidat.ca_adresse
        document.getElementById("date_deliv_cni_user").value = formatDate(new Date(candidat.ca_deliv_cni))
        let diplome = candidat.ca_diplome_admission ==1?"BACC":"GCE"
        document.getElementById("label_di").value =diplome +" "+ candidat.label_serie
        document.getElementById("annee_dip").value = candidat.ca_annee_diplome
        document.getElementById("mention_dip").value = candidat.ca_mention_diplome
        document.getElementById("institution_dip").value = candidat.ca_etab_diplome
        document.getElementById("pays_dip").value = candidat.ca_pays_diplome

        document.getElementById("nom_pere_user").value = candidat.ca_nom_pere
        document.getElementById("telephone_tuteur_user").value = candidat.ca_telephone_pere
        document.getElementById("email_tuteur_user").value = candidat.ca_email_pere
        document.getElementById("nom_mere_user").value = candidat.ca_nom_mere
        document.getElementById("telephone_mere").value = candidat.ca_telephone_mere
        document.getElementById("code_cand").value = candidat.ca_code
        $("#saveEtudiantModal").modal("show");
    }
</script>
@endsection
@section("content")
@if(session('user'))
<div class="alert alert-primary alert-dismissible" role="alert">
    <p>Inscription reussie du candidat. Le marticule du candidat est : <span class="h3">{{ session('user')->code_user}}</span>
    </p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
<div class="modal fade" id="saveEtudiantModal" tabindex="-1">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary p-1" style="color: white">
                <h4 class="modal-title text-white">Inscription d'un Etudiant</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background-color: rgb(245,245,249);">
                    <div class="card-form" style="clear: both;">
                        <form action="/inscription_administrative" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="type" value="backend">
                            <input type="hidden" name="code_cand" id="code_cand">
                            <div class="card-body p-0">
                                <fieldset class="rounded">
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <label for="nom_user" class="col-sm-3 col-form-label">Nom <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Exp: ATANGANA MOUHAMADOU" name="nom_user" id="nom_user">
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
                                                        <option value="MASCULIN">Homme</option>
                                                        <option value="FéMININ" >Femme</option>
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
                                                        <option value="CéLIBATAIRE" >Célibataire</option>
                                                        <option value="MARIé">Marié</option>
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
                                                            <option value="FRANçAIS">Français</option>
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
                                        <div class="col-sm-6" id="ca_nationali" style="display: none;">
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
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" id="departement">
                                            <div class="row">
                                                <label for="depart_origine_user" class="col-sm-4 col-form-label">Département D'origine<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <select class="form-select" name="depart_origine_user" id="depart_origine_user" onchange="chargeArrond(this.value)">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-2">
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
                                    <legend class="bg-secondary text-white">Informations Académique</legend>
                                    <div class="row mb-4 p-2">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="code_filiere" class="col-sm-4 col-form-label">Filière <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <select name="code_filiere" id="cursus" class="form-select" required><!-- onchange= chargeDiplome() -->
                                                        @foreach (\App\Models\Filiere::all() as $c)
                                                            <option value="{{$c->code_filiere}}"  >{{$c->label_filiere}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="label_di" class="col-sm-6 col-form-label">Diplôme d'admission<span class="text-danger">*</span></label>
                                                <div class="col-sm-6">
                                                    <input type="hidden" name="specialite_dip"  value="">
                                                    <input type="hidden" name="label_dip"  value="">
                                                    <input type="text" class="form-control" placeholder="BACC A" name="label_di" id="label_di" minlength="5">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="annee_dip" class="col-sm-6 col-form-label">Année Diplôme <span class="text-danger">*</span></label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" placeholder="Exp: 2023" name="annee_dip" id="annee_dip" minlength="4" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <label for="mention_dip" class="col-sm-4 col-form-label">Mention<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" placeholder="Exp: Bien" name="mention_dip" id="mention_dip" minlength="5" >
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
                                <fieldset class="rounded">
                                    <legend class="bg-secondary text-white">Autres Informations</legend>
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

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row p-2">
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
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <label for="ecole_user" class="col-sm-6 col-form-label">Site de Formation<span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                            <select name="ecole_user" id="ecole_user" class="form-select" required>
                                                                <option value="ESTLC">ESTLC</option>
                                                                <option value="ISLAPE">ISLAPE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer text-center">
                                <button type="reset" class="btn btn-danger m-3" >Annuler</button>
                                <button type="submit" class="btn btn-primary">S'Inscrire</button>
                            </div>
                        </form>
                    </div>
          </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row">
                    <h4 class="card-title col-2">Nos candidats</h4>
                    <div class=" col-4">
                        <form action="/search_candidats" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-5">
                                    <select name="filiere_code" id="filiere_code" class="form-select">
                                        @foreach (\App\Models\Filiere::all() as $c)
                                            <option value="{{$c->code_filiere}}"  >{{$c->label_filiere}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-5">
                                    <select name="code_site" id="code_site" class="form-select">
                                        <option value="1">ESTLC</option>
                                        <option value="2">ISLAPE</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-outline-primary"><i class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-4">
                        <form action="find_candidats" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-8">
                                    <input type="text" placeholder="rechercher par nom, prenons ou tel" name="keyword", id="keyword" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-outline-primary"><i class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-outline-primary" data-bs-target="#saveEtudiantModal" data-bs-toggle="modal"> Inscire <i class="ri-add-circle-fill"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover datatable" id="filterTable">
                        <thead>
                            <tr style="text-transform: uppercase;">
                                <th>N°</th>
                                <th>Code</th>
                                <th>V</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Filiere</th>
                                <th>Né le</th>
                                <th>à</th>
                                <th>Sexe</th>
                                <th>Téléphone</th>
                                <th>Région</th>
                                <th>Departement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($candidats as $candidat)
                                <tr>
                                    <td @if ($candidat->ca_email_pere != '')
                                        style="background: green; color:white;"
                                    @else
                                        style="background: white;"
                                    @endif>{{ $loop->index +1 }}</td>
                                    <td>{{ $candidat->ca_code }}</td>
                                    <td class="text-center"><a href="#" onclick="showInscriptionForm({{$candidat}})" class="p-1 btn-success rounded"><i class="ri-check-line"></i></a></td>
                                    <td>{{ $candidat->ca_nom }}</td>
                                    <td>{{ $candidat->ca_prenom }}</td>
                                    <td>{{ $candidat->filiere_code }}</td>
                                    <td>{{ $candidat->ca_date_naiss->format("d/m/Y") }}</td>
                                    <td>{{ $candidat->ca_lieu_naiss }}</td>
                                    <td>{{ \Str::substr($candidat->ca_sexe, 0, 1) }}</td>
                                    <td>{{ $candidat->ca_telephone }}</td>
                                    <td>{{ $candidat->ca_region_origine }}</td>
                                    <td>{{ $candidat->ca_depart_origine }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
