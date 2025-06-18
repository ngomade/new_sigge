<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de soumission de votre requête</title>
</head>
<body>
    <h1>Confirmation de soumission de votre requête</h1>
    <p>Bonjour,</p>
    <p>Votre requête a été soumise avec succès. Voici les détails :</p>
    <ul>
        <li><strong>Numéro de référence :</strong> {{ $requete->code_requete }}</li>
        <li><strong>Titre :</strong> {{ $requete->titre_requete }}</li>
        <li><strong>Date de soumission :</strong> {{ $requete->date_sousmis->format('d/m/Y H:i') }}</li>
        <li><strong>Description :</strong> {{ $requete->desc_requete }}</li>
        <li><strong>Bureau :</strong> {{ $requete->bureau->label_bureau ?? 'N/A' }}</li>
    </ul>
    <p>Nous vous remercions pour votre confiance.</p>
    <p>Cordialement,</p>
    <p>L'équipe de gestion des requêtes</p>
</body>
</html>
