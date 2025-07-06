@extends('laboratoires.public.layout')

@section('title', 'Ajouter une publication')

@section('content')
<div class="container mt-4">
    <h1>Ajouter une publication</h1>

    @include('laboratoires.partials.alerts')

    <form action="{{ route('labo.publications.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="titre_publi" class="form-label">Titre <span class="text-danger">*</span></label>
            <input type="text" name="titre_publi" id="titre_publi" class="form-control @error('titre_publi') is-invalid @enderror" value="{{ old('titre_publi') }}" required maxlength="255">
            @error('titre_publi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="type_publi" class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type_publi" id="type_publi" class="form-select @error('type_publi') is-invalid @enderror" required>
                <option value="">Sélectionner un type</option>
                <option value="article" {{ old('type_publi') == 'article' ? 'selected' : '' }}>Article</option>
                <option value="conference" {{ old('type_publi') == 'conference' ? 'selected' : '' }}>Conférence</option>
                <option value="livre" {{ old('type_publi') == 'livre' ? 'selected' : '' }}>Livre</option>
                <option value="rapport" {{ old('type_publi') == 'rapport' ? 'selected' : '' }}>Rapport</option>
                <option value="these" {{ old('type_publi') == 'these' ? 'selected' : '' }}>Thèse</option>
            </select>
            @error('type_publi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="domaine" class="form-label">Domaine</label>
            <input type="text" name="domaine" id="domaine" class="form-control @error('domaine') is-invalid @enderror" value="{{ old('domaine') }}" maxlength="100">
            @error('domaine')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="resume" class="form-label">Résumé</label>
            <textarea name="resume" id="resume" class="form-control @error('resume') is-invalid @enderror" rows="4">{{ old('resume') }}</textarea>
            @error('resume')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <input type="text" name="tags" id="tags" class="form-control @error('tags') is-invalid @enderror" value="{{ old('tags') }}">
            @error('tags')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="reference" class="form-label">Référence</label>
            <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference') }}">
            @error('reference')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="rapport_path" class="form-label">Chemin du rapport</label>
            <input type="text" name="rapport_path" id="rapport_path" class="form-control @error('rapport_path') is-invalid @enderror" value="{{ old('rapport_path') }}">
            @error('rapport_path')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="id_pers_lab" class="form-label">Créateur <span class="text-danger">*</span></label>
            <select name="id_pers_lab" id="id_pers_lab" class="form-select @error('id_pers_lab') is-invalid @enderror" required>
                <option value="">Sélectionner un créateur</option>
                @foreach($membres as $membre)
                    <option value="{{ $membre->id_pers_lab }}" {{ old('id_pers_lab') == $membre->id_pers_lab ? 'selected' : '' }}>
                        {{ $membre->nom }} {{ $membre->prenom }} ({{ $membre->laboratoire->label_labo ?? '' }})
                    </option>
                @endforeach
            </select>
            @error('id_pers_lab')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="code_lab" class="form-label">Laboratoire</label>
            <input type="text" name="code_lab" id="code_lab" class="form-control @error('code_lab') is-invalid @enderror" value="{{ old('code_lab') }}">
            @error('code_lab')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('labo.publications.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
