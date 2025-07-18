@extends('laboratoires.public.layout')

@section('content')
@php
    $userId = session('user_id');
    $userType = session('user_type');
    $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', session('laboratoire_code'))
        ->where('statut', 'actif')
        ->where(function ($q) use ($userId, $userType) {
            if ($userType === 'externe') {
                $q->where('id_user_externe', $userId);
            } else {
                $q->where('id_pers_lab', $userId);
            }
        })
        ->with('roleLabo')
        ->first();
    $userRole = $affectation && $affectation->roleLabo ? strtolower($affectation->roleLabo->lib_rl) : null;
@endphp
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-cabinet'></i> Gestion des Équipements - {{ $laboratoire->label_labo }}</h2>
        <div>
            @if($userRole === 'admin' || $userRole === 'chef_projet')
                <a href="{{ route('laboratoires.admin.equipements.create', $laboratoire->code_lab) }}" class="btn btn-success">
                    <i class='bx bx-plus'></i> Nouvel Équipement
                </a>
            @endif
            <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour au dashboard
            </a>
        </div>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ $stats['total'] }}</h4>
                    <p class="text-muted mb-0">Total Équipements</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-success">{{ $stats['disponible'] }}</h4>
                    <p class="text-muted mb-0">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-warning">{{ $stats['maintenance'] }}</h4>
                    <p class="text-muted mb-0">En Maintenance</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-danger">{{ $stats['hors_service'] }}</h4>
                    <p class="text-muted mb-0">Hors Service</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Liste des Équipements</h4>

            <!-- Filtres -->
            <form method="GET" action="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="etat">
                        <option value="">Tous les états</option>
                        <option value="disponible" {{ $etat === 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="en maintenance" {{ $etat === 'en maintenance' ? 'selected' : '' }}>En maintenance</option>
                        <option value="hors service" {{ $etat === 'hors service' ? 'selected' : '' }}>Hors service</option>
                        <option value="réservé" {{ $etat === 'réservé' ? 'selected' : '' }}>Réservé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="localisation" placeholder="Localisation..." value="{{ $localisation }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bx bx-search"></i> Filtrer
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}" class="btn btn-outline-secondary w-100">
                        <i class="bx bx-refresh"></i> Réinitialiser
                    </a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-centered table-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Référence</th>
                            <th>État</th>
                            <th>Localisation</th>
                            <th>Valeur</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipements as $equipement)
                        <tr>
                            <td>
                                @if($equipement->image_path)
                                    <img src="{{ asset('storage/' . $equipement->image_path) }}" alt="Image de l'équipement" style="max-height: 60px; max-width: 80px;" class="img-thumbnail">
                                @else
                                    <i class='bx bx-cog' style="font-size: 2rem; color: var(--primary-color);"></i>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $equipement->code_equip }}</span>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $equipement->nom_equip }}</h6>
                                    @if($equipement->desc_equip)
                                        <small class="text-muted">{{ Str::limit($equipement->desc_equip, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($equipement->ref_equip)
                                    <span class="text-muted">{{ $equipement->ref_equip }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $equipement->etat_badge }}">
                                    {{ $equipement->etat_label }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $equipement->localisation ?: '-' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $equipement->valeur ? number_format($equipement->valeur, 0, ',', ' ') . ' FCFA' : '-' }}</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}">
                                            <i class="uil uil-eye me-2"></i>Voir
                                        </a>
                                        @if($userRole === 'admin' || $userRole === 'chef_projet')
                                            <a class="dropdown-item" href="{{ route('laboratoires.admin.equipements.edit', [$laboratoire->code_lab, $equipement->code_equip]) }}">
                                                <i class="uil uil-edit me-2"></i>Modifier
                                            </a>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('laboratoires.admin.equipements.entretiens', [$laboratoire->code_lab, $equipement->code_equip]) }}">
                                            <i class="uil uil-wrench me-2"></i>Entretiens
                                        </a>
                                        <a class="dropdown-item" href="{{ route('laboratoires.admin.equipements.reservations', [$laboratoire->code_lab, $equipement->code_equip]) }}">
                                            <i class="uil uil-calendar-alt me-2"></i>Réservations
                                        </a>
                                        @if($userRole === 'admin')
                                            <div class="dropdown-divider"></div>
                                            <form id="delete-form-{{ $equipement->code_equip }}" action="{{ route('laboratoires.admin.equipements.destroy', [$laboratoire->code_lab, $equipement->code_equip]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger btn-delete-equipement" data-code="{{ $equipement->code_equip }}">
                                                    <i class="uil uil-trash-alt me-2"></i>Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bx bx-cabinet font-size-48"></i>
                                    <p class="mt-2">Aucun équipement trouvé</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $equipements->appends(request()->query())->links() }}
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
