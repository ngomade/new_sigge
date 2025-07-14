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
            <h2><i class='bx bx-calendar'></i> Gestion des Réservations - {{ $equipement->nom_equip }}</h2>
            <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}"
                class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Retour aux détails
            </a>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
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
                                <h4 class="text-info">{{ $equipement->reservations->count() }}</h4>
                                <p class="text-muted mb-0">Total réservations</p>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">
                                    {{ $equipement->reservations->where('statut', 'confirmé')->count() }}</h4>
                                <p class="text-muted mb-0">Confirmées</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Nouvelle réservation -->
                @if($userRole === 'admin' || $userRole === 'chef_projet')
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Créer une réservation</h5>
                        <form
                            action="{{ route('laboratoires.admin.equipements.reservation.store', [$laboratoire->code_lab, $equipement->code_equip]) }}"
                            method="POST">
                            @csrf
                            @if(isset($userRole) && $userRole === 'technicien')
                                <div class="mb-3">
                                    <label class="form-label">Membre</label>
                                    <input type="hidden" name="id_pers_lab" value="{{ $affectation->id_pers_lab }}">
                                    <input type="text" class="form-control" value="{{ $affectation->nom_complet }}" readonly>
                                </div>
                            @else
                            <div class="mb-3">
                                    <label for="id_pers_lab" class="form-label">Membre <span class="text-danger">*</span></label>
                                    <select class="form-select @error('id_pers_lab') is-invalid @enderror" id="id_pers_lab" name="id_pers_lab" required>
                                    <option value="">Sélectionner un membre</option>
                                    @foreach ($personnel as $pers)
                                            <option value="{{ $pers->id_pers_lab }}" {{ old('id_pers_lab') == $pers->id_pers_lab ? 'selected' : '' }}>
                                            @if ($pers->persLab)
                                                {{ $pers->persLab->nom_complet ?? 'Membre interne' }}
                                            @elseif($pers->userExterne)
                                                {{ $pers->userExterne->nom_user_ext }}
                                                {{ $pers->userExterne->prenom_user_ext }}
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
                            @endif
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
                @endif
            </div>
            <!-- Liste des réservations -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Historique des réservations</h5>
                        @if($reservations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Membre</th>
                                            <th>Période</th>
                                            <th>Statut</th>
                                            <th>Durée</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reservations as $reservation)
                                            <tr>
                                                <td>
                                            @if($reservation->personnel && $reservation->personnel->persLab)
                                                {{ $reservation->personnel->persLab->nom_complet ?? 'Membre interne' }}
                                                    @elseif($reservation->personnel && $reservation->personnel->userExterne)
                                                        {{ $reservation->personnel->userExterne->nom_user_ext }}
                                                        {{ $reservation->personnel->userExterne->prenom_user_ext }}
                                                    @else
                                                Membre inconnu
                                                    @endif
                                                </td>
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
@endsection
