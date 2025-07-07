@extends($layout ?? 'sige_app.backend.template.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Détails du rôle : {{ $role->lib_rl }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations générales</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID :</strong></td>
                                    <td>{{ $role->id_rl }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Libellé :</strong></td>
                                    <td><span class="badge bg-primary">{{ $role->lib_rl }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Date de création :</strong></td>
                                    <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dernière modification :</strong></td>
                                    <td>{{ $role->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Statistiques</h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h3>{{ $role->affectations ? $role->affectations->count() : 0 }}</h3>
                                            <p class="mb-0">Affectations</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h3>{{ $role->affectations ? $role->affectations->where('statut', 'actif')->count() : 0 }}</h3>
                                            <p class="mb-0">Actuellement actives</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>Personnes ayant ce rôle</h6>
                            <a href="{{ route('labo.roles.edit', $role->id_rl) }}" class="btn btn-warning btn-sm">
                                <i class="bx bx-edit"></i> Modifier le rôle
                            </a>
                        </div>

                        @if($role->affectations && $role->affectations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Laboratoire</th>
                                            <th>ID Personne</th>
                                            <th>Type</th>
                                            <th>Date d'affectation</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($role->affectations as $affectation)
                                            <tr>
                                                <td>
                                                    <strong>{{ $affectation->laboratoire->label_labo }}</strong>
                                                    <br><small class="text-muted">{{ $affectation->laboratoire->code_lab }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $affectation->persLab->id_pers_lab }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $affectation->persLab->type_pers_lab }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($affectation->date_affectation)->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $affectation->statut == 'actif' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($affectation->statut) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bx bx-info-circle fs-1"></i>
                                <p class="mt-2">Aucune personne n'a encore ce rôle</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('labo.roles.index') }}" class="btn btn-secondary">
                            <i class="bx bx-list-ul"></i> Liste des rôles
                        </a>
                        <a href="{{ route('labo.roles.edit', $role->id_rl) }}" class="btn btn-warning">
                            <i class="bx bx-edit"></i> Modifier ce rôle
                        </a>
                        @if($role->affectations && $role->affectations->count() == 0)
                            <form action="{{ route('labo.roles.destroy', $role->id_rl) }}" method="POST" class="d-grid">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">
                                    <i class="bx bx-trash"></i> Supprimer ce rôle
                                </button>
                            </form>
                        @else
                            <button class="btn btn-danger" disabled title="Ce rôle ne peut pas être supprimé car il est attribué à des personnes">
                                <i class="bx bx-trash"></i> Supprimer ce rôle
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
