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
            <p>Vous n'avez pas les permissions nécessaires pour modifier ce projet.</p>
            <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour au projet
            </a>
        </div>
    </div>
@else
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-edit'></i> Modifier le projet - {{ $laboratoire->label_labo }}</h2>
        <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour au projet
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('laboratoires.admin.projets.update', [$laboratoire->code_lab, $projet->code_projet]) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="theme_projet" class="form-label">Thème du projet *</label>
                            <input type="text" class="form-control @error('theme_projet') is-invalid @enderror"
                                   id="theme_projet" name="theme_projet" value="{{ old('theme_projet', $projet->theme_projet) }}" required>
                            @error('theme_projet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="statut_projet" class="form-label">Statut *</label>
                            <select class="form-select @error('statut_projet') is-invalid @enderror" id="statut_projet" name="statut_projet" required>
                                <option value="">Sélectionner un statut</option>
                                <option value="En cours" {{ old('statut_projet', $projet->statut_projet) == 'En cours' ? 'selected' : '' }}>En cours</option>
                                <option value="Terminé" {{ old('statut_projet', $projet->statut_projet) == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                                <option value="En pause" {{ old('statut_projet', $projet->statut_projet) == 'En pause' ? 'selected' : '' }}>En pause</option>
                                <option value="Annulé" {{ old('statut_projet', $projet->statut_projet) == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('statut_projet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description_projet" class="form-label">Description du projet *</label>
                    <textarea class="form-control @error('description_projet') is-invalid @enderror"
                              id="description_projet" name="description_projet" rows="6"
                              placeholder="Décrivez le projet, ses objectifs, sa méthodologie..." required>{{ old('description_projet', $projet->description_projet) }}</textarea>
                    @error('description_projet')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="debut_projet" class="form-label">Date de début *</label>
                            <input type="date" class="form-control @error('debut_projet') is-invalid @enderror"
                                   id="debut_projet" name="debut_projet"
                                   value="{{ old('debut_projet', $projet->debut_projet ? \Carbon\Carbon::parse($projet->debut_projet)->format('Y-m-d') : '') }}" required>
                            @error('debut_projet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fin_projet" class="form-label">Date de fin (optionnel)</label>
                            <input type="date" class="form-control @error('fin_projet') is-invalid @enderror"
                                   id="fin_projet" name="fin_projet"
                                   value="{{ old('fin_projet', $projet->fin_projet ? \Carbon\Carbon::parse($projet->fin_projet)->format('Y-m-d') : '') }}">
                            @error('fin_projet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Mettre à jour le projet
                    </button>
                    <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-outline-secondary">
                        <i class='bx bx-x'></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validation côté client pour les dates
document.getElementById('fin_projet').addEventListener('change', function() {
    const debut = document.getElementById('debut_projet').value;
    const fin = this.value;

    if (debut && fin && fin <= debut) {
        this.setCustomValidity('La date de fin doit être postérieure à la date de début');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endif
@endsection
