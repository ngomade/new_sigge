<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset("share/css/fiche.css")}}">
    <style>
        body{

            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            background-size: contain;
            height: 100vh;
            width: 100%;
            opacity: 0.8;
            background-color: rgba(255,255,255,0.15);
        }
    </style>
    <title> {{$user->code_user}}.pdf</title>
</head>
<body>
    <?php $u = $user;
         \Carbon\Carbon::setLocale('fr');
         $date_naiss = $u->date_naissance_user->translatedFormat('d/m/Y');
    ?>
    <header>
        <img src="{{asset("share/img/entete_fiche.png")}}" alt="entete de la fiche">
    </header>
    <div class="titre">
        FICHE D'INSCRIPTION ADMINISTRATIVE
    </div>
    <div class="row">
        <div class="col-sm-10">
            <div class="item-info"> <span class="label-fiche">Nom:</span> <span style="text-transform: uppercase;"> {{$u->nom_user}} </span></div>
            <div class="item-info"> <span class="label-fiche"> Prénom:</span> <span style="text-transform: uppercase;"> {{$u->prenom_user}} </span></div>
            <div class="item-info"><span class="label-fiche">Date naissance:</span> <span> {{$date_naiss}} </span></div>
            <div class="item-info" ><span class="label-fiche">Lieu de naissance :</span> <span> {{$u->lieu_naissance_user}} </span></div>
            <div class="item-info" ><span class="label-fiche">Matricule :</span> <span> {{$u->code_user}} </span></div>
        </div>
        <div class="col-sm-2">
            <p>PHOTO 4 X 4</p>
        </div>
    </div>
    <div class="row" style="margin-top: -10px;">
        <div class="col-sm-6">
                <span  class="text_label">Téléphone</span>
                <span  class="text_value">{{$u->first_phone_user}}</span>
        </div>
        <div class="col-sm-6">
                <span  class="text_label">Filière</span>
                <span  class="text_value">{{$code_filiere}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
                <span  class="text_label">Région D'origine</span>
                <span  class="text_value">{{$u->region_origine_user}}</span>
        </div>
        <div class="col-sm-6">
                <span  class="text_label">Départ. D'origine</span>
                <span  class="text_value">{{$u->depart_origine_user}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-11">
            <span  class="text_label">Arrond. D'origine</span>
            <span  class="text_value ">{{$u->arrond_origine_user}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-11">
                <span  class="text_label">Nom du père ou Tuteur</span>
                <span  class="text_value">{{$info_extra->nom_pere_user}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-11">
            <span  class="text_label">Téléphone</span>
            <span  class="text_value">{{$info_extra->telephone_tuteur_user}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-11">
                <span  class="text_label">Nom de la mère</span>
                <span  class="text_value">{{$info_extra->nom_mere_user}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-11">
            <span  class="text_label">Téléphone</span>
            <span  class="text_value">{{$info_extra->telephone_mere}}</span>
        </div>
    </div>
    <div class="row">
        <div class="dsse">
            <span>LA DIVISION DE LA SCOLARITE ET DU SUIVI DES ETUDIANTS</span>
        </div>
        <div class="daarc">
            <span>LA DIVISION DES AFFAIRES ACADEMIQUES, DE LA RECHERCHE ET DE LA COOPERATION</span>
        </div>
    </div>

    <div class="note-imp">
        Imprimer cette fiche en 03 exemplaires et déposer au Service de Scolarité.
    </div>

    <footer>
        <div class="imp">Imprimée le <?php echo date("d/m/Y H:i:s");?> </div>
    </footer>
</body>
</html>
