<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nouvelle réponse à votre requête</title>
</head>
<body>
    <p>Bonjour,</p>
    <p>Une nouvelle réponse a été ajoutée à votre requête <strong>{{ $requete->code_requete }}</strong> :</p>
    <blockquote>
        {{ $reponse->text_reponse ?? $reponse }}
    </blockquote>
    <p>Vous pouvez consulter votre requête pour plus de détails.</p>
    <p>Cordialement,<br>L'équipe de support</p>
</body>
</html>
