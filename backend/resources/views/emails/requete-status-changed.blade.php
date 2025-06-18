<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mise à jour de votre requête</title>
</head>
<body>
    <p>Bonjour,</p>
    <p>Le statut de votre requête <strong>{{ $requete->code_requete }}</strong> a été mis à jour.</p>
    <p>Ancien statut : <strong>{{ ucfirst($oldStatus) }}</strong></p>
    <p>Nouveau statut : <strong>{{ ucfirst($newStatus) }}</strong></p>
    <p>Vous pouvez consulter les détails de votre requête en vous connectant à votre compte.</p>
    <p>Merci,</p>
    <p>L'équipe de gestion des requêtes</p>
</body>
</html>
