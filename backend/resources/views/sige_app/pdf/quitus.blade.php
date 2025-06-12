<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset("share/css/quitus.css")}}">
    <style>
        body{
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            background-size: contain;
            height: 100vh;
            width: 100%;
            background-color: rgba(255,255,255,0.15);
        }
    </style>
    <title> {{$user->code_user}}.pdf</title>
</head>
<body>
    <?php
         \Carbon\Carbon::setLocale('fr');
         $date_naiss = $user->date_naissance_user->translatedFormat('d/m/Y');
    ?>
    <header>
        <img src="{{asset("share/img/entete_quitus.png")}}" alt="entete de la fiche">
    </header>
    <div class="titre">
        <span class="ecole">ECOLE SUPERIEURE DE TRANSPORT, DE LOGISTIQUE ET DE COMMERCE</span>
        <span class="tranche">{{\Str::upper($type_tranche. " - ". $tranche->lable_tranche)}}</span>
        <span class="aca">{{$aca->debut_annee->year. "/".$aca->fin_annee->year}}</span>
    </div>
    <div class="row">
        <div class="taille-8">
            <div class="ligne">
                <div class="label">
                    <span class="french"> Noms et Prénoms </span>
                    <span class="english"> Surname and Name</span>
                </div>
                <div class="valeur">
                    {{$user->nom_user}}  {{$user->prenom_user}}
                </div>
            </div>

            <div class="ligne">
                <div class="label">
                    <span class="french"> Né(e) le </span>
                    <span class="english"> Born on</span>
                </div>
                <div class="valeur">
                    {{$date_naiss}} &nbsp; &nbsp; &nbsp; &nbsp;  à  &nbsp; &nbsp;  {{$user->lieu_naissance_user}}
                </div>
            </div>
            <div class="ligne">
                <div class="label">
                    <span class="french"> Formation </span>
                    <span class="english"> Training</span>
                </div>
                <div class="valeur" style="font-size: 0.8em; text-align: center;">
                    {{\Str::upper($filiere->label_filiere)}}
                </div>
            </div>
            <div class="ligne">
                <div class="label">
                    <span class="french"> Type de Formation </span>
                    <span class="english"> Type of Training</span>
                </div>
                <div class="valeur">
                    @if($filiere->code_filiere == "UFD-TSI")
                        Formation Doctorale
                    @else
                        Formation Initiale
                    @endif
                </div>
            </div>

            <div class="ligne">
                <div class="label">
                    <span class="french"> Montant Net à Payer </span>
                    <span class="english"> Amount</span>
                </div>
                <div class="valeur">
                    {{$tranche->montant_tranche}} FCFA
                </div>
            </div>
            <div class="ligne">
                <div class="label">
                    <span class="french"> Numéro de Compte </span>
                    <span class="english"> Account Number</span>
                </div>
                <div class="valeur">
                    @if (Str::upper($type_tranche) != "INSCRIPTION")
                        10039-10012-00272772201-07
                    @else
                        10039-10012-00272768601-40
                    @endif
                </div>
            </div>
        </div>
        <div class="taille-4">
            <div class="ligne">
                <div class="label">
                    <span class="french"> Matricule </span>
                    <span class="english"> Register</span>
                </div>
                <div class="valeur">
                    {{$user->code_user}}
                </div>
            </div>
            <div class="ligne">
                <div class="label">
                    <span class="french"> Pays </span>
                    <span class="english"> Country</span>
                </div>
                <div class="valeur">
                    <?php
                         if($user->nationalite_user == "CMR"){
                            echo "CAMEROUN";
                         }
                         ?>
                </div>
            </div>
            <div class="ligne">
                <div class="label">
                    <span class="french"> Cycle </span>
                    <span class="english"> Cycle</span>
                </div>
                <div class="valeur">
                    @if($filiere->code_filiere == "UFD-TSI")
                        MASTER
                    @else
                        CURSUS INGENIEUR
                    @endif
                </div>
            </div>
            <div class="ligne">
                <div class="label">
                    <span class="french"> Niveau </span>
                    <span class="english"> Level</span>
                </div>
                <div class="valeur">
                    {{\Session::get("inscription")->code_niveau}}
                </div>
            </div>
            <div class="ligne">
                <div class="label">
                    <span class="french"> BANQUE </span>
                    <span class="english"> Bank</span>
                </div>
                <div class="valeur">
                    CCA Bank
                </div>
            </div>
        </div>
    </div>
    <div class="row-fin">
        <div class="taille-3">
            <img src="data:image/png;base64,{{ $qrcode }}">
        </div>
        <div class="taille-6">
            {{\Str::upper($quitus->numero_quitus)}}
        </div>
        <div class="taille-3">
            <p>Le Caissier</p>
        </div>
    </div>

    <footer>
        <div class="imp">Imprimée le <?php echo date("d/m/Y H:i:s");?> </div>
    </footer>
</body>
</html>
