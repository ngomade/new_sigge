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
    <h2 class="mb-4"><i class="bx bx-wrench"></i> Tous les entretiens du laboratoire</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>Équipement</th>
                            <th>Type</th>
                            <th>Responsable</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Coût</th>
                            @if($userRole === 'admin' || $userRole === 'chef_projet' || $userRole === 'technicien')
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($entretiens as $entretien)
                        <tr>
                            <td class="d-flex align-items-center gap-2">
                                @if($entretien->equipement && $entretien->equipement->image_path)
                                    <img src="{{ asset('storage/' . $entretien->equipement->image_path) }}" alt="Image de l'équipement" style="max-height: 30px; max-width: 40px;" class="rounded shadow">
                                @else
                                    <i class='bx bx-cog' style="font-size: 1.2rem; color: var(--primary-color);"></i>
                                @endif
                                <span>{{ $entretien->equipement->nom_equip ?? '-' }}</span>
                            </td>
                            <td><span class="badge bg-{{ $entretien->type_badge }}">{{ $entretien->type_label }}</span></td>
                            <td>
                                @if($entretien->personnel && $entretien->persLab)
                                    {{ $entretien->personnel->nom_complet ?? 'Membre interne' }}
                                @elseif($entretien->userExterne)
                                    {{ $entretien->userExterne->nom_user_ext }} {{ $entretien->userExterne->prenom_user_ext }}
                                @else
                                    Non défini
                                @endif
                            </td>
                            <td>{{ $entretien->debut_formatted }} - {{ $entretien->fin_formatted }}</td>
                            <td><span class="badge bg-{{ $entretien->statut_badge }}">{{ $entretien->statut_entretien }}</span></td>
                            <td>{{ $entretien->cout_formatted }}</td>
                            @if($userRole === 'admin' || $userRole === 'chef_projet' || $userRole === 'technicien')
                            <td>
                                @if($entretien->statut_entretien === 'En cours')
                                <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$entretien->equipement->code_lab, $entretien->equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut_entretien" value="Terminé">
                                    <input type="hidden" name="keep_dates" value="true">
                                    <button type="submit" class="btn btn-success btn-sm" title="Terminer"><i class="bx bx-check"></i></button>
                                </form>
                                <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$entretien->equipement->code_lab, $entretien->equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut_entretien" value="En pause">
                                    <input type="hidden" name="keep_dates" value="true">
                                    <button type="submit" class="btn btn-warning btn-sm" title="Mettre en pause"><i class="bx bx-pause"></i></button>
                                </form>
                                <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$entretien->equipement->code_lab, $entretien->equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut_entretien" value="Annulé">
                                    <input type="hidden" name="keep_dates" value="true">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Annuler"><i class="bx bx-x"></i></button>
                                </form>
                                @elseif($entretien->statut_entretien === 'En pause')
                                <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$entretien->equipement->code_lab, $entretien->equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut_entretien" value="En cours">
                                    <input type="hidden" name="keep_dates" value="true">
                                    <button type="submit" class="btn btn-info btn-sm" title="Reprendre"><i class="bx bx-play"></i></button>
                                </form>
                                <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$entretien->equipement->code_lab, $entretien->equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut_entretien" value="Annulé">
                                    <input type="hidden" name="keep_dates" value="true">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Annuler"><i class="bx bx-x"></i></button>
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
