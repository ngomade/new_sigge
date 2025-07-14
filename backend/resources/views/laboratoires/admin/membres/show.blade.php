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
    <h2 class="mb-4">Fiche membre du laboratoire : {{ $laboratoire->label_labo }}</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Identité</h5>
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><strong>ID :</strong> {{ $affectation->id_pers_lab ?? $affectation->id_user_externe }}</li>
                <li class="list-group-item"><strong>Type :</strong> {{ $affectation->userExterne ? 'Externe' : ($affectation->persLab->type_pers_lab ?? '-') }}</li>
                <li class="list-group-item"><strong>Nom :</strong>
                    @if($affectation->userExterne)
                        {{ $affectation->userExterne->nom_user_ext }}
                        {{ $affectation->userExterne->prenom_user_ext }}
                    @elseif($affectation->persLab)
                        @if($affectation->persLab->type_pers_lab === 'personnel')
                            {{ optional(\App\Models\Personnel::find($affectation->id_pers_lab))->nom_pers }}
                            {{ optional(\App\Models\Personnel::find($affectation->id_pers_lab))->prenom_pers }}
                        @elseif($affectation->persLab->type_pers_lab === 'user')
                            {{ optional(\App\Models\Users::find($affectation->id_pers_lab))->nom_user }}
                            {{ optional(\App\Models\Users::find($affectation->id_pers_lab))->prenom_user }}
                        @endif
                    @endif
                </li>
                <li class="list-group-item"><strong>Rôle :</strong> {{ $affectation->roleLabo->lib_rl ?? '-' }}</li>
                <li class="list-group-item"><strong>Statut :</strong> <span class="badge bg-{{ $affectation->statut === 'actif' ? 'success' : 'secondary' }}">{{ ucfirst($affectation->statut) }}</span></li>
                <li class="list-group-item"><strong>Date d'affectation :</strong>
                    @php
                        $date = $affectation->date_affectation;
                        if (is_string($date) && !empty($date)) {
                            $date = \Carbon\Carbon::parse($date);
                        }
                    @endphp
                    {{ $date && $date instanceof \Carbon\Carbon ? $date->format('d/m/Y') : '-' }}
                </li>
                <li class="list-group-item"><strong>Date de fin d'affectation :</strong>
                    @php
                        $dateFin = $affectation->date_fin_affectation;
                        if (is_string($dateFin) && !empty($dateFin)) {
                            $dateFin = \Carbon\Carbon::parse($dateFin);
                        }
                    @endphp
                    {{ $dateFin && $dateFin instanceof \Carbon\Carbon ? $dateFin->format('d/m/Y') : '-' }}
                </li>
            </ul>
            <div class="d-flex gap-2">
                @if($userRole === 'admin' || $userRole === 'chef_projet')
                <a href="{{ route('laboratoires.admin.membres.edit', [$laboratoire->code_lab, $affectation->id_pers_lab ?? $affectation->id_user_externe]) }}" class="btn btn-primary"><i class="bx bx-edit"></i> Modifier</a>
                @endif
                @if($userRole === 'admin')
                <form method="POST" action="{{ route('laboratoires.admin.membres.destroy', [$laboratoire->code_lab, $affectation->id_pers_lab ?? $affectation->id_user_externe]) }}" onsubmit="return confirm('Confirmer la suppression de ce membre ?');">
                    @csrf
                    <button type="submit" class="btn btn-danger"><i class="bx bx-trash"></i> Supprimer</button>
                </form>
                @endif
                <a href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</div>
@endsection
