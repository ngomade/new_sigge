@extends('laboratoires.public.layout')

@section('title', 'Détails de la publication')

@section('content')
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
            <div class="mt-4">
                <h5><i class='bx bx-file-pdf'></i> Rapport</h5>
                @if($publication->rapport_path)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class='bx bx-file'></i> Fichier joint
                                    </h6>
                                    <p class="card-text">
                                        <strong>Nom du fichier :</strong> {{ basename($publication->rapport_path) }}<br>
                                        <strong>Type :</strong> {{ strtoupper(pathinfo($publication->rapport_path, PATHINFO_EXTENSION)) }}
                                    </p>
                                    <div class="d-flex gap-2">
                                        <a href="{{ Storage::url($publication->rapport_path) }}"
                                           target="_blank"
                                           class="btn btn-primary btn-sm">
                                            <i class='bx bx-show'></i> Consulter
                                        </a>
                                        <a href="{{ Storage::url($publication->rapport_path) }}"
                                           download="{{ basename($publication->rapport_path) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class='bx bx-download'></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @php
                                $extension = strtolower(pathinfo($publication->rapport_path, PATHINFO_EXTENSION));
                                $isPdf = $extension === 'pdf';
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp

                            @if($isPdf)
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class='bx bx-preview'></i> Aperçu PDF</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <iframe src="{{ Storage::url($publication->rapport_path) }}"
                                                width="100%"
                                                height="400"
                                                style="border: none;">
                                            Votre navigateur ne supporte pas l'affichage des PDF.
                                        </iframe>
                                    </div>
                                </div>
                            @elseif($isImage)
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class='bx bx-image'></i> Aperçu Image</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <img src="{{ Storage::url($publication->rapport_path) }}"
                                             class="img-fluid"
                                             style="max-height: 400px;"
                                             alt="Aperçu du rapport">
                                    </div>
                                </div>
                            @else
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class='bx bx-file' style="font-size: 4rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Aperçu non disponible pour ce type de fichier</p>
                                        <small class="text-muted">Format: {{ strtoupper($extension) }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class='bx bx-info-circle'></i> Aucun rapport n'a été joint à cette publication.
                    </div>
                @endif
            </div>

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
        <a href="{{ route('labo.publications.edit', $publication->code_publi) }}" class="btn btn-warning">
            <i class='bx bx-edit'></i> Modifier
        </a>
        <a href="{{ route('labo.publications.index') }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour à la liste
        </a>
    </div>
</div>
@endsection
