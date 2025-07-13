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

@if($userRole !== 'admin' && $userRole !== 'chef_projet')
    <div class="container py-4">
        <div class="alert alert-danger">
            <h4><i class='bx bx-error-circle'></i> Accès refusé</h4>
            <p>Vous n'avez pas les permissions nécessaires pour modifier ce membre.</p>
            <a href="{{ route('laboratoires.admin.membres.show', [$laboratoire->code_lab, $affectation->id_pers_lab ?? $affectation->id_user_externe]) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour au membre
            </a>
        </div>
    </div>
@else
<div class="container py-4">
    <h2 class="mb-4">Modifier le membre du laboratoire : {{ $laboratoire->label_labo }}</h2>
    <form method="POST" action="{{ route('laboratoires.admin.membres.update', [$laboratoire->code_lab, $affectation->id_pers_lab ?? $affectation->id_user_externe]) }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Type de personne</label>
                <input type="text" class="form-control" value="{{ $affectation->userExterne ? 'Externe' : ($affectation->persLab->type_pers_lab ?? '-') }}" disabled>
            </div>
            <div class="col-md-8">
                <label class="form-label">Personne</label>
                <input type="text" class="form-control" value="
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
                " disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_rl" class="form-label">Rôle</label>
                <select class="form-select" id="id_rl" name="id_rl" required>
                    <option value="">Sélectionner un rôle</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id_rl }}" {{ $affectation->id_rl == $role->id_rl ? 'selected' : '' }}>{{ $role->lib_rl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="statut" class="form-label">Statut</label>
                <select class="form-select" id="statut" name="statut" required>
                    <option value="actif" {{ $affectation->statut === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ $affectation->statut === 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="date_affectation" class="form-label">Date d'affectation</label>
                <input type="date" class="form-control" id="date_affectation" name="date_affectation" value="{{ old('date_affectation', $affectation->date_affectation ? (is_string($affectation->date_affectation) ? $affectation->date_affectation : $affectation->date_affectation->format('Y-m-d')) : '') }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date_fin_affectation" class="form-label">Date de fin d'affectation (optionnel)</label>
                <input type="date" class="form-control" id="date_fin_affectation" name="date_fin_affectation" value="{{ old('date_fin_affectation', $affectation->date_fin_affectation ? (is_string($affectation->date_fin_affectation) ? $affectation->date_fin_affectation : $affectation->date_fin_affectation->format('Y-m-d')) : '') }}">
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <a href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-save"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endif
@endsection
