@extends('laboratoires.public.layout')

@section('title', 'Modifier le rapport')

@section('content')
<div class="container mt-4">
    <h1>Modifier le rapport</h1>

    @include('laboratoires.partials.alerts')

    <form action="{{ route('labo.rapports.update', $rapport->code_rapport) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
            <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre', $rapport->titre) }}" required maxlength="255">
            @error('titre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $rapport->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="fichier_path" class="form-label">Chemin du fichier</label>
            <input type="text" name="fichier_path" id="fichier_path" class="form-control @error('fichier_path') is-invalid @enderror" value="{{ old('fichier_path', $rapport->fichier_path) }}">
            @error('fichier_path')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('labo.rapports.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
