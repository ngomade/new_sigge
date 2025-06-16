<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('concours/frontend/pdf.css')}}" rel="stylesheet">
    <title> {{$ca->ca_code}}.pdf</title>
</head>
<body>
    <header>
        <img src="{{asset('concours/frontend/img/entete_fiche.png')}}" alt="entete de la fiche">
    </header>
    <div>
        <table class="entete">
            <tr>
                <td rowspan="2" style="width: 15%;">
                    <img src="{{storage_path().DIRECTORY_SEPARATOR. 'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'cartes'.DIRECTORY_SEPARATOR.getdate()['year'].DIRECTORY_SEPARATOR.$ca->ca_photo}}" alt="Photo du Candidat">
                </td>
                <td colspan="2" class="titre" style="padding-bottom: 0px;"> FICHE D'INSCRIPTION AU CONCOURS D'ENTREE à L'ESTLC SESSION  {{getdate()['year']}}<hr style="margin: 0;"> <span style="color:rgb(19, 115, 224);">CURSUS INGENIEUR</span> </td>
            </tr>
            <tr>
                <td style="width: 70%; text-align: center;"> INSCRIPTION N° <span style="color: red;">{{$ca->ca_code}} </span></td>
                <td style="width: 40%; text-align: center; font-family: 'Arial Narrow'; font-style: italic;"> Timbre Fiscal ici / Stamp here</td>
            </tr>
        </table>
    </div>
    <div class="info">
        <h3 style="margin-top: 5px;"><span>Informations Personnelles / Personal Informations</span></h3>
        <div class="item-info-2"> Nom: <span style="text-transform: uppercase;"> {{$ca->ca_nom}} </span></div>
        <div class="item-info-2"> Prénom: <span style="text-transform: uppercase;"> {{$ca->ca_prenom}} </span></div>
        <div class="item-info-3">Date naissance: <span> {{\Str::substr($ca->ca_date_naiss, 0, 10)}} </span></div>
        <div class="item-info-3" style="margin-left: 40px;">Lieu de naissance : <span> {{$ca->ca_lieu_naiss}} </span></div>
        <div class="item-info-4" style="margin-left: 40px;">Sexe : <span> {{$ca->ca_sexe}} </span></div>
        <div class="item-info-4" style="margin-left: 15px;"> Nationalité: <span> {{$ca->ca_nationalite}} </span></div>
        <div class="item-info-3"> Région d'origine: <span> {{$ca->ca_region_origine}} </span></div>
        <div class="item-info-2" style="margin-left: -45px; text-align: center;"> Département d'origne: <span> {{$ca->ca_depart_origine}} </span></div>
        <div class="item-info-3"> CNI: <span> {{$ca->ca_num_cni}} </span></div>
        <div class="item-info-3"> Téléphone: <span>(+237) {{$ca->ca_telephone}} </span></div>
        <div class="item-info-3"> Adresse: <span> {{$ca->ca_adresse}} </span></div>
        <div class="item-info-2"> 1<sup>ière</sup> Langue: <span> {{$ca->ca_premirere_lang}} </span></div>
        <div class="item-info-2"> Email: <span> {{$ca->ca_email}} </span></div>
    </div>

    <div class="info">
        <h3 style="margin-top: -5px;"><span>Informations Académique / Academic Informations</span></h3>
        <div class="item-info-3"> Filière: <span style="font-size: 0.9em;">TRONC COMMUN {{$ca->cursus_code}} </span></div>
        <div class="item-info-2" > Diplôme d'admission: <span> {{$ca->ca_diplome_admission}} {{$ca->ca_serie_diplome}} </span></div>
        <div class="item-info-4" style="margin-left: -25px;">Mention: <span> {{$ca->ca_mention_diplome}} </span></div>
        <div class="item-info-3" >Année diplôme : <span> {{$ca->ca_annee_diplome}} </span></div>
        <div class="item-info-3" style="margin-left: -50px;">Centre d'examen : <span> {{$ca->ca_centre_examen}} </span></div>
        <div class="item-info-3" style="margin-left: -25px;"> Centre de dépôt: <span> {{$ca->ca_centre_depot}} </span></div>
    </div>

    <div class="info">
        <h3 style="margin-top: -5px;"><span>Autres Informations  / Others Informations</span></h3>
        <div class="item-info-2" > Nom du père: <span> {{$ca->ca_nom_pere}} </span></div>
        <div class="item-info-2" > Téléphone du père: <span>(+237) {{$ca->ca_telephone_pere}} </span></div>
        <div class="item-info-2">Nom de la mère: <span> {{$ca->ca_nom_mere}} </span></div>
        <div class="item-info-2" >Téléphone de la mère: <span>(+237)  {{$ca->ca_telephone_mere}} </span></div>
    </div>
    <div class="consigne">
        <h3 style="margin-top: -7px;">Documents Néccessaires /Neccessary Documents</h3>
        <ul>
            <li> Une photocopie certifiée d'acte de naissance datant de moins de trois (3) mois ; <br>
            <span class="english">A certified true photocopy of the birth certificate issued within the last three (03) months;</span></li>
            <li> Une photocopie certifiée conforme du diplôme/attestation de réussite du Baccalauréat ou du GCE A/L ou du diplôme équivalent ; <br>
                <span class="english">A certified true copy of the required diploma;</span></li>
            <li> Un certificat de scolarité de la classe de terminale/upper sixth ; <br>
                <span class="english">A certificate of school attendance for "Terminale" or Upper 6th;</span> </li>
            <li> Un certificat médical délivré par un médecin fonctionnaire, datant de moins de trois (03) mois et certifiant que le candidat est apte à poursuivre des études supérieures ; <br>
                <span class="english"> A medical certificate issued within the last three (03) months by a state medical practitioner, and testifying that the candidate is fit for higher education;</span></li>
            <li> Un reçu de versement bancaire d'un montant de vingt mille (20 000) francs CFA représentant les droits d'inscription au concours de l'ESTLC de l'Université d'Ebolowa, à verser au compte SCB Cameroun, N° 10002-00059-90001487491-26 dans toutes les agences du Cameroun (Aucun autre mode de paiement ne sera accepté) ; <br>
                <span class="english">A twenty thousand (20,000) CFA francs banking receipt representing examination fees of the competitive entrance at HITLC of the University of Ebolowa to be paid at SCB Cameroon account, N° 10002-00059-90001487491-26 in all agencies in Cameroon (No other method of payment will be accepted);</span></li>
            <li> Une enveloppe A4 timbrée au tarif réglementaire et portant l'adresse exacte du candidat ; <br>
                <span class="english">A 21 x 29.7 size self-addressed envelope bearing a 400 CFA francs postal stamp</span> </li>
        </ul>
    </div>

    <footer>
        <div class="connexion">Code: <span style="color:red">{{$ca->ca_code}}</span>   Mot de Passe:  <span style="color: red;">{{$ca->ca_pwd}}</span></div>
        <div class="imp">Imprimée le <?php echo date("d/m/Y");?> </div>
    </footer>
</body>
</html>
