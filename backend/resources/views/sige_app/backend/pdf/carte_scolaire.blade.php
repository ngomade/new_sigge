<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="{{public_path()."/share/css/fiche.css"}}"> --}}
    <style>
        body{
            width: 100%;
            height: 30cm;
            padding: 0px !important;
            margin: 0px !important;
            margin-top: -6%;
            padding-top: -20px;
            margin-right: -25px;
        }
        .carte{
            height: 5cm;
            width: 8cm;
            border: 3px solid rgb(59, 149, 64);
            margin-top: 0px;
            margin-bottom: 0.16cm;
            margin-right: 20px;
            display:inline-block;
            padding: 0px 3px 1px 3px;
            border-radius: 3%;
            /*background: url("{{public_path().'/share/img/estlc_sans_fond.png'}}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;*/
        }

        .verso{
            padding: 0;
            width: 8cm;
            height: 5cm;
            background: url("{{public_path().'/share/img/estlc_sans_fond.png'}}");
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
            margin-left: 48px;
            margin-top: 1px;
            /*opacity: 0.8;*/
            overflow: hidden;
        }
        p{
            margin-bottom: 2px;
            font-size: .8em;
            padding: 0px !important;
            margin: 0px !important;
        }
        header{
            display: block;
            align-content: center;
            margin-bottom: 0px !important;
            height: 55px;
        }
        .logo{
            width: 55px;
            height: 50px;
            display: inline-block;
        }
        img{
            width: 100%;
            height: 100%;
        }
        .titre{
            margin-top: 15px;
            display: inline-block;
            text-align: center;
            width: 200px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.63em;
        }
        .ecole{
            clear: both;
            width: 100%;
            margin-top: 30px;
            margin-top: 15px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.52em;
            text-align: left;
            text-align: center;
            font-weight: bold;
        }
        main{
            clear: both;
            display: block;
            height: 80px;
            align-content: center;
            vertical-align: top;
            margin-bottom: 10px;
        }
        .photo, .data, .premier, .second{
            display: inline-block;
            vertical-align: top;
        }
        .photo{
            width: 18%;
            height: 60px;
            overflow: visible;
        }
        .data{
            width: 80%;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.9em;
        }
        .ligne{
            width: 100%;
            display: block;
        }
        .premier{
            width: 81%;
            display: inline-block;
            text-align: left;
            line-height: 14px;
        }
        .item-title{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.9em;
            color: rgb(199, 57, 57);
        }
        .item-value{
            font-family: Arial, Helvetica, sans-serif;
            font-size: .9em;
            text-transform: uppercase;
            font-weight: bold;
        }
        .second{
            width:10%;
            display: inline-block;
        }
        .demi{
            width: 80%;
            display: block;
            text-align: left;
            padding-left: 30px;
        }
        .demi .item-title{
            font-size: .8em;
        }
        .foot{
            clear: both;
            width: 100%;
            vertical-align: top;
        }
        .left{
            width: 40%;
            display: inline-block;
            line-height: 10px;
        }
        .right{
            width: 58%;
            display: inline-block;
            font-family: Arial, Helvetica, sans-serif;
        }

        .title-up{
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            padding-bottom: 10px;
        }
        .item, .value{
            color:rgb(199, 57, 57);
            width: 48%;
            display: inline-block;
            font-family:Arial, Helvetica, sans-serif;
            font-size: 0.75em;
        }
        .value{
            color: black;
            font-weight: bold;

        }
        .up{
            margin-bottom: 5px;
            padding: 10px;
            padding-bottom: 0px !important
        }
    </style>
    <title>CERTIFICAT {{$code_filiere}}.pdf</title>
</head>
<body>
    <?php $nb_carte = 0;?>
    <div id="content-recto">
        <?php $cpt = 0; $cartes = [];?>
    @foreach ($etudiants as $user)

            @if($user->photo_user != null)
                <?php $u = $user;
                \Carbon\Carbon::setLocale('fr');
                $date_naiss = $u->date_naissance_user->translatedFormat('d/m/Y');
                $nb_carte++;
                $cpt++;
                $cartes[] = $user;
                ?>

                <div class="carte" @if ($cpt%2 == 0)
                    style="margin-right: 0px !important;"
                @endif>
                    {{-- <div class="watermark"></div> --}}
                    <header>
                        <div class="logo">
                            <img src="{{public_path()."/share/img/logo_ueb.png"}}">
                        </div>
                        <div class="titre">
                            <span style="color:rgb(59, 149, 64);">UNIVERSITE D'EBOLOWA</span><br>
                            <span ><i>UNIVERSITY OF EBOLOWA</i></span><br>
                            <span style="color:rgb(97, 16, 16); font-family: 'Cambria'; font-weight: bold;  font-size: 1.2em;">CARTE D'ETUDIANT</span><br>
                            <span style="color:rgb(97, 16, 16); font-family: 'Cambria'; font-size: 1.2em; font-weight: bold;"><i>Student Id Card</i></span>
                        </div>
                        <div class="logo">
                            <img src="{{public_path()."/share/img/logo_estlc.png"}}">
                        </div>
                    </header>
                    <p class="ecole">
                        <span style="color:rgb(8, 130, 14);">ECOLE SUPERIEURE DE TRANSPORT, DE LOGISTIQUE ET DE COMMERCE</span> <br>
                        <span>HIGHER INSTITUTE OF TRANSPORT, LOGISTICS AND COMMERCE</span>
                    </p>
                    <main>
                        <div class="photo">
                            <img src="{{public_path()."/cartes/".$user->photo_user}}"  alt="">
                        </div>
                        <div class="data">
                            <div class="ligne">
                                <div class="premier">
                                    <p>
                                        <span class="item-title">Matricule / <i>Registration N°</i></span><br>
                                        <span class="item-value"> {{$user->code_user}} </span>
                                    </p>
                                    <p>
                                        <span class="item-title">Noms et Prénoms / <i>Names and Surnames</i></span><br>
                                        <span class="item-value"> {{$user->nom_user}}  {{$user->prenom_user}} </span>
                                    </p>
                                    <p class="demi">
                                        <span class="item-title">Parcours /<i>Field</i></span> &nbsp; &nbsp; &nbsp;
                                        <span class="item-value">{{$code_filiere}} </span>
                                    </p>
                                    <p class="demi" style="">
                                        <span class="item-title">Niveau /<i>Level</i></span> &nbsp; &nbsp; &nbsp;
                                        <span class="item-value">  {{$niveau}} </span>
                                    </p>
                                </div>
                                <div class="second">
                                    <?php
                                        $qrcode = base64_encode(QrCode::encoding('UTF-8')->size(100)->format("svg")->generate($user->code_user."-".$user->nom_user."-". $user->prenom_user));
                                    ?>
                                    <div style="margin-top: 5px; margin-right: 3px; width: 45px; height: 45px;">
                                        <img src="data:image/png;base64,{{ $qrcode }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                    <div class="foot">
                        <div class="left">
                            <p  style="margin-top: -30px;">
                                <span class="item-title" style="font-size: .55em; text-align: left;">Année Académique <br> <i>Académic Year</i></span><br>
                                <span class="item-value" style="font-size: .6em;"> 2024/2025</span>
                            </p>
                        </div>
                        <div class="right">
                            <p style="width: 100%; text-align: right; font-size: .6em;">
                                <span style="margin-top: -40px; display: block; margin-left: 15px;">Le Directeur / The Director</span>
                                <img style="width: 95px; height: 44px; margin-left: -60px;" src="{{public_path()."/sige_app/backend/img/avatars/signature_director.jpg"}}">
                            </p>
                        </div>
                    </div>
                </div>
            @endif
    @endforeach
    </div>
    <?php $nb_recto = 10-($nb_carte%10); $c = 0;?>
   @for($i = 0; $i < $nb_recto; $i++)
   <?php $c++;?>
        <div class="carte verso" @if ($c%2 == 0)
        style="margin-right: 0px !important; margin-left:0px !important;"
    @endif>
            <!--<div class="up">
                <p class="title-up">Contacts de l'étudiant / Student's Contacts </p>
                <span class="item"> Téléphone / Phone</span>
                <span class="value">{{$user->first_phone_user}}</span><br>
                <span class="item"> Mail </span>
                <span class="value">{{$user->email_user}}</span>
            </div>
            <div class="up">
                <p class="title-up">Contacts de l'Ecole /School's Contacts</p>
                <span class="item"> Téléphone /Phone</span>
                <span class="value">(+237) 222 482 412 </span><br>
                <span class="item"> Mail</span>
                <span class="value">estlc@estlc-ueb.cm</span><br>
                <span class="value">B.P 22 AMBAM</span>
            </div>-->
        </div>
   @endfor
   <?php $cpt_1 = 0;?>
   @for($i = 0; $i < count($cartes)-1; $i++)
        {{-- <?php $user = $cartes[$i-1];?> --}}
        @if($i%2 != 0)
            <?php $user = $cartes[$i-1];?>
        @else
            <?php $user = $cartes[$i+1];?>
        @endif
        @if($user->photo_user != null)
        <?php $cpt++;?>
            <div class="carte verso" @if ($cpt%2 == 0)
                style="margin-right: 0px !important; margin-left:0px !important;"
            @endif>
                <div class="up">
                    <p class="title-up">Contacts de l'étudiant / Student's Contacts </p>
                    <span class="item"> Téléphone / Phone</span>
                    <span class="value">{{$user->first_phone_user}}</span><br>
                    <span class="item"> Mail </span>
                    <span class="value" style="margin-left: -50px;">{{$user->email_user}}</span>
                </div>
                <div class="up">
                    <p class="title-up">Contacts de l'Ecole /School's Contacts</p>
                    <span class="item"> Téléphone /Phone</span>
                    <span class="value">(+237) 222 482 412 </span><br>
                    <span class="item"> Mail</span>
                    <span  class="value" style="margin-left: -40px; width: 60%; !important; margin-top: 15px;">estlc@estlc.unv-ebolowa.cm</span><br>
                    <span class="value">B.P 22 AMBAM</span>
                </div>
            </div>
        @endif
@endfor
</body>
</html>
