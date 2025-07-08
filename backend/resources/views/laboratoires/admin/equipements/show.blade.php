@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-cabinet'></i> Détails de l'Équipement - {{ $equipement->nom_equip }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('laboratoires.admin.equipements.edit', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-primary">
                <i class="bx bx-edit"></i> Modifier
            </a>
            <a href="{{ route('laboratoires.admin.equipements.entretiens', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-warning">
                <i class="bx bx-wrench"></i> Entretiens
            </a>
            <a href="{{ route('laboratoires.admin.equipements.reservations', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-info">
                <i class="bx bx-calendar"></i> Réservations
            </a>
            <a href="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Retour
            </a>
        </div>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $equipement->nom_equip }}</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Code :</td>
                                    <td><span class="badge bg-primary">{{ $equipement->code_equip }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Référence :</td>
                                    <td>{{ $equipement->ref_equip ?: 'Non définie' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">État :</td>
                                    <td><span class="badge bg-{{ $equipement->etat_badge }}">{{ $equipement->etat_label }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Localisation :</td>
                                    <td>{{ $equipement->localisation ?: 'Non définie' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Date d'achat :</td>
                                    <td>{{ $equipement->date_achat_formatted }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Valeur :</td>
                                    <td>{{ $equipement->valeur_formatted }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Laboratoire :</td>
                                    <td>{{ $equipement->laboratoire->label_labo }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ajouté le :</td>
                                    <td>{{ \Carbon\Carbon::parse($equipement->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($equipement->desc_equip)
                    <div class="mt-4">
                        <h6 class="fw-bold">Description :</h6>
                        <p class="text-muted">{{ $equipement->desc_equip }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Entretien en cours -->
            @if($equipement->getEntretienEnCours())
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="bx bx-wrench"></i> Entretien en cours
                    </h5>
                    @php $entretien = $equipement->getEntretienEnCours() @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Type :</strong> <span class="badge bg-{{ $entretien->type_badge }}">{{ $entretien->type_label }}</span></p>
                            <p><strong>Responsable :</strong> {{ $entretien->personnel->persLab->nom ?? 'Non défini' }}</p>
                            <p><strong>Début :</strong> {{ $entretien->debut_formatted }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fin prévue :</strong> {{ $entretien->fin_formatted }}</p>
                            <p><strong>Durée :</strong> {{ $entretien->getDureeFormatted() }}</p>
                            <p><strong>Coût :</strong> {{ $entretien->cout_formatted }}</p>
                        </div>
                    </div>
                    @if($entretien->desc_entretien)
                    <div class="mt-3">
                        <p><strong>Description :</strong></p>
                        <p class="text-muted">{{ $entretien->desc_entretien }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Réservation active -->
            @if($equipement->hasReservationActive())
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title text-info">
                        <i class="bx bx-calendar-check"></i> Réservation active
                    </h5>
                    @php $reservation = $equipement->getReservationActive() @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Réservé par :</strong> {{ $reservation->personnel->persLab->nom ?? 'Non défini' }}</p>
                            <p><strong>Début :</strong> {{ $reservation->debut_formatted }}</p>
                            <p><strong>Fin :</strong> {{ $reservation->fin_formatted }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Durée :</strong> {{ $reservation->getDureeFormatted() }}</p>
                            <p><strong>Jours restants :</strong> {{ $reservation->getJoursRestantsFormatted() }}</p>
                            <p><strong>Statut :</strong> <span class="badge bg-{{ $reservation->statut_badge }}">{{ $reservation->statut_label }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Statistiques et actions rapides -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $equipement->entretiens->count() }}</h4>
                                <p class="text-muted mb-0">Entretiens</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info">{{ $equipement->reservations->count() }}</h4>
                            <p class="text-muted mb-0">Réservations</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Actions rapides</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('laboratoires.admin.equipements.entretiens', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-warning">
                            <i class="bx bx-wrench"></i> Programmer un entretien
                        </a>
                        <a href="{{ route('laboratoires.admin.equipements.reservations', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-info">
                            <i class="bx bx-calendar-plus"></i> Créer une réservation
                        </a>
                        @if($equipement->isDisponible())
                        <button class="btn btn-outline-success" onclick="changerEtat('en maintenance')">
                            <i class="bx bx-wrench"></i> Mettre en maintenance
                        </button>
                        @elseif($equipement->isEnMaintenance())
                        <button class="btn btn-outline-success" onclick="changerEtat('disponible')">
                            <i class="bx bx-check"></i> Rendre disponible
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des entretiens -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Historique des entretiens</h5>
                        <a href="{{ route('laboratoires.admin.equipements.entretiens', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-primary btn-sm">
                            Voir tout
                        </a>
                    </div>

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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipement->entretiens->take(5) as $entretien)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $entretien->type_badge }}">
                                            {{ $entretien->type_label }}
                                        </span>
                                    </td>
                                    <td>{{ $entretien->personnel->persLab->nom ?? 'Non défini' }}</td>
                                    <td>{{ $entretien->debut_formatted }} - {{ $entretien->fin_formatted }}</td>
                                    <td>
                                        <span class="badge bg-{{ $entretien->statut_badge }}">
                                            {{ $entretien->statut_entretien }}
                                        </span>
                                    </td>
                                    <td>{{ $entretien->cout_formatted }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bx bx-wrench font-size-48 text-muted"></i>
                        <p class="text-muted mt-2">Aucun entretien enregistré</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des réservations -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Historique des réservations</h5>
                        <a href="{{ route('laboratoires.admin.equipements.reservations', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-primary btn-sm">
                            Voir tout
                        </a>
                    </div>

                    @if($equipement->reservations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Réservé par</th>
                                    <th>Période</th>
                                    <th>Statut</th>
                                    <th>Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipement->reservations->take(5) as $reservation)
                                <tr>
                                    <td>{{ $reservation->personnel->persLab->nom ?? 'Non défini' }}</td>
                                    <td>{{ $reservation->debut_formatted }} - {{ $reservation->fin_formatted }}</td>
                                    <td>
                                        <span class="badge bg-{{ $reservation->statut_badge }}">
                                            {{ $reservation->statut_label }}
                                        </span>
                                    </td>
                                    <td>{{ $reservation->getDureeFormatted() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bx bx-calendar font-size-48 text-muted"></i>
                        <p class="text-muted mt-2">Aucune réservation enregistrée</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulaire pour changer l'état -->
<form id="changerEtatForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="etat" id="nouvelEtat">
</form>

@endsection

@push('scripts')
<script>
function changerEtat(nouvelEtat) {
    if (confirm('Êtes-vous sûr de vouloir changer l\'état de cet équipement ?')) {
        document.getElementById('nouvelEtat').value = nouvelEtat;
        document.getElementById('changerEtatForm').action = '{{ route("laboratoires.admin.equipements.update", [$laboratoire->code_lab, $equipement->code_equip]) }}';
        document.getElementById('changerEtatForm').submit();
    }
}
</script>
@endpush
