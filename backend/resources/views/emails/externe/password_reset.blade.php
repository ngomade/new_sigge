<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .credentials {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 0.9em;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réinitialisation de mot de passe</h1>
    </div>

    <div class="content">
        <p>Bonjour {{ $userExterne->prenom_user_ext }} {{ $userExterne->nom_user_ext }},</p>

        <p>Votre mot de passe pour le laboratoire <strong>{{ $laboratoire->label_labo }}</strong> a été réinitialisé par l'administrateur.</p>

        <div class="credentials">
            <h3>Vos nouveaux identifiants de connexion :</h3>
            <p><strong>Email :</strong> {{ $userExterne->email_user_ext }}</p>
            <p><strong>Nouveau mot de passe :</strong> <code>{{ $newPassword }}</code></p>
        </div>

        <div class="warning">
            <strong>⚠️ Important :</strong> Pour des raisons de sécurité, nous vous recommandons fortement de changer ce mot de passe lors de votre prochaine connexion.
        </div>

        <p>Vous pouvez maintenant vous connecter à la plateforme du laboratoire avec vos nouveaux identifiants.</p>

        <p>Si vous n'avez pas demandé cette réinitialisation, veuillez contacter immédiatement l'administrateur du laboratoire.</p>

        <p>Cordialement,<br>
        L'équipe {{ $laboratoire->label_labo }}</p>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
        <p>© {{ date('Y') }} {{ $laboratoire->label_labo }} - Tous droits réservés</p>
    </div>
</body>
</html>
