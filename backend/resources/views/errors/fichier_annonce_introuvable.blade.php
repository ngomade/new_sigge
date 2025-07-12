@extends('laboratoires.public.layout')

@section('title', 'Fichier introuvable')

@section('content')
<div class="container py-5">
    <div class="alert alert-danger text-center">
        <h2 class="mb-3"><i class="bi bi-exclamation-triangle"></i> Fichier introuvable</h2>
        <p>{{ $message ?? 'Le fichier demandé est introuvable ou a été supprimé.' }}</p>
        <a href="{{ route('laboratoires.admin.annonces', $laboratoire->code_lab) }}" class="btn btn-primary mt-3">
            <i class="bi bi-arrow-left"></i> Retour aux annonces
        </a>
    </div>
</div>
@endsection
