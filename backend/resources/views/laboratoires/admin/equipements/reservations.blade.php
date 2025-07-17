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
    $peutReserver = $userRole !== 'secretaire';
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
                @if($peutReserver)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Créer une réservation</h5>
                        <form
                            action="{{ route('laboratoires.admin.equipements.reservation.store', [$laboratoire->code_lab, $equipement->code_equip]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="participant_type" value="{{ $userType === 'externe' ? 'externe' : 'interne' }}">
                            @if($userType === 'externe')
                                <input type="hidden" name="id_user_ext" value="{{ $userId }}">
                            @else
                                <input type="hidden" name="id_pers_lab" value="{{ $userId }}">
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Responsable de la réservation</label>
                                <div class="alert alert-info">
                                    <i class="bx bx-user"></i>
                                    <strong>{{ session('user_name') }}</strong>
                                    <br><small class="text-muted">Vous effectuez cette réservation pour vous-même</small>
                                </div>
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
                                            @if($userRole === 'admin' || $userRole === 'chef_projet' || $userRole === 'technicien')
                                            <th>Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reservations as $reservation)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <strong>{{ $reservation->responsable['nom'] }}</strong>
                                                        <small class="text-muted">{{ $reservation->responsable['email'] }}</small>
                                                        @if($reservation->responsable['telephone'] !== 'Non défini')
                                                            <small class="text-muted">{{ $reservation->responsable['telephone'] }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $reservation->debut_formatted }} - {{ $reservation->fin_formatted }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $reservation->statut_badge }}">
                                                        {{ $reservation->statut_label }}
                                                    </span>
                                                </td>
                                                <td>{{ $reservation->getDureeFormatted() }}</td>
                                                @if($userRole === 'admin' || $userRole === 'chef_projet' || $userRole === 'technicien')
                                                <td>
                                                    @if($reservation->statut === 'en attente')
                                                    <form method="POST" action="{{ route('laboratoires.admin.equipements.reservation.update', [$laboratoire->code_lab, $equipement->code_equip, $reservation->id]) }}" style="display:inline-block;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="statut" value="confirmé">
                                                        <button type="submit" class="btn btn-success btn-sm" title="Valider"><i class="bx bx-check"></i></button>
                                                    </form>
                                                    <form method="POST" action="{{ route('laboratoires.admin.equipements.reservation.update', [$laboratoire->code_lab, $equipement->code_equip, $reservation->id]) }}" style="display:inline-block;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="statut" value="refusé">
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Refuser"><i class="bx bx-x"></i></button>
                                                    </form>
                                                    @elseif($reservation->statut === 'confirmé')
                                                    <form method="POST" action="{{ route('laboratoires.admin.equipements.reservation.update', [$laboratoire->code_lab, $equipement->code_equip, $reservation->id]) }}" style="display:inline-block;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="statut" value="annulé">
                                                        <button type="submit" class="btn btn-warning btn-sm" title="Annuler"><i class="bx bx-block"></i></button>
                                                    </form>
                                                    @endif
                                                </td>
                                                @endif
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
