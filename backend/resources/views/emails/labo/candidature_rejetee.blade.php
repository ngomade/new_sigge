<p>Bonjour {{ $candidature->prenom_user_ext }} {{ $candidature->nom_user_ext }},</p>

<p>Nous vous informons que votre candidature au laboratoire <strong>{{ $candidature->laboratoire->label_labo ?? '' }}</strong> a été <span style="color: red; font-weight: bold;">rejetée</span>.</p>

<p><strong>Motif du rejet :</strong></p>
<p style="color: #b02a37;">{{ $motif }}</p>

<p>Nous vous remercions pour l'intérêt porté à notre laboratoire et vous souhaitons bonne continuation.</p>

<p>Cordialement,<br>L'équipe du laboratoire</p>
