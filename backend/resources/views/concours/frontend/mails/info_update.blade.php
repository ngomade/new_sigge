<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <title> {{$mail['mail_objet']}}</title>
</head>
<body>
    <div style="width: 80%; margin:auto;">
        <div class="card-header h6 bg-success" style="color: white;"> {{$mail['mail_objet']}} </div>
        <hr>
        <div class="card-body">
            <p class="text-danger" style="font-size: 1.4em; text-align: center; font-family: Arial Narrow;"> {{$mail['mail_content']}} </p>
            <p  style="font-size: 1.3em; text-align: center; font-family: Arial Narrow;">  <a href="http://estlc-ueb.cm" target="_blank" rel="noopener noreferrer"> http://estlc-ueb.cm</a></p>
            <h2 class="mb-2 mt-3">Code: <span  style="font-family: Arial Narrow; color:red; font-size: 1.1em;" > {{$candidat->ca_code}}</span></h2>
            <h2 class="mb-4 mt-3">Password: <span class="text-primary h4" style="font-family: Arial Narrow;color:red; font-size: 1.1em;" > {{$candidat->ca_pwd}}</span></h2>
        </div>
        <hr>
        <p style="text-align: right; font-style: italic;"> Envoyé par ESTLC Mailing</p>
    </div>
</body>
</html>

