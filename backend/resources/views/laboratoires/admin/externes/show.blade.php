@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-user'></i> Détail de l'utilisateur externe</h2>
        <a href="{{ route('laboratoires.admin.externes', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour à la liste
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class='bx bx-user-circle'></i> Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom :</strong> {{ $externe->nom_user_ext }}</p>
                            <p><strong>Prénom :</strong> {{ $externe->prenom_user_ext }}</p>
                            <p><strong>Email :</strong> {{ $externe->email_user_ext }}</p>
                            <p><strong>Téléphone :</strong> {{ $externe->tel_user_ext }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Statut :</strong>
                                <span class="badge bg-{{ $externe->statut === 'actif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($externe->statut) }}
                                </span>
                            </p>
                            <p><strong>Date début :</strong> {{ $externe->date_debut ? $externe->date_debut->format('d/m/Y') : 'N/A' }}</p>
                            <p><strong>Date fin :</strong> {{ $externe->date_fin ? $externe->date_fin->format('d/m/Y') : 'N/A' }}</p>
                            <p><strong>Laboratoire :</strong> {{ $laboratoire->label_labo }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($affectation)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class='bx bx-cog'></i> Affectation au laboratoire</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Rôle :</strong> {{ $affectation->roleLabo->lib_rl ?? 'N/A' }}</p>
                        <p><strong>Date d'affectation :</strong> {{ $affectation->date_affectation ? $affectation->date_affectation->format('d/m/Y') : 'N/A' }}</p>
                        <p><strong>Date fin affectation :</strong> {{ $affectation->date_fin_affectation ? $affectation->date_fin_affectation->format('d/m/Y') : 'N/A' }}</p>
                        <p><strong>Statut affectation :</strong>
                            <span class="badge bg-{{ $affectation->statut === 'actif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($affectation->statut) }}
                            </span>
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class='bx bx-cog'></i> Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('laboratoires.admin.externes.edit', [$laboratoire->code_lab, $externe->id_user_ext]) }}" class="btn btn-primary">
                            <i class='bx bx-edit'></i> Modifier
                        </a>

                        <form method="POST" action="{{ route('laboratoires.admin.externes.reset-password', [$laboratoire->code_lab, $externe->id_user_ext]) }}"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ? Un email sera envoyé avec le nouveau mot de passe.')">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class='bx bx-key'></i> Réinitialiser le mot de passe
                            </button>
                        </form>

                        <form method="POST" action="{{ route('laboratoires.admin.externes.destroy', [$laboratoire->code_lab, $externe->id_user_ext]) }}"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur externe ?')">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class='bx bx-trash'></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
