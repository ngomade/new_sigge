@extends('laboratoires.public.layout')

@section('title', 'Détails de la publication')

@section('content')
<div class="container mt-4">
    <h1>Détails de la publication</h1>

    @include('laboratoires.partials.alerts')

    <div class="card">
        <div class="card-body">
            <h3>{{ $publication->titre_publi }}</h3>
            <p><strong>Type:</strong> {{ ucfirst($publication->type_publi) }}</p>
            <p><strong>Domaine:</strong> {{ $publication->domaine }}</p>
            <p><strong>Résumé:</strong> {!! nl2br(e($publication->resume)) !!}</p>
            <p><strong>Tags:</strong> {{ $publication->tags }}</p>
            <p><strong>Référence:</strong> {{ $publication->reference }}</p>
            <p><strong>Chemin du rapport:</strong> {{ $publication->rapport_path }}</p>
            <p><strong>Créateur:</strong> {{ $publication->createur->nom ?? 'N/A' }}</p>
            <p><strong>Date de création:</strong> {{ $publication->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('labo.publications.edit', $publication->code_publi) }}" class="btn btn-warning">Modifier</a>
        <a href="{{ route('labo.publications.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>
</div>
@endsection
