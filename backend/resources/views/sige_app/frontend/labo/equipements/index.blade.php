@extends('sige_app.frontend.template.frontend')

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var confirmDeleteEquipementModal = document.getElementById('confirmDeleteEquipementModal');
            var equipementCodeSpan = document.getElementById('equipementCodeToDelete');
            var confirmDeleteEquipementBtn = document.getElementById('confirmDeleteEquipementBtn');
            var formToSubmit = null;

            // Attach click event to all delete buttons
            document.querySelectorAll('.btn-delete-equipement').forEach(function(button) {
                button.addEventListener('click', function() {
                    var code = this.getAttribute('data-code');
                    equipementCodeSpan.textContent = code;
                    formToSubmit = document.getElementById('delete-form-' + code);
                    var modal = new bootstrap.Modal(confirmDeleteEquipementModal);
                    modal.show();
                });
            });

            // Confirm delete button submits the form
            confirmDeleteEquipementBtn.addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });
        });
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
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equipements as $equipement)
                                        <tr>
                                            <td>{{ $equipement->code_equip }}</td>
                                            <td>{{ $equipement->nom_equip }}</td>
                                            <td>{{ $equipement->ref_equip }}</td>
                                            <td>{{ $equipement->laboratoire->code_lab }}</td>
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
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('labo.equipements.show', $equipement->code_equip) }}"
                                                    class="btn btn-outline-info" title="Voir">
                                                    <i class="fas fa-eye fs-5"></i>
                                                </a>
                                                <a href="{{ route('labo.equipements.edit', $equipement->code_equip) }}"
                                                    class="btn btn-outline-warning" title="Modifier">
                                                    <i class="fas fa-edit fs-5"></i>
                                                </a>
                                                @if ($equipement->etat == 'disponible')
                                                    <a href="{{ route('labo.equipements.reserver', $equipement->code_equip) }}"
                                                        class="btn btn-outline-primary" title="Réserver">
                                                        <i class="fas fa-calendar-plus fs-5"></i>
                                                    </a>
                                                @endif
                                                <form
                                                    id="delete-form-{{ $equipement->code_equip }}"
                                                    action="{{ route('labo.equipements.destroy', $equipement->code_equip) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger btn-delete-equipement"
                                                        data-code="{{ $equipement->code_equip }}" title="Supprimer">
                                                        <i class="fas fa-trash fs-5"></i>
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

    <!-- Modal de confirmation de suppression d'équipement -->
    <div class="modal fade" id="confirmDeleteEquipementModal" tabindex="-1" aria-labelledby="confirmDeleteEquipementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteEquipementModalLabel">
                        <i class="fas fa-trash me-2"></i>Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-3">Êtes-vous sûr de vouloir supprimer cet équipement ?</h6>
                    <div class="alert alert-light border">
                        <div class="mb-2">
                            <strong>Code équipement:</strong> <span id="equipementCodeToDelete"></span>
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Cette action est irréversible. Toutes les données associées à cet équipement seront définitivement supprimées.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteEquipementBtn">
                        <i class="fas fa-trash me-1"></i>Supprimer l'équipement
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
