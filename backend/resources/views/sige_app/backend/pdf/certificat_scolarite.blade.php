<!DOCTYPE html>
<<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{public_path()."/share/css/fiche.css"}}">
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
            border-width: 10px !important; /* Épaisseur de la bordure */
            border-style: double !important; /* Nécessaire pour appliquer border-image */
            border-color: green !important;
            /*border-image: url('{{public_path()."/share/img/entete_fiche.png"}}') 30 round;  Image utilisée pour la bordure */
            margin: -5px !important;
            padding: 5px !important;
        }
        p{
            margin-bottom: 2px;
        }
        .ligne, .texte{
            width: 98%;
            margin: auto;
            vertical-align:center;
            font-family: "Arial Narrow";
        }
        .texte{
            font-family: "Arial Narrow";
            font-size: 0.77em;
            text-align: justify;
        }
        .label{
            width: 15%;
            text-align: left;
            display: inline-block;
        }
        .info-long{
            text-transform: uppercase;
            width: 80%;
            display: inline-block;
            font-weight: bold;
            font-size: 1.25em;
            margin-top: -25px;
        }
        .info{
            text-transform: uppercase;
            width: 30%;
            display: inline-block;
            font-weight: bold;
            font-size: 1.25em;
            margin-top: -25px;
        }
        .info-short{
            text-transform: uppercase;
            width: 17%;
            display: inline-block;
            font-weight: bold;
            font-size: 1.25em;
            margin-top: -25px;
        }
        .certificat{
            height: 820px;
            padding-top: 8px;
        }
    </style>
    <title>CERTIFICAT {{$code_filiere}}.pdf</title>
</head>
<body>
   @foreach ($etudiants as $user)
   <?php $u = $user;
         \Carbon\Carbon::setLocale('fr');
         $date_naiss = $u->date_naissance_user->translatedFormat('d/m/Y');
    ?>
    <div class="certificat">
            <header>
                @if ($code_filiere == "TTL")
                <img src="{{public_path()."/share/img/entete_ttl.png"}}" alt="entete de la fiche" style="height: 200px;">
                @else
                <img src="{{public_path()."/share/img/entete_fiche.png"}}" alt="entete de la fiche" style="height: 200px;">
                @endif
            </header>
            <p class="titre" style="text-align: center;font-family: 'Arial Narrow';">
                CERTIFICAT DE SCOLARITE <br>
                <i>CERTIFICATE OF SCHOOL ATTENDANCE</i>
            </p>
            <p style="text-align: center; font-family: 'Arial Narrow'; font-size: 1.1em;">
                N°<u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</u> /UEb/ESTLC/DA/DAARC/DSSE/CISI
            </p>
            <p class="texte" style="margin-top: 10px; margin-bottom: 5px;">
                Je soussigné(e), le Directeur de l'Ecole Supérieure de Transport, de Logistique et de Commerce de l'Université d'Ebolowa, atteste que, <br>
                <i>I, the undersigned, Director of the Higher Institute of Transport, Logistics and Commerce of the University of Ebolowa, hereby certify that, </i>
            </p>

            <div class="ligne">
                <p class="label">M/Mlle <br> <i>Mr/Miss</i> </p>
                <p class="info-long"> : {{$user->nom_user}}  {{$user->prenom_user}} </p>
            </div>
            <div class="ligne">
                <p class="label">Né(e) le  <br> <i>Born on the</i> </p>
                <p class="info"> : {{$date_naiss}}</p>
                <p class="label">A <br> <i>At</i> </p>
                <p class="info"> : {{$user->lieu_naissance_user}}</p>
            </div>
            @if ($user->ecole_user =="ESTLC")
                <p class="texte">
                    Est étudiant(e) régulièrement inscrit(e) à l'Ecole Supérieure de Transport, de Logistique et de Commerce  de l'Université d'Ebolowa <br>
                    <i>Is regularly enrolled as a student of the Higher School of Transport, Logistics and Commerce of the University of Ebolowa </i>
                </p>
            @else
                <p class="texte">
                    Est étudiant(e) régulièrement inscrit(e) à l'Institut Supérieure La Perle (ISLAPE) de Douala, sous la tutelle académique de l'Ecole Superieure de Transport, de Logistique et de Commerce (ESTLC) de l'Universite d'Ebolowa à Ambam <br>
                    <i>Is regularly enrolled as a student of the Higher Institute La Perle (ISLAPE) of Douala under the academic supervision of the Higher School of Transport, Logistics and Commerce (HSTLC) of the University of Ebolowa at Ambam</i>
                </p>
            @endif
            <div class="ligne">
                <p class="label">N° Matricule <br> <i>Registration N°</i> </p>
                <p class="info-short" style="font-size: 18px;"> : {{$user->code_user}} </p>
                <p class="label" style="margin-left: 5px;">Niveau <br> <i>Level</i> </p>
                <p class="info-short"> : {{$niveau}} </p>
                <p class="label" style="font-size: 0.8em;">Année Académique <br> <i>Academic Year</i> </p>
                <p class="info-short"> : 2024/2025 </p>
            </div>
            <div class="ligne">
                <p class="label">Cycle <br> <i>Cycle</i> </p>
                <p class="info-long"> : INGENIEUR / <i>ENGINEER</i>  </p>
            </div>
            <div class="ligne">
                <p class="label">Spécialité <br> <i>Field</i> </p>
                <p class="info-long" style="font-size: 0.9em;"> :
                    @if ($code_filiere == "TTL")
                        Technologie de Transport et de Logistique
                    @else
                        Gestion Logistique, Transport et Commerce
                    @endif  </p>
            </div>
            <p class="texte">
                En foi de quoi le présent certificat lui est délivré pour servir et valoir ce que de droit.<br>
                <i>This certificate is issued to serve the purpose for which it is required</i>
            </p>
            <p style="text-align: right; font-family: 'Arial Narrow'; width:95%; margin-top: 5%;">
               <b>AMBAM le</b> /<i> AMBAM on</i>  <u> &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</u>
            </p>
            <p style="text-align: right; font-size: 1.08em;  width:90%; margin-top: 3%;"><b>Le Directeur Adjoint</b><br> <i>The Deputy Director</i></p>
        </div>
   @endforeach
</body>
</html>
