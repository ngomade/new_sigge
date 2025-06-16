<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link href="{{asset('concours/frontend/pdf.css')}}" rel="stylesheet">
    <title> Impression-Liste</title>
</head>

<body style="margin-bottom: 20px;">
    <header style="text-align: center; margin-bottom: 2px;">
        <img src="{{asset('concours/frontend/img/entete_fiche_land.png')}}" alt="entete de la fiche" style="width: 98%; height: 25%; margin: auto;">
    </header>
    <div>
        <h1 style="border-bottom: 1px solid black; text-align: center; border-top: 1px solid black;">
            LISTE DES CANDIDATS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            FILIERE: <span style="color: blue;">Toutes</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            CENTRE D'EXAMEN: <span style="color: blue;">Tous les centres</span>
        </h1>
    </div>
    <div style=" width: 99%; margin: auto;">
        <table class="table table-bordered" >
            <thead>
                <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Code</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Date Naissance</th>
                    <th scope="col">Lieu Naissance</th>
                    <th scope="col">Sexe</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Nationalité</th>
                    <th scope="col">Diplôme</th>
                    <th scope="col">Année Diplôme</th>
                    <th scope="col">Mention</th>
                    <th scope="col">Langue</th>
                    <th scope="col">Région Origine</th>
                    <th scope="col">Départ Origine</th>
                    <th scope="col">N° CNI</th>
                    <th scope="col">Filière</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($candidats as $ca)
                    <tr>
                        <td style="width: 30px;"> {{ $loop->index + 1 }} </td>
                        <td style="width: 50px;"> {{ $ca->ca_code }} </td>
                        <td style="width: 210px;"> {{ $ca->ca_nom }} </td>
                        <td style="width: 120px;"> {{ $ca->ca_prenom }} </td>
                        <td style="width: 80px;"> {{ \Str::substr($ca->ca_date_naiss, 0, 10) }} </td>
                        <td style="width: 110px;"> {{ $ca->ca_lieu_naiss }} </td>
                        <td> {{ \Str::substr($ca->ca_sexe, 0,1)}} </td>
                        <td> {{ \Str::substr($ca->ca_telephone,0,13) }} </td>
                        <td> {{ $ca->ca_nationalite }} </td>
                        <td style="width: 80px;"> {{ \Str::substr($ca->ca_diplome_admission, 0, 4) }} {{ $ca->ca_serie_diplome }} </td>
                        <td> {{ $ca->ca_annee_diplome }} </td>
                        <td style="width: 80px;"> {{ $ca->ca_mention_diplome }} </td>
                        <td> {{ \Str::substr($ca->ca_premirere_lang, 0, 3) }} </td>
                        <td > {{ $ca->ca_region_origine }} </td>
                        <td style="width: 110px;"> {{ $ca->ca_depart_origine }} </td>
                        <td> {{ $ca->ca_num_cni }} </td>
                        <td> {{ $ca->cursus_code }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <footer style="margin-top: 10px;">
        <div class="imp">Imprimée le <?php echo date('d/m/Y'); ?> </div>
        <div class="imp" style="text-align: right;">Page 1/3
        </div>
    </footer>
</body>

</html>
