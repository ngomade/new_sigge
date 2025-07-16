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
    <h2 class="mb-4"><i class="bx bx-calendar"></i> Toutes les réservations du laboratoire</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>Équipement</th>
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
                            <td>{{ $reservation->equipement->nom_equip ?? '-' }}</td>
                            <td>
                                @if($reservation->personnel && $reservation->personnel->persLab)
                                    {{ $reservation->personnel->persLab->nom_complet ?? 'Membre interne' }}
                                @elseif($reservation->personnel && $reservation->personnel->userExterne)
                                    {{ $reservation->personnel->userExterne->nom_user_ext }} {{ $reservation->personnel->userExterne->prenom_user_ext }}
                                @elseif($reservation->userExterne)
                                    {{ $reservation->userExterne->nom_user_ext }} {{ $reservation->userExterne->prenom_user_ext }}
                                @else
                                    Membre inconnu
                                @endif
                            </td>
                            <td>{{ $reservation->debut_formatted }} - {{ $reservation->fin_formatted }}</td>
                            <td><span class="badge bg-{{ $reservation->statut_badge }}">{{ $reservation->statut_label }}</span></td>
                            <td>{{ $reservation->getDureeFormatted() }}</td>
                            @if($userRole === 'admin' || $userRole === 'chef_projet' || $userRole === 'technicien')
                            <td>
                                @if($reservation->statut === 'en attente')
                                <form method="POST" action="{{ route('laboratoires.admin.equipements.reservation.update', [$reservation->equipement->code_lab, $reservation->equipement->code_equip, $reservation->id]) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="confirmé">
                                    <button type="submit" class="btn btn-success btn-sm" title="Valider"><i class="bx bx-check"></i></button>
                                </form>
                                <form method="POST" action="{{ route('laboratoires.admin.equipements.reservation.update', [$reservation->equipement->code_lab, $reservation->equipement->code_equip, $reservation->id]) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="refusé">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Refuser"><i class="bx bx-x"></i></button>
                                </form>
                                @elseif($reservation->statut === 'confirmé')
                                <form method="POST" action="{{ route('laboratoires.admin.equipements.reservation.update', [$reservation->equipement->code_lab, $reservation->equipement->code_equip, $reservation->id]) }}" style="display:inline-block;">
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
        </div>
    </div>
</div>
@endsection
