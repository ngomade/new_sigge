@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-user'></i> Détail de l'utilisateur externe</h2>
        <a href="{{ route('laboratoires.admin.externes', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour à la liste
        </a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $externe->prenom_user_ext }} {{ $externe->nom_user_ext }}</h4>
            <p><strong>Email :</strong> {{ $externe->email_user_ext }}</p>
            <p><strong>Téléphone :</strong> {{ $externe->tel_user_ext }}</p>
            <p><strong>Statut :</strong> {{ $externe->statut }}</p>
            <p><strong>Date début :</strong> {{ $externe->date_debut }}</p>
            <p><strong>Date fin :</strong> {{ $externe->date_fin ?? 'N/A' }}</p>
            <p><strong>Laboratoire :</strong> {{ $laboratoire->label_labo }}</p>
            @if($affectation)
                <p><strong>Rôle :</strong> {{ $affectation->roleLabo->label_role ?? 'N/A' }}</p>
                <p><strong>Date d'affectation :</strong> {{ $affectation->date_affectation }}</p>
                <p><strong>Date fin affectation :</strong> {{ $affectation->date_fin_affectation ?? 'N/A' }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
