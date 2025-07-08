@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-edit'></i> Modifier l'Équipement - {{ $equipement->nom_equip }}</h2>
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

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Modifier les informations de l'équipement</h4>

            <form action="{{ route('laboratoires.admin.equipements.update', [$laboratoire->code_lab, $equipement->code_equip]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom_equip" class="form-label">Nom de l'équipement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nom_equip') is-invalid @enderror"
                                   id="nom_equip" name="nom_equip" value="{{ old('nom_equip', $equipement->nom_equip) }}"
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
                                   id="ref_equip" name="ref_equip" value="{{ old('ref_equip', $equipement->ref_equip) }}"
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
                              placeholder="Description détaillée de l'équipement...">{{ old('desc_equip', $equipement->desc_equip) }}</textarea>
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
                                <option value="disponible" {{ old('etat', $equipement->etat) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="en maintenance" {{ old('etat', $equipement->etat) === 'en maintenance' ? 'selected' : '' }}>En maintenance</option>
                                <option value="hors service" {{ old('etat', $equipement->etat) === 'hors service' ? 'selected' : '' }}>Hors service</option>
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
                                   id="date_achat" name="date_achat"
                                   value="{{ old('date_achat', $equipement->date_achat ? $equipement->date_achat->format('Y-m-d') : '') }}">
                            @error('date_achat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="valeur" class="form-label">Valeur (FCFA)</label>
                            <input type="number" class="form-control @error('valeur') is-invalid @enderror"
                                   id="valeur" name="valeur"
                                   value="{{ old('valeur', $equipement->valeur) }}"
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
                           id="localisation" name="localisation"
                           value="{{ old('localisation', $equipement->localisation) }}"
                           placeholder="Ex: Salle A12, Étage 2">
                    @error('localisation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
