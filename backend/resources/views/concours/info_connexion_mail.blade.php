<!DOCTYPE html>
<html>
<head>
    <title>Informations de Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .header img {
            max-width: 100px;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            font-size: 24px;
            color: #333333;
        }
        .content p {
            font-size: 16px;
            color: #666666;
            line-height: 1.5;
        }
        .content ul {
            list-style-type: none;
            padding: 0;
        }
        .content ul li {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f4f4f4;
            color: #666666;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>ESTLC Mailing</h1>
    </div>
    <div class="content">
        <h1>Bonjour {{ $compte->ca_prenom ?? $compte->prenom_pers }} {{ $compte->ca_nom ?? $compte->nom_pers }},</h1>
        <p>Voici vos informations de connexion :</p>
        <ul>
            <li><strong>Email :</strong> {{ $compte->ca_email ?? $compte->email_pers }}</li>
            <li><strong>Numéro de reçu :</strong> {{ $compte->ca_num_recu ?? $compte->login_pers }}</li>
        </ul>
        <p>Merci de garder ces informations en sécurité.</p>
    </div>
    <div class="footer">
        <p>Cordialement,</p>
        <p>L'équipe ESTLC</p>
    </div>
</div>
</body>
</html>
