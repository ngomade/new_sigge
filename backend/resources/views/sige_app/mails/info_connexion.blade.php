<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <title> Informations perdues</title>
</head>
<body>
    <div style="width: 80%; margin:auto;">
        <div class="card-header h6 bg-success" style="color: white;">Informations utiles</div>
        <hr>
        <div class="card-body">
            <p class="text-danger" style="font-size: 1.4em; text-align: center; font-family: Arial Narrow;">Matricule et mot de passe à usage personnel et à ne divulguer à aucune tierce personne !!!</p>
            <h2 class="mb-2 mt-3">Matricule: <span  style="font-family: Arial Narrow; color:red; font-size: 1.1em;" > {{$user->code_user}}</span></h2>
            <h2 class="mb-4 mt-3">Nouveau Mot de Passe: <span class="text-primary h4" style="font-family: Arial Narrow;color:red; font-size: 1.1em;" > {{$pwd}}</span></h2>
        </div>
        <hr>
        <p style="text-align: right; font-style: italic;"> Envoyé par ESTLC Mailing</p>
    </div>
</body>
</html>
