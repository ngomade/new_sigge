@extends("sige_app.frontend.template.frontend")
@section("js")
<script>
    document.getElementById('closeModalBtn').addEventListener('click', function() {
        const modal = this.closest('.modal');
        modal.classList.remove('show', 'd-block');
    });
</script>

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

@endsection
@section('content')
<div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-danger shadow">
            <div class="modal-header bg-danger p-2 d-flex justify-content-between align-items-center" style="color: white">
                <h5 class="modal-title mb-0" style="color: white">Mes Requêtes</h5>
                <a href="{{ route('requetes.create') }}" 
                   class="btn btn-success btn-sm">
                    Nouvelle Requête
                </a>
            </div>
            <div class="modal-body">
                <!-- Filtres -->
                <form method="GET" action="{{ route('requetes.index') }}" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Statut</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="en attente" {{ request('status') == 'en attente' ? 'selected' : '' }}>En attente</option>
                            <option value="en cours" {{ request('status') == 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="traité" {{ request('status') == 'traité' ? 'selected' : '' }}>Traité</option>
                            <option value="rejeté" {{ request('status') == 'rejeté' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Catégorie</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->code_cat }}" {{ request('category') == $category->code_cat ? 'selected' : '' }}>
                                    {{ $category->label_cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Date de début</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Date de fin</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-secondary">Filtrer</button>
                        <a href="{{ route('requetes.index') }}" class="btn btn-light">Réinitialiser</a>
                    </div>
                </form>

                <!-- Messages -->
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

                <!-- Liste des requêtes -->
                @if($requetes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Titre</th>
                                    <th>Catégorie</th>
                                    <th>Bureau</th>
                                    <th>Statut</th>
                                    {{-- <th>Priorité</th> --}}
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requetes as $requete)
                                    <tr>
                                        <td>{{ $requete->code_requete }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($requete->titre_requete, 30) }}</td>
                                        <td>{{ $requete->category->label_cat ?? 'N/A' }}</td>
                                        <td>{{ $requete->bureau->label_bureau ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'en attente' => 'badge bg-warning text-dark',
                                                    'en cours' => 'badge bg-primary',
                                                    'traité' => 'badge bg-success',
                                                    'rejeté' => 'badge bg-danger'
                                                ];
                                            @endphp
                                            <span class="{{ $statusClasses[$requete->status] ?? 'badge bg-secondary' }}">
                                                {{ ucfirst($requete->status) }}
                                            </span>
                                        </td>
                                        {{-- <td>
                                            <span class="badge {{ $requete->priorite === 'urgent' ? 'bg-danger' : 'bg-secondary' }}">
                                                {{ ucfirst($requete->priorite) }}
                                            </span>
                                        </td> --}}
                                        <td>{{ $requete->date_sousmis->format('d/m/Y H:i') }}</td>
                                        <td style="min-width: 180px; white-space: nowrap;">
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ route('requetes.show', $requete->code_requete) }}" class="btn btn-sm btn-info">Parcourir</a>
                                                @if($requete->status === 'en attente')
                                                    <a href="{{ route('requetes.edit', $requete->code_requete) }}" class="btn btn-sm btn-primary">Modifier</a>
                                                    <form action="{{ route('requetes.destroy', $requete->code_requete) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette requête ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $requetes->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <svg class="mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="h5">Aucune requête</h3>
                        <p>Commencez par créer votre première requête.</p>
                        <a href="{{ route('requetes.create') }}" class="btn btn-success">Nouvelle Requête</a>
                    </div>
                @endif
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" id="closeModalBtn">Fermer</button>
            </div>
        </div>
    </div>
</div>


@endsection
