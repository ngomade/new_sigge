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
            <p>Vous n'avez pas les permissions nécessaires pour créer un équipement.</p>
            <a href="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour à la liste
            </a>
        </div>
    </div>
@else
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-plus'></i> Nouvel Équipement - {{ $laboratoire->label_labo }}</h2>
        <a href="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour aux équipements
        </a>
    </div>
    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Informations de l'Équipement</h4>
            <form method="POST" action="{{ route('laboratoires.admin.equipements.store', $laboratoire->code_lab) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom_equip" class="form-label">Nom de l'équipement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nom_equip') is-invalid @enderror"
                                   id="nom_equip" name="nom_equip" value="{{ old('nom_equip') }}"
                                   placeholder="Ex: Microscope électronique" required>
                            @error('nom_equip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ref_equip" class="form-label">Référence</label>
                            <input type="text" class="form-control @error('ref_equip') is-invalid @enderror"
                                   id="ref_equip" name="ref_equip" value="{{ old('ref_equip') }}"
                                   placeholder="Ex: ME-2024-001">
                            @error('ref_equip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="desc_equip" class="form-label">Description</label>
                    <textarea class="form-control @error('desc_equip') is-invalid @enderror"
                              id="desc_equip" name="desc_equip" rows="3"
                              placeholder="Description détaillée de l'équipement...">{{ old('desc_equip') }}</textarea>
                    @error('desc_equip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="etat" class="form-label">État <span class="text-danger">*</span></label>
                            <select class="form-select @error('etat') is-invalid @enderror" id="etat" name="etat" required>
                                <option value="">Sélectionner un état</option>
                                <option value="disponible" {{ old('etat') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="en maintenance" {{ old('etat') === 'en maintenance' ? 'selected' : '' }}>En maintenance</option>
                                <option value="hors service" {{ old('etat') === 'hors service' ? 'selected' : '' }}>Hors service</option>
                            </select>
                            @error('etat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date_achat" class="form-label">Date d'achat</label>
                            <input type="date" class="form-control @error('date_achat') is-invalid @enderror"
                                   id="date_achat" name="date_achat" value="{{ old('date_achat') }}">
                            @error('date_achat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="valeur" class="form-label">Valeur (FCFA)</label>
                            <input type="number" class="form-control @error('valeur') is-invalid @enderror"
                                   id="valeur" name="valeur" value="{{ old('valeur') }}"
                                   placeholder="0" min="0" step="0.01">
                            @error('valeur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="localisation" class="form-label">Localisation</label>
                    <input type="text" class="form-control @error('localisation') is-invalid @enderror"
                           id="localisation" name="localisation" value="{{ old('localisation') }}"
                           placeholder="Ex: Salle A12, Étage 2">
                    @error('localisation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="image">Image de l’équipement <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
