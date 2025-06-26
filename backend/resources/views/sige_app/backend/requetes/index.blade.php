@extends("sige_app.frontend.template.frontend")

@section("js")
<script>
    let confirmDeleteModal;
    let currentDeleteForm = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation du modal de suppression
        confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

        // Fermeture du modal principal
        document.getElementById('closeModalBtn').addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.classList.remove('show', 'd-block');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Gestion des formulaires de suppression de requêtes
        document.querySelectorAll('.delete-requete-form').forEach(form => {
            const deleteBtn = form.querySelector('button[type="submit"]');
            
            // Empêcher la soumission par défaut
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                return false;
            });

            // Gérer le clic sur le bouton
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const row = this.closest('tr');
                const codeRequete = row.querySelector('td:first-child').textContent.trim();
                const titreRequete = row.querySelector('td:nth-child(2)').textContent.trim();
                
                // Stocker la référence du formulaire
                currentDeleteForm = form;
                
                // Mettre à jour le contenu du modal
                document.getElementById('requeteCodeToDelete').textContent = codeRequete;
                document.getElementById('requestTitleToDelete').textContent = titreRequete;
                
                // Afficher le modal
                confirmDeleteModal.show();
            });
        });

        // Confirmation de la suppression
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentDeleteForm) {
                // Désactiver le bouton pour éviter les doubles clics
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Suppression...';
                
                confirmDeleteModal.hide();
                
                // Créer un nouveau formulaire temporaire
                const tempForm = document.createElement('form');
                tempForm.method = 'POST';
                tempForm.action = currentDeleteForm.action;
                tempForm.style.display = 'none';
                
                // Copier les inputs CSRF et METHOD
                const csrfToken = currentDeleteForm.querySelector('input[name="_token"]').value;
                const methodInput = currentDeleteForm.querySelector('input[name="_method"]').value;
                
                tempForm.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="${methodInput}">
                `;
                
                document.body.appendChild(tempForm);
                tempForm.submit();
            }
        });

        // Réinitialiser le bouton quand le modal se ferme
        document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function() {
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Supprimer la requête';
            currentDeleteForm = null;
        });
    });
</script>
@endsection

@section('content')
<div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-danger shadow">
            <div class="modal-header bg-danger p-2 d-flex justify-content-between align-items-center" style="color: white">
                <h5 class="modal-title mb-0" style="color: white">
                    <i class="fas fa-list me-2"></i>Mes Requêtes
                </h5>
                <a href="{{ route('requetes.create') }}" 
                   class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i>Nouvelle Requête
                </a>
            </div>
            <div class="modal-body">
                <!-- Filtres -->
                <form method="GET" action="{{ route('requetes.index') }}" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">
                            <i class="fas fa-flag me-1"></i>Statut
                        </label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="en attente" {{ request('status') == 'en attente' ? 'selected' : '' }}>En attente</option>
                            <option value="en cours" {{ request('status') == 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="traité" {{ request('status') == 'traité' ? 'selected' : '' }}>Traité</option>
                            <option value="rejeté" {{ request('status') == 'rejeté' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">
                            <i class="fas fa-tags me-1"></i>Catégorie
                        </label>
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
                        <label for="date_from" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>Date de début
                        </label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>Date de fin
                        </label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                        <a href="{{ route('requetes.index') }}" class="btn btn-light">
                            <i class="fas fa-undo me-1"></i>Réinitialiser
                        </a>
                    </div>
                </form>

                <!-- Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Liste des requêtes -->
                @if($requetes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-barcode me-1"></i>Code</th>
                                    <th><i class="fas fa-heading me-1"></i>Titre</th>
                                    <th><i class="fas fa-tags me-1"></i>Catégorie</th>
                                    <th><i class="fas fa-building me-1"></i>Bureau</th>
                                    <th><i class="fas fa-flag me-1"></i>Statut</th>
                                    <th><i class="fas fa-calendar me-1"></i>Date</th>
                                    <th><i class="fas fa-cogs me-1"></i>Actions</th>
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
                                        <td>{{ $requete->date_sousmis->format('d/m/Y H:i') }}</td>
                                        <td style="min-width: 180px; white-space: nowrap;">
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ route('requetes.show', $requete->code_requete) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Parcourir
                                                </a>
                                                @if($requete->status === 'en attente')
                                                    <a href="{{ route('requetes.edit', $requete->code_requete) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit me-1"></i>Modifier
                                                    </a>
                                                    <form action="{{ route('requetes.destroy', $requete->code_requete) }}" method="POST" class="d-inline delete-requete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash me-1"></i>Supprimer
                                                        </button>
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
                        <i class="fas fa-file-alt text-muted mb-3" style="font-size: 3rem;"></i>
                        <h3 class="h5">Aucune requête</h3>
                        <p class="text-muted">Commencez par créer votre première requête.</p>
                        <a href="{{ route('requetes.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Nouvelle Requête
                        </a>
                    </div>
                @endif
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" id="closeModalBtn">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression de requête -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fas fa-trash me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Êtes-vous sûr de vouloir supprimer cette requête ?</h6>
                <div class="alert alert-light border">
                    <div class="mb-2">
                        <strong>Code:</strong> <span id="requeteCodeToDelete"></span>
                    </div>
                    <div>
                        <strong>Titre:</strong> <span id="requestTitleToDelete"></span>
                    </div>
                </div>
                <p class="text-muted mb-0">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Cette action est irréversible. La requête et tous ses fichiers seront définitivement supprimés.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Supprimer la requête
                </button>
            </div>
        </div>
    </div>
</div>
@endsection