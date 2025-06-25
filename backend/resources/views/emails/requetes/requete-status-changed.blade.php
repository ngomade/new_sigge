<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mise à jour de votre requête</title>
</head>
<body>
    @php
        $hour = \Carbon\Carbon::now()->hour;
        $greeting = ($hour >= 18 || $hour < 6) ? 'Bonsoir' : 'Bonjour';
    @endphp
    <p>{{ $greeting }},</p>
    <p>Le statut de votre requête <strong>{{ $requete->code_requete }}</strong> a été mis à jour.</p>
    {{-- <p>Ancien statut : <strong>{{ ucfirst($oldStatus) }}</strong></p>
    <p>Nouveau statut : <strong>{{ ucfirst($newStatus) }}</strong></p> --}}
    @if($oldStatus === 'en attente' && $newStatus === 'en cours')
        <p>Votre requête a été transférée au bureau <strong>{{ $requete->bureau->label_bureau ?? 'inconnu' }}</strong> et est maintenant en cours de traitement.</p>
    @elseif(($oldStatus === 'en cours' || $oldStatus === 'en attente') && $newStatus === 'traitée')
        <p>Votre requête a été traitée avec succès et est maintenant terminée.</p>
    @elseif($newStatus === 'rejetée')
        <p>Votre requête a été rejetée. Veuillez consulter les détails pour plus d'informations.</p>
    @else
        <p>Le statut de votre requête a été mis à jour.</p>
    @endif
    <p>Vous pouvez consulter les détails de votre requête en vous connectant à votre compte.</p>
    <p>Merci,</p>
    <p>L'équipe de gestion des requêtes</p>
</body>
</html>
