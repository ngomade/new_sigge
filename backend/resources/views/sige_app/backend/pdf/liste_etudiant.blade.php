<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link href="{{public_path()."/pdf/liste_etudiant.css"}}" rel="stylesheet">
    <title>Liste-{{$fil}}</title>
</head>

<body style="margin-bottom: 20px;">
    <header style="text-align: center; margin-bottom: 2px;">
        <img src="{{public_path()."/share/img/entete_fiche.png"}}" alt="entete de la fiche" style="width: 98%; height: 25%; margin: auto;">
    </header>
    <div>
        <h2 style="border-bottom: 1px solid black; text-align: center; border-top: 1px solid black;">
            LISTE DES ETUDIANTS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            FILIERE: <span style="color: rgb(74, 74, 210);">{{$fil}}</span>
        </h2>
    </div>
    <div style=" width: 99%; margin: auto;">
        <table class="table table-bordered" >
            <thead>
                <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Matricule</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Date Naissance</th>
                    <th scope="col">Lieu Naissance</th>
                    <th scope="col">N° CNI</th>
                    <th scope="col">Sexe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td style="width: 20px;"> {{ $loop->index + 1 }} </td>
                        <td style="width: 50px;"> {{ $user->code_user}} </td>
                        <td style="width: 150px; text-transform: uppercase;"> {{ $user->nom_user }} </td>
                        <td style="width: 140px;"> {{ $user->prenom_user }} </td>
                        <td style="width: 70px;"> {{ $user->date_naissance_user->format("d/m/Y") }} </td>
                        <td style="width: 110px;"> {{ $user->lieu_naissance_user }} </td>
                        <td style="width: 110px;"> {{ $user->numero_cni_user}} </td>
                        <td>
                                @if ($user->sexe_user == "MASCULIN")
                                    M
                            @else
                                    F
                            @endif
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <footer style="margin-top: 20px;">
        <div class="imp">Imprimée le <?php echo date('d/m/Y'); ?> </div>
    </footer>
</body>
</html>
