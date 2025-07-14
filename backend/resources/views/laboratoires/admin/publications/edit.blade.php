@extends('laboratoires.public.layout')

@section('title', 'Modifier la publication')

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
@if($publication->id_pers_lab == $userId)
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-book-edit'></i> Modifier la publication</h2>
        <a href="{{ route('laboratoires.admin.publications.index', $publication->code_lab) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour à la liste
        </a>
    </div>
    @include('laboratoires.partials.alerts')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Modifier les informations de la publication</h4>
            <form action="{{ route('laboratoires.admin.publications.update', [$publication->code_lab, $publication->code_publi]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="titre_publi" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="titre_publi" id="titre_publi" class="form-control @error('titre_publi') is-invalid @enderror" value="{{ old('titre_publi', $publication->titre_publi) }}" required maxlength="255" placeholder="Titre de la publication">
                            @error('titre_publi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                            <label for="type_publi" class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type_publi" id="type_publi" class="form-select @error('type_publi') is-invalid @enderror" required>
                                <option value="">Sélectionner un type</option>
                                <option value="article" {{ old('type_publi', $publication->type_publi) == 'article' ? 'selected' : '' }}>Article</option>
                                <option value="conference" {{ old('type_publi', $publication->type_publi) == 'conference' ? 'selected' : '' }}>Conférence</option>
                                <option value="livre" {{ old('type_publi', $publication->type_publi) == 'livre' ? 'selected' : '' }}>Livre</option>
                                <option value="rapport" {{ old('type_publi', $publication->type_publi) == 'rapport' ? 'selected' : '' }}>Rapport</option>
                                <option value="these" {{ old('type_publi', $publication->type_publi) == 'these' ? 'selected' : '' }}>Thèse</option>
                            </select>
                            @error('type_publi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="domaine" class="form-label">Domaine</label>
                            <input type="text" name="domaine" id="domaine" class="form-control @error('domaine') is-invalid @enderror" value="{{ old('domaine', $publication->domaine) }}" maxlength="100" placeholder="Domaine scientifique">
                            @error('domaine')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" name="tags" id="tags" class="form-control @error('tags') is-invalid @enderror" value="{{ old('tags', $publication->tags) }}" placeholder="Ex: biologie, chimie">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="resume" class="form-label">Résumé</label>
                    <textarea name="resume" id="resume" class="form-control @error('resume') is-invalid @enderror" rows="3" placeholder="Résumé de la publication">{{ old('resume', $publication->resume) }}</textarea>
                    @error('resume')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="reference" class="form-label">Référence</label>
                            <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference', $publication->reference) }}" placeholder="Référence bibliographique">
                            @error('reference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rapport" class="form-label">Rapport (optionnel)</label>
                            <input type="file" name="rapport" id="rapport" class="form-control @error('rapport') is-invalid @enderror" accept=".pdf,.doc,.docx,.ppt,.pptx">
                            <div class="form-text">
                                Formats acceptés : PDF, DOC, DOCX, PPT, PPTX (max 10MB)
                                @if($publication->rapport_path)
                                    <br><small class="text-muted">Fichier actuel : {{ basename($publication->rapport_path) }}</small>
                                @endif
                            </div>
                            @error('rapport')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('laboratoires.admin.publications.index', $publication->code_lab) }}" class="btn btn-secondary">
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
@else
    <div class="container py-4">
        <div class="alert alert-danger">
            <h4><i class='bx bx-error-circle'></i> Accès refusé</h4>
            <p>Vous n'avez pas les permissions nécessaires pour modifier cette publication.</p>
            <a href="{{ route('laboratoires.admin.publications.index', $publication->code_lab) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour à la liste
            </a>
        </div>
    </div>
@endif
@endsection
