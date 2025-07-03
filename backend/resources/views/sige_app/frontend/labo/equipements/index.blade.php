@extends('sige_app.frontend.template.frontend')

@section('js')
    <script>
        function confirmDelete(form) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?')) {
                form.submit();
            }
        }
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Gestion des Équipements</h4>
                            <a href="{{ route('labo.equipements.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus"></i> Nouvel Équipement
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Filtres -->
                        <form method="GET" action="{{ route('labo.equipements.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="laboratoire" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tous les laboratoires</option>
                                        @foreach ($laboratoires as $lab)
                                            <option value="{{ $lab->code_lab }}"
                                                {{ request('laboratoire') == $lab->code_lab ? 'selected' : '' }}>
                                                {{ $lab->label_labo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="etat" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tous les états</option>
                                        <option value="disponible" {{ request('etat') == 'disponible' ? 'selected' : '' }}>
                                            Disponible</option>
                                        <option value="en_maintenance"
                                            {{ request('etat') == 'en_maintenance' ? 'selected' : '' }}>En maintenance
                                        </option>
                                        <option value="hors_service"
                                            {{ request('etat') == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('labo.equipements.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-sync"></i> Réinitialiser
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Référence</th>
                                        <th>Laboratoire</th>
                                        <th>État</th>
                                        <th>Localisation</th>
                                        <th>Valeur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equipements as $equipement)
                                        <tr>
                                            <td>{{ $equipement->code_equip }}</td>
                                            <td>{{ $equipement->nom_equip }}</td>
                                            <td>{{ $equipement->ref_equip }}</td>
                                            <td>{{ $equipement->laboratoire->sigle }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $equipement->etat == 'disponible'
                                                        ? 'success'
                                                        : ($equipement->etat == 'en_maintenance'
                                                            ? 'warning'
                                                            : 'danger') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $equipement->etat)) }}
                                                </span>
                                            </td>
                                            <td>{{ $equipement->localisation }}</td>
                                            <td>{{ number_format($equipement->valeur, 0, ',', ' ') }} FCFA</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('labo.equipements.show', $equipement->code_equip) }}"
                                                        class="btn btn-sm btn-info" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('labo.equipements.edit', $equipement->code_equip) }}"
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if ($equipement->etat == 'disponible')
                                                        <a href="{{ route('labo.equipements.reserver', $equipement->code_equip) }}"
                                                            class="btn btn-sm btn-primary" title="Réserver">
                                                            <i class="fas fa-calendar-plus"></i>
                                                        </a>
                                                    @endif
                                                    <form
                                                        action="{{ route('labo.equipements.destroy', $equipement->code_equip) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete(this.form)" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun équipement trouvé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $equipements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
