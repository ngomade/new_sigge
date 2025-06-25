@extends("sige_app.backend.template.backend")
@section("js")
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 text-dark mb-1">Gestion des Requêtes</h1>
            <p class="text-muted mb-0">Administration et suivi des demandes</p>
        </div>
    </div>

    <!-- Messages de notification -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card container for filters and table -->
    <div class="card shadow-sm">
        <!-- Filter form -->
        <div class="card-header bg-light">
            <form method="GET" action="{{ route('admin.requetes.index') }}" class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <label for="status" class="form-label small text-muted">STATUT</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="en attente" {{ request('status') == 'en attente' ? 'selected' : '' }}>En attente</option>
                        <option value="en cours" {{ request('status') == 'en cours' ? 'selected' : '' }}>En cours</option>
                        <option value="traitée" {{ request('status') == 'traitée' ? 'selected' : '' }}>Traitée</option>
                        <option value="rejetée" {{ request('status') == 'rejetée' ? 'selected' : '' }}>Rejetée</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label for="category" class="form-label small text-muted">CATÉGORIE</label>
                    <select name="category" id="category" class="form-select form-select-sm">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->code_cat }}" {{ request('category') == $category->code_cat ? 'selected' : '' }}>
                                {{ $category->label_cat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label for="bureau" class="form-label small text-muted">BUREAU</label>
                    <select name="bureau" id="bureau" class="form-select form-select-sm">
                        <option value="">Tous les bureaux</option>
                        @foreach($bureaux as $bureau)
                            <option value="{{ $bureau->code_bureau }}" {{ request('bureau') == $bureau->code_bureau ? 'selected' : '' }}>
                                {{ $bureau->label_bureau }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label for="date_from" class="form-label small text-muted">DATE DE DÉBUT</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label for="date_to" class="form-label small text-muted">DATE DE FIN</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-6 col-sm-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-secondary btn-sm px-4">Filtrer</button>
                    <a href="{{ route('admin.requetes.index') }}" class="btn btn-outline-secondary btn-sm px-4">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Table header -->
        <div class="card-body p-0">
            <div class="bg-primary text-white text-center py-3">
                <h5 class="mb-0">Liste des Requêtes ({{ $requetes->total() }} résultats)</h5>
            </div>

            @if($requetes->count() > 0)
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-primary fw-bold">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'code_requete', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                       class="text-decoration-none text-primary">
                                        Code Requête
                                        @if(request('sort') === 'code_requete')
                                            <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-muted">Utilisateur</th>
                                <th class="text-muted">Catégorie</th>
                                <th class="text-muted">Bureau</th>
                                <th class="text-primary fw-bold">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                       class="text-decoration-none text-primary">
                                        Statut
                                        @if(request('sort') === 'status')
                                            <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-primary fw-bold">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_sousmis', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                       class="text-decoration-none text-primary">
                                        Date soumise
                                        @if(request('sort') === 'date_sousmis')
                                            <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-muted">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requetes as $requete)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $requete->code_requete }}</td>
                                    <td class="text-muted">{{ $requete->user->nom_user ?? 'N/A' }}</td>
                                    <td class="text-muted">{{ $requete->category->label_cat ?? 'N/A' }}</td>
                                    <td class="text-muted">{{ $requete->bureau->label_bureau ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'en attente' => ['class' => 'badge bg-warning text-dark', 'text' => 'En attente'],
                                                'en cours' => ['class' => 'badge bg-info text-white', 'text' => 'En cours'],
                                                'traitée' => ['class' => 'badge bg-success text-white', 'text' => 'Traitée'],
                                                'rejetée' => ['class' => 'badge bg-danger text-white', 'text' => 'Rejetée'],
                                            ];
                                            $config = $statusConfig[$requete->status] ?? ['class' => 'badge bg-secondary', 'text' => ucfirst($requete->status)];
                                        @endphp
                                        <span class="{{ $config['class'] }}">{{ $config['text'] }}</span>
                                    </td>
                                    <td class="text-muted">
                                        {{ $requete->date_sousmis ? $requete->date_sousmis->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.requetes.show', $requete->code_requete) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Voir détails
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Affichage de {{ $requetes->firstItem() }} à {{ $requetes->lastItem() }} sur {{ $requetes->total() }} résultats
                        </div>
                        <div>
                            {{ $requetes->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty state -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Aucune requête trouvée</h5>
                    <p class="text-muted">Aucune requête ne correspond aux critères de recherche.</p>
                    <a href="{{ route('admin.requetes.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-refresh me-1"></i>Voir toutes les requêtes
                    </a>
                </div>
            @endif
        </div>
    </div>

   
</div>


@endsection