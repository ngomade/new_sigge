@extends('laboratoires.public.layout')

@section('title', 'Détails du rapport')

@section('content')
<div class="container mt-4">
    <h1>Détails du rapport</h1>

    @include('laboratoires.partials.alerts')

    <div class="card">
        <div class="card-body">
            <h3>{{ $rapport->titre }}</h3>
            <p><strong>Description:</strong> {!! nl2br(e($rapport->description)) !!}</p>
            <p><strong>Chemin du fichier:</strong> {{ $rapport->fichier_path }}</p>
            <p><strong>Date de création:</strong> {{ $rapport->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('labo.rapports.edit', $rapport->code_rapport) }}" class="btn btn-warning">Modifier</a>
        <a href="{{ route('labo.rapports.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>
</div>
@endsection
