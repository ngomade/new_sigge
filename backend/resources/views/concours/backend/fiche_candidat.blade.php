@extends("concours.backend.template.backend_concours")
@section("content")
<main id="main" class="main ">
    <div class="card">
        <div class="row">
            <div class="col-sm-2" style=" display: flex; justify-content: center;">
                <img src="{{asset("storage/cartes").DIRECTORY_SEPARATOR.getdate()['year']."/".$ca->ca_photo}}" alt="Photo du Candidat" style="width: 100%">
            </div>
            <div class="col-sm-10">
                <div class="row p-4">
                    <h3 style="border-bottom: 1px solid gray;"><span>Informations Personnelles / Personal Informations</span></h3>
                    <div class="col-sm-3 mt-3"> Nom: <span class="info_detail" style="text-transform: uppercase; font-weight: bold;"> {{$ca->ca_nom}} </span></div>
                    <div class="col-sm-3 mt-3"> Prénom: <span class="info_detail" style="text-transform: uppercase;  font-weight: bold;"> {{$ca->ca_prenom}} </span></div>
                    <div class="col-sm-3 mt-3">Date naissance: <span class="info_detail"> {{\Str::substr($ca->ca_date_naiss, 0, 10)}} </span></div>
                    <div class="col-sm-3 mt-3" >Lieu de naissance : <span class="info_detail"> {{$ca->ca_lieu_naiss}} </span></div>
                    <div class="col-sm-3 mt-3" >Sexe : <span class="info_detail"> {{$ca->ca_sexe}} </span></div>
                    <div class="col-sm-3 mt-3" > Nationalité: <span class="info_detail"> {{$ca->ca_nationalite}} </span></div>
                    <div class="col-sm-3 mt-3"> Région d'origine: <span class="info_detail"> {{$ca->ca_region_origine}} </span></div>
                    <div class="col-sm-3 mt-3" > Département d'origne: <span class="info_detail"> {{$ca->ca_depart_origine}} </span></div>
                    <div class="col-sm-3 mt-3"> CNI: <span class="info_detail"> {{$ca->ca_num_cni}} </span></div>
                    <div class="col-sm-3 mt-3"> Téléphone: <span class="info_detail">(+237) {{$ca->ca_telephone}} </span></div>
                    <div class="col-sm-3 mt-3"> Adresse: <span class="info_detail"> {{$ca->ca_adresse}} </span></div>
                    <div class="col-sm-3 mt-3"> 1<sup>ière</sup> Langue: <span class="info_detail"> {{$ca->ca_premirere_lang}} </span></div>
                    <div class="col-sm-3 mt-3"> Email: <span class="info_detail"> {{$ca->ca_email}} </span></div>
                    <div class="col-sm-3 mt-3"> Statut Matrimoniale: <span class="info_detail"> {{$ca->ca_statut_mat}} </span></div>
                    <div class="col-sm-3 mt-3"> Handicap? : <span class="info_detail"> {{$ca->ca_handicap}} </span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="row p-4">
            <h3 style="border-bottom: 1px solid gray;"><span>Informations Académique / Academic Informations</span></h3>
            <div class="col-sm-4 mt-3"> Filière: <span class="info_detail">TRONC COMMUN {{$ca->cursus_code}} </span></div>
            <div class="col-sm-4 mt-3" > Diplôme d'admission: <span class="info_detail"> {{$ca->ca_diplome_admission}} {{$ca->ca_serie_diplome}} </span></div>
            <div class="col-sm-4 mt-3" >Mention: <span class="info_detail"> {{$ca->ca_mention_diplome}} </span></div>
            <div class="col-sm-4 mt-3" >Année diplôme : <span class="info_detail"> {{$ca->ca_annee_diplome}} </span></div>
            <div class="col-sm-4 mt-3" >Centre d'examen : <span class="info_detail"> {{$ca->ca_centre_examen}} </span></div>
            <div class="col-sm-4 mt-3" > Centre de dépôt: <span class="info_detail"> {{$ca->ca_centre_depot}} </span></div>
        </div>
    </div>
    <div class="card">
        <div class="row p-4">
            <h3 style="border-bottom: 1px solid gray;"><span>Autres Informations  / Others Informations</span></h3>
            <div class="col-sm-4 mt-3" > Nom du père: <span class="info_detail"> {{$ca->ca_nom_pere}} </span></div>
            <div class="col-sm-4 mt-3" > Téléphone du père: <span class="info_detail">(+237) {{$ca->ca_telephone_pere}} </span></div>
            <div class="col-sm-4 mt-3" > Email du père: <span class="info_detail">{{$ca->ca_email_pere}} </span></div>
            <div class="col-sm-4 mt-3">Nom de la mère: <span class="info_detail"> {{$ca->ca_nom_mere}} </span></div>
            <div class="col-sm-4 mt-3" >Téléphone de la mère: <span class="info_detail">(+237)  {{$ca->ca_telephone_mere}} </span></div>
        </div>
        <footer class="row">
            <div class="connexion col-sm-6" style="margin-left: 5%;">Code: <span style="color:red">{{$ca->ca_code}}</span>  </div>
            <div class="imp col-sm-5">Mot de Passe:  <span style="color: red;">{{$ca->ca_pwd}}</span> </div>
        </footer>
    </div>
    <div class="card p-3">
        <div class="row">
            <div class="col-sm-2" style=" display: flex; justify-content: center;"><a href="/index_admin_concours" class="btn btn-outline-primary"><i class="ri-arrow-left-fill"></i> Retour</a></div>
            <div class="col-sm-8" style=" display: flex; justify-content: center;"></div>
            <div class="col-sm-2" style=" display: flex; justify-content: center;"><a href="/impression/{{$ca->ca_code}}" class="btn btn-outline-success"><i class="ri-printer-fill"></i> Imprimer la fiche</a></div>
        </div>
    </div>
</main>
@endsection
