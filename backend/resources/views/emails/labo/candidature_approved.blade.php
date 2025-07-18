<p>Bonjour {{ $candidature->prenom_user_ext }} {{ $candidature->nom_user_ext }},</p>

<p>Félicitations, votre candidature au laboratoire <strong>{{ $candidature->laboratoire->label_labo ?? '' }}</strong> a été <span style="color: green; font-weight: bold;">approuvée</span> !</p>

<p>Vous pouvez désormais accéder à l'espace membre du laboratoire avec les identifiants suivants :</p>
<ul>
    <li><strong>Email :</strong> {{ $candidature->email_user_ext }}</li>
    <li><strong>Mot de passe temporaire :</strong> {{ $tempPassword }}</li>
</ul>
<p>Nous vous recommandons de changer votre mot de passe dès votre première connexion.</p>

<p>Bienvenue dans l'équipe !<br>L'équipe du laboratoire</p>
