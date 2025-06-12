<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{public_path()."/share/css/fiche_academique.css"}}">
    <title></title>

</head>
<body>
    <?php
         \Carbon\Carbon::setLocale('fr');
         $date_naiss = $user->date_naissance_user->translatedFormat('d/m/Y');
    ?>
    <header>
        <img src="{{public_path()."/share/img/entete_fiche.png"}}" alt="entete de la fiche">
    </header>
    <div class="row" style=" margin-top:10px;">
        <div class="col-sm-10">
            <div class="service"> DIVISION DE LA SCOLARITE ET DES STATISTIQUES</div>
            <div class="service1"> SERVICE DE LA SCOLARITE</div>
            <div class="titre">
                <span class="french">FICHE D'INSCRIPTION ACADEMIQUE</span>
                <span class="english">ACADEMIC REGISTRATION FORM</span>
            </div>
            <div class="ligne">
                <div class="label">
                    <span class="french"> Cursus : </span>
                    <span class="english"> Curriculum :</span>
                </div>
                <div class="valeur">
                   @if ($filiere->code_filiere == "TTL")
                        TECHNOLOGIE DE TRANSPORT ET DE LOGISTIQUE (TTL)
                   @else
                        GESTION LOGISTIQUE, TRANSPORT ET COMMERCE (GLTCO)
                   @endif
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <p >PHOTO 4 X 4</p>
        </div>
    </div>
    <div class="row" style="margin-top: -45px;">
        <div class="titre-l">
            <span class="francais">Noms et Prénoms : </span>
            <span class="anglais"> Surname and Name :</span>
        </div>
        <div class="titre-v" style="font-size: 0.9em;">
            {{$user->nom_user}} &nbsp;  {{$user->prenom_user}}
        </div>
    </div>
    <div class="row" style="margin-top: 5px; font-size: 0.9em;">
        <div class="col-sm-6">
            <div class="titre-l" style="width: 35%;">
                <span class="francais">Date de Naissance : </span>
                <span class="anglais"> Date of Birth :</span>
            </div>
            <div class="titre-v" style="width: 63%;">
                {{$date_naiss}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="titre-l" style="width: 35%;">
                <span class="francais">Lieu de Naissance : </span>
                <span class="anglais"> Place of Birth :</span>
            </div>
            <div class="titre-v" style="width: 63%;">
                {{$user->lieu_naissance_user}}
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 5px; font-size: 0.9em;">
        <div class="col-sm-6">
            <div class="titre-l" style="width: 35%;">
                <span class="francais">Région d'Origine : </span>
                <span class="anglais"> Region :</span>
            </div>
            <div class="titre-v" style="width: 63%;">
                {{$user->region_origine_user}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="titre-l" style="width: 35%;">
                <span class="francais">Nationalité : </span>
                <span class="anglais">Nationality :</span>
            </div>
            <div class="titre-v" style="width: 63%;">
                @if ($user->nationalite_user=="CMR")
                    CAMEROUN
                @else
                    {{$user->nationalite_user}}
                @endif
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 5px; font-size: 0.9em;">
        <div class="col-sm-6">
            <div class="titre-l" style="width: 35%;">
                <span class="francais">Matricule : </span>
                <span class="anglais"> Registration Number :</span>
            </div>
            <div class="titre-v" style="width: 63%;">
                {{$user->code_user}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="titre-l" style="width: 35%;">
                <span class="francais">Année Academique : </span>
                <span class="anglais">Academic Year :</span>
            </div>
            <div class="titre-v" style="width: 63%;">
                2024/2025
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: -7px">
        <p style="width: 95%; margin:auto; font-size: 0.7em; font-weight: bold; text-align: left;">Numéro des réçus de paiment des droits universitaires / Receipt numbers for payement of university dues</p>
    </div>
    <div class="row" style="margin-top: ">
        <div class="taille-4">
           <div class="text-box">
                <span class="francais">1iere Tranche: </span>
                <span class="anglais">1st Instalment :</span>
           </div>
           <div class="text-box-value"></div>
        </div>
        <div class="taille-4">
            <div class="text-box">
                 <span class="francais">2ieme Tranche: </span>
                 <span class="anglais">2nd Instalment :</span>
            </div>
            <div class="text-box-value"></div>
         </div>
         <div class="taille-4">
            <div class="text-box">
                 <span class="francais">TOTAL: </span>
            </div>
            <div class="text-box-value"></div>
         </div>
    </div>
    <div class="row">
       <table style="border-collapse: collapse;">
            <thead>
                <tr style="text-align: center;">
                    <th>N°</th>
                    <th>CODE UE</th>
                    <th>INTITULE</th>
                    <th>SEM</th>
                    <th>CREDIT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; $t_ue = 0 ?>
                @foreach ($ues as $ue)
                <?php $total += \App\Helper\get_nb_credit($ue->code_ue); $t_ue += 1; ?>
                    <tr>
                        <td>{{$loop->index +1}}</td>
                        <td> {{$ue->code_ue}} </td>
                        <td> {{$ue->intitule_ue}} </td>
                        <td> {{$ue->semestre->code_sem}} </td>
                        <td>{{\App\Helper\get_nb_credit($ue->code_ue)}}</td>
                        <td style="text-align: center;"> <input type="checkbox" name="" id="" required> </td>
                    </tr>
                @endforeach
            </tbody>
       </table>
    </div>

    <div class="row" style="margin-top: -1px">
        <div class="nb_ue">
            Nombre Total des Ues / credits : &nbsp; &nbsp;&nbsp; <?php echo $t_ue ." / ". $total ?>
        </div>
        <div class="ambam">
            AMBAM le <?php echo date("d/m/Y");?>
        </div>
    </div>

    <div class="row" style="margin-top: 5px">
        <div class="scol">
            <span class="francais">Date et signature du Responsable de la Scolarité</span>
            <span class="anglais">Date and Signature of the admission's Officer</span>
        </div>
        <div class="sign">
            <span class="francais">Date et Signature de l'étudiant</span>
            <span class="anglais">Date et Signature of the student</span>
        </div>
    </div>

    <footer>
        <div class="imp">Imprimée le <?php echo date("d/m/Y H:i:s");?> </div>
    </footer>
</body>
</html>
