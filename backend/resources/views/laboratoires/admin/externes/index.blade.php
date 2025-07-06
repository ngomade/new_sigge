@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-user'></i> Gestion des utilisateurs externes - {{ $laboratoire->label_labo }}</h2>
        <div>
            <a href="{{ route('laboratoires.admin.externes.create', $laboratoire->code_lab) }}" class="btn btn-success">
                <i class='bx bx-user-plus'></i> Ajouter un externe
            </a>
            <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour au dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Recherche (nom, prénom, email)" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ $statut == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ $statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('laboratoires.admin.externes', $laboratoire->code_lab) }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des utilisateurs externes -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Utilisateurs externes ({{ $externes->total() }})</h5>
        </div>
        <div class="card-body">
            @if($externes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Statut</th>
                                <th>Période</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($externes as $externe)
                                <tr>
                                    <td>
                                        <strong>{{ $externe->nom_user_ext }} {{ $externe->prenom_user_ext }}</strong>
                                        @if($externe->motivation)
                                            <br><small class="text-muted">A une motivation</small>
                                        @endif
                                    </td>
                                    <td>{{ $externe->email_user_ext }}</td>
                                    <td>{{ $externe->tel_user_ext }}</td>
                                    <td>
                                        @if($externe->statut == 'actif')
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            Du {{ $externe->date_debut ? \Carbon\Carbon::parse($externe->date_debut)->format('d/m/Y') : 'N/A' }}
                                            @if($externe->date_fin)
                                                <br>Au {{ \Carbon\Carbon::parse($externe->date_fin)->format('d/m/Y') }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('laboratoires.admin.externes.show', [$laboratoire->code_lab, $externe->id_user_ext]) }}" class="btn btn-sm btn-info">
                                            <i class='bx bx-show'></i> Voir
                                        </a>
                                        <a href="{{ route('laboratoires.admin.externes.edit', [$laboratoire->code_lab, $externe->id_user_ext]) }}" class="btn btn-sm btn-primary">
                                            <i class='bx bx-edit'></i> Modifier
                                        </a>
<form method="POST" action="{{ route('laboratoires.admin.externes.destroy', [$laboratoire->code_lab, $externe->id_user_ext]) }}" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cet utilisateur externe ?')">
    @csrf
    <button type="submit" class="btn btn-sm btn-danger" onclick="console.log('Deleting externe with id:', '{{ $externe->id_user_ext }}')">
        <i class='bx bx-trash'></i> Supprimer
    </button>
</form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $externes->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class='bx bx-user-x' style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-2">Aucun utilisateur externe trouvé</p>
                    <a href="{{ route('laboratoires.admin.externes.create', $laboratoire->code_lab) }}" class="btn btn-primary">
                        <i class='bx bx-user-plus'></i> Ajouter le premier utilisateur externe
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
