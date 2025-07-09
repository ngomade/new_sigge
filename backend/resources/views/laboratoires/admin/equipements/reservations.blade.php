@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-calendar'></i> Gestion des Réservations - {{ $equipement->nom_equip }}</h2>
        <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back"></i> Retour aux détails
        </a>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}

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
                            <h4 class="text-info">{{ $equipement->reservations->count() }}</h4>
                            <p class="text-muted mb-0">Total réservations</p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $equipement->reservations->where('statut', 'confirmé')->count() }}</h4>
                            <p class="text-muted mb-0">Confirmées</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nouvelle réservation -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Créer une réservation</h5>

                    <form action="{{ route('laboratoires.admin.equipements.reservation.store', [$laboratoire->code_lab, $equipement->code_equip]) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="id_pers_lab" class="form-label">Membre <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_pers_lab') is-invalid @enderror" id="id_pers_lab" name="id_pers_lab" required>
                                <option value="">Sélectionner un membre</option>
                                @foreach($personnel as $pers)
                                    <option value="{{ $pers->id_pers_lab }}" {{ old('id_pers_lab') == $pers->id_pers_lab ? 'selected' : '' }}>
                                        @if($pers->persLab)
                                            {{ $pers->persLab->nom_complet }}
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
                            <label for="debut_reserv" class="form-label">Date de début <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('debut_reserv') is-invalid @enderror"
                                   id="debut_reserv" name="debut_reserv" value="{{ old('debut_reserv') }}" required>
                            @error('debut_reserv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fin_reserv" class="form-label">Date de fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fin_reserv') is-invalid @enderror"
                                   id="fin_reserv" name="fin_reserv" value="{{ old('fin_reserv') }}" required>
                            @error('fin_reserv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-plus"></i> Créer la réservation
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des réservations -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Historique des réservations</h5>

                    @if($equipement->reservations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Membre</th>
                                    <th>Période</th>
                                    <th>Statut</th>
                                    <th>Durée</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipement->reservations->sortByDesc('created_at') as $reservation)
                                <tr>
                                    <td>
                                        @if($reservation->personnel && $reservation->personnel->persLab)
                                            {{ $reservation->personnel->persLab->nom_complet }}
                                        @elseif($reservation->personnel && $reservation->personnel->userExterne)
                                            {{ $reservation->personnel->userExterne->nom_user_ext }} {{ $reservation->personnel->userExterne->prenom_user_ext }}
                                        @else
                                            Non défini
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $reservation->debut_formatted }}</div>
                                        <small class="text-muted">au {{ $reservation->fin_formatted }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $reservation->statut_badge }}">
                                            {{ $reservation->statut_label }}
                                        </span>
                                        @if($reservation->isActive())
                                            <br><small class="text-success">{{ $reservation->getJoursRestantsFormatted() }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $reservation->getDureeFormatted() }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="text-muted dropdown-toggle font-size-16 p-2" href="#" role="button" data-bs-toggle="dropdown">
                                                <i class="uil uil-ellipsis-h"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @if($reservation->isEnAttente())
                                                <a class="dropdown-item text-success" href="#" onclick="changerStatutReservation('{{ $reservation->id_pers_lab }}', 'confirmé')">
                                                    <i class="bx bx-check me-2"></i>Confirmer
                                                </a>
                                                <a class="dropdown-item text-danger" href="#" onclick="changerStatutReservation('{{ $reservation->id_pers_lab }}', 'refusé')">
                                                    <i class="bx bx-x me-2"></i>Refuser
                                                </a>
                                                @endif
                                                @if($reservation->isConfirme() && !$reservation->isPasse())
                                                <a class="dropdown-item text-warning" href="#" onclick="changerStatutReservation('{{ $reservation->id_pers_lab }}', 'annulé')">
                                                    <i class="bx bx-stop me-2"></i>Annuler
                                                </a>
                                                @endif
                                                @if($reservation->isEnAttente())
                                                <a class="dropdown-item text-secondary" href="#" onclick="changerStatutReservation('{{ $reservation->id_pers_lab }}', 'annulé')">
                                                    <i class="bx bx-trash me-2"></i>Supprimer
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
                        <i class="bx bx-calendar font-size-48 text-muted"></i>
                        <p class="text-muted mt-2">Aucune réservation enregistrée pour cet équipement</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulaire pour changer le statut des réservations -->
<form id="changerStatutReservationForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="statut" id="nouveauStatutReservation">
</form>

@endsection

@push('scripts')
<script>
function changerStatutReservation(reservationId, nouveauStatut) {
    let message = '';
    switch(nouveauStatut) {
        case 'confirmé':
            message = 'Êtes-vous sûr de vouloir confirmer cette réservation ?';
            break;
        case 'refusé':
            message = 'Êtes-vous sûr de vouloir refuser cette réservation ?';
            break;
        case 'annulé':
            message = 'Êtes-vous sûr de vouloir annuler cette réservation ?';
            break;
        default:
            message = 'Êtes-vous sûr de vouloir changer le statut de cette réservation ?';
    }

    if (confirm(message)) {
        document.getElementById('nouveauStatutReservation').value = nouveauStatut;
        document.getElementById('changerStatutReservationForm').action = '{{ url("/laboratoires/{$laboratoire->code_lab}/admin/equipements/{$equipement->code_equip}/reservations") }}/' + reservationId;
        document.getElementById('changerStatutReservationForm').submit();
    }
}
}
</script>
@endpush
