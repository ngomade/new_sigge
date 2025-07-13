@extends('laboratoires.public.layout')

@section('title', 'Détails de la publication')

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
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-book-content'></i> Détails de la publication</h2>
        <a href="{{ route('labo.publications.index') }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour à la liste
        </a>
    </div>
    @include('laboratoires.partials.alerts')
    <div class="card">
        <div class="card-body">
            <h3>{{ $publication->titre_publi }}</h3>
            <p><strong>Type :</strong> {{ ucfirst($publication->type_publi) }}</p>
            <p><strong>Domaine :</strong> {{ $publication->domaine }}</p>
            <p><strong>Résumé :</strong> {!! nl2br(e($publication->resume)) !!}</p>
            <p><strong>Tags :</strong> {{ $publication->tags }}</p>
            <p><strong>Référence :</strong> {{ $publication->reference }}</p>
            <!-- Section Rapport -->
            @if($publication->rapport_path)
                <p><strong>Rapport :</strong> <a href="{{ Storage::url($publication->rapport_path) }}" target="_blank" class="btn btn-sm btn-primary"><i class='bx bx-file-pdf'></i> Consulter</a></p>
            @endif
            <p><strong>Créateur :</strong>
                @if($publication->createur)
                    <span class="badge bg-info me-1">{{ ucfirst($publication->createur->type_pers_lab) }}</span>
                    {{ $publication->createur->nom_complet }}
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </p>
            <p><strong>Date de création :</strong> {{ $publication->created_at->format('d/m/Y') }}</p>
        </div>
    </div>
    <div class="mt-3 d-flex gap-2">
        @if($userRole === 'admin' || $userRole === 'chef_projet' || $publication->id_pers_lab === $userId)
            <a href="{{ route('labo.publications.edit', $publication->code_publi) }}" class="btn btn-warning">
                <i class='bx bx-edit'></i> Modifier
            </a>
            <form action="{{ route('labo.publications.destroy', $publication->code_publi) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Confirmer la suppression de cette publication ?')">
                    <i class='bx bx-trash'></i> Supprimer
                </button>
            </form>
        @endif
        <a href="{{ route('labo.publications.index') }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour à la liste
        </a>
    </div>
</div>
@endsection
