@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-wrench'></i> Gestion des Entretiens - {{ $equipement->nom_equip }}</h2>
        <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back"></i> Retour aux détails
        </a>
    </div>

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

    <div class="row">
        <!-- Informations de l'équipement -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $equipement->nom_equip }}</h5>
                    <p class="text-muted">Code: {{ $equipement->code_equip }}</p>

                    <div class="mb-3">
                        <span class="badge bg-{{ $equipement->etat_badge }} fs-6">
                            {{ $equipement->etat_label }}
                        </span>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $equipement->entretiens->count() }}</h4>
                            <p class="text-muted mb-0">Total entretiens</p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">{{ $equipement->entretiens->where('statut_entretien', 'En cours')->count() }}</h4>
                            <p class="text-muted mb-0">En cours</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nouvel entretien -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Programmer un entretien</h5>

                    <form action="{{ route('laboratoires.admin.equipements.entretien.store', [$laboratoire->code_lab, $equipement->code_equip]) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="type_entretien" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type_entretien') is-invalid @enderror" id="type_entretien" name="type_entretien" required>
                                <option value="">Sélectionner</option>
                                <option value="entretien" {{ old('type_entretien') === 'entretien' ? 'selected' : '' }}>Entretien</option>
                                <option value="reparation" {{ old('type_entretien') === 'reparation' ? 'selected' : '' }}>Réparation</option>
                            </select>
                            @error('type_entretien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="id_pers_lab" class="form-label">Responsable <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_pers_lab') is-invalid @enderror" id="id_pers_lab" name="id_pers_lab" required>
                                <option value="">Sélectionner un membre</option>
                                @foreach($personnel as $pers)
                                    <option value="{{ $pers->id }}" {{ old('id_pers_lab') == $pers->id ? 'selected' : '' }}>
                                        @if($pers->persLab)
                                            {{ $pers->persLab->nom_complet ?? 'Membre interne' }}
                                        @elseif($pers->userExterne)
                                            {{ $pers->userExterne->nom_user_ext }} {{ $pers->userExterne->prenom_user_ext }}
                                        @else
                                            Membre inconnu
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pers_lab')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="debut_entretien" class="form-label">Date de début <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('debut_entretien') is-invalid @enderror"
                                   id="debut_entretien" name="debut_entretien" value="{{ old('debut_entretien') }}" required>
                            @error('debut_entretien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fin_entretien" class="form-label">Date de fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fin_entretien') is-invalid @enderror"
                                   id="fin_entretien" name="fin_entretien" value="{{ old('fin_entretien') }}" required>
                            @error('fin_entretien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cout" class="form-label">Coût estimé (FCFA)</label>
                            <input type="number" class="form-control @error('cout') is-invalid @enderror"
                                   id="cout" name="cout" value="{{ old('cout') }}" placeholder="0" min="0" step="0.01">
                            @error('cout')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="desc_entretien" class="form-label">Description</label>
                            <textarea class="form-control @error('desc_entretien') is-invalid @enderror"
                                      id="desc_entretien" name="desc_entretien" rows="3"
                                      placeholder="Description de l'entretien...">{{ old('desc_entretien') }}</textarea>
                            @error('desc_entretien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-plus"></i> Programmer l'entretien
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des entretiens -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Historique des entretiens</h5>

                    @if($equipement->entretiens->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Responsable</th>
                                    <th>Période</th>
                                    <th>Statut</th>
                                    <th>Coût</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipement->entretiens->sortByDesc('created_at') as $entretien)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $entretien->type_badge }}">
                                            {{ $entretien->type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($entretien->personnel && $entretien->personnel->persLab)
                                            {{ $entretien->personnel->persLab->nom_complet ?? 'Membre interne' }}
                                        @elseif($entretien->personnel && $entretien->personnel->userExterne)
                                            {{ $entretien->personnel->userExterne->nom_user_ext }} {{ $entretien->personnel->userExterne->prenom_user_ext }}
                                        @else
                                            Non défini
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $entretien->debut_formatted }}</div>
                                        <small class="text-muted">au {{ $entretien->fin_formatted }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $entretien->statut_badge }}">
                                            {{ $entretien->statut_entretien }}
                                        </span>
                                        @if($entretien->isEnCours())
                                            <br><small class="text-muted">{{ $entretien->getJoursRestantsFormatted() }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $entretien->cout_formatted }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="text-muted dropdown-toggle font-size-16 p-2" href="#" role="button" data-bs-toggle="dropdown">
                                                <i class="uil uil-ellipsis-h"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editEntretienModal{{ $entretien->id }}">
                                                    <i class="uil uil-edit me-2"></i>Modifier
                                                </a>
                                                @if($entretien->isEnCours())
                                                <a class="dropdown-item text-success" href="#" onclick="changerStatut('{{ $entretien->id }}', 'Terminé')">
                                                    <i class="bx bx-check me-2"></i>Marquer comme terminé
                                                </a>
                                                <a class="dropdown-item text-info" href="#" onclick="changerStatut('{{ $entretien->id }}', 'En pause')">
                                                    <i class="bx bx-pause me-2"></i>Mettre en pause
                                                </a>
                                                @endif
                                                @if($entretien->isEnPause())
                                                <a class="dropdown-item text-warning" href="#" onclick="changerStatut('{{ $entretien->id }}', 'En cours')">
                                                    <i class="bx bx-play me-2"></i>Reprendre
                                                </a>
                                                @endif
                                                @if(!$entretien->isTermine() && !$entretien->isAnnule())
                                                <a class="dropdown-item text-danger" href="#" onclick="changerStatut('{{ $entretien->id }}', 'Annulé')">
                                                    <i class="bx bx-x me-2"></i>Annuler
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bx bx-wrench font-size-48 text-muted"></i>
                        <p class="text-muted mt-2">Aucun entretien enregistré pour cet équipement</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour modifier les entretiens -->
@foreach($equipement->entretiens as $entretien)
<div class="modal fade" id="editEntretienModal{{ $entretien->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'entretien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('laboratoires.admin.equipements.entretien.update', [$laboratoire->code_lab, $equipement->code_equip, $entretien->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statut_entretien{{ $entretien->id }}" class="form-label">Statut</label>
                        <select class="form-select" id="statut_entretien{{ $entretien->id }}" name="statut_entretien" required>
                            <option value="En cours" {{ $entretien->statut_entretien === 'En cours' ? 'selected' : '' }}>En cours</option>
                            <option value="Terminé" {{ $entretien->statut_entretien === 'Terminé' ? 'selected' : '' }}>Terminé</option>
                            <option value="En pause" {{ $entretien->statut_entretien === 'En pause' ? 'selected' : '' }}>En pause</option>
                            <option value="Annulé" {{ $entretien->statut_entretien === 'Annulé' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="debut_entretien{{ $entretien->id }}" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="debut_entretien{{ $entretien->id }}"
                               name="debut_entretien" value="{{ $entretien->debut_entretien ? $entretien->debut_entretien->format('Y-m-d') : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="fin_entretien{{ $entretien->id }}" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="fin_entretien{{ $entretien->id }}"
                               name="fin_entretien" value="{{ $entretien->fin_entretien ? $entretien->fin_entretien->format('Y-m-d') : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cout{{ $entretien->id }}" class="form-label">Coût (FCFA)</label>
                        <input type="number" class="form-control" id="cout{{ $entretien->id }}"
                               name="cout" value="{{ $entretien->cout }}" placeholder="0" min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="desc_entretien{{ $entretien->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="desc_entretien{{ $entretien->id }}"
                                  name="desc_entretien" rows="3">{{ $entretien->desc_entretien }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Formulaire pour changer le statut -->
<form id="changerStatutForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="statut_entretien" id="nouveauStatut">
    <!-- Garder seulement les champs qui doivent être modifiés -->
    <input type="hidden" name="keep_dates" value="true">
</form>

@endsection

@push('scripts')
<script>
function changerStatut(entretienId, nouveauStatut) {
    let message = '';
    switch(nouveauStatut) {
        case 'Terminé':
            message = 'Êtes-vous sûr de vouloir marquer cet entretien comme terminé ?';
            break;
        case 'En pause':
            message = 'Êtes-vous sûr de vouloir mettre cet entretien en pause ?';
            break;
        case 'En cours':
            message = 'Êtes-vous sûr de vouloir reprendre cet entretien ?';
            break;
        case 'Annulé':
            message = 'Êtes-vous sûr de vouloir annuler cet entretien ?';
            break;
        default:
            message = 'Êtes-vous sûr de vouloir changer le statut de cet entretien ?';
    }

    if (confirm(message)) {
        document.getElementById('nouveauStatut').value = nouveauStatut;
        document.getElementById('changerStatutForm').action = '{{ url("/laboratoires/{$laboratoire->code_lab}/admin/equipements/{$equipement->code_equip}/entretiens") }}/' + entretienId;
        document.getElementById('changerStatutForm').submit();
    }
}
</script>
@endpush