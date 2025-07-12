@extends('laboratoires.public.layout')

@section('title', 'Annonces du laboratoire - ' . $laboratoire->nom_labo)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0">Annonces du laboratoire</h2>
        <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    @if($isAdmin)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-megaphone"></i> Envoyer une annonce à tous les membres
        </div>
        <div class="card-body">
            <form action="{{ route('laboratoires.admin.annonces.store', $laboratoire->code_lab) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label for="titre" class="form-label">Titre (optionnel)</label>
                    <input type="text" name="titre" id="titre" class="form-control" maxlength="255" placeholder="Titre de l'annonce">
                </div>
                <div class="mb-2">
                    <label for="contenu" class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea name="contenu" id="contenu" class="form-control" rows="3" maxlength="2000" required placeholder="Votre message à tous les membres..."></textarea>
                </div>
                <div class="mb-2">
                    <label for="fichier" class="form-label">Fichier joint (optionnel, max 5 Mo)</label>
                    <input type="file" name="fichier" id="fichier" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.ppt,.pptx">
                </div>
                <button type="submit" class="btn btn-primary mt-2">
                    <i class="bi bi-send"></i> Envoyer l'annonce
                </button>
            </form>
        </div>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <i class="bi bi-bell"></i> Annonces récentes
        </div>
        <div class="card-body p-0">
            @if($annonces->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($annonces as $annonce)
                    <li class="list-group-item py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold mb-1">
                                    @if($annonce->titre)
                                        <i class="bi bi-megaphone text-primary"></i> {{ $annonce->titre }}
                                    @else
                                        <i class="bi bi-megaphone text-primary"></i> Annonce
                                    @endif
                                </div>
                                <div class="mb-1">{!! nl2br(e($annonce->contenu)) !!}</div>
                                @if($annonce->fichier)
                                    <div class="mt-2">
                                        <a href="{{ route('laboratoires.admin.annonces.download', [$laboratoire->code_lab, $annonce->id]) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="bi bi-paperclip"></i> Voir le fichier joint
                                        </a>
                                    </div>
                                @endif
                                <div class="text-muted small mt-1">
                                    Envoyé le {{ $annonce->created_at->format('d/m/Y H:i') }} par
                                    <span class="fw-medium">{{ $annonce->admin ? ($annonce->admin->nom ?? $annonce->admin->prenom ?? 'Admin') : 'Admin' }}</span>
                                </div>
                            </div>
                            @if($isAdmin)
                            <form action="{{ route('laboratoires.admin.annonces.delete', [$laboratoire->code_lab, $annonce->id]) }}" method="POST" onsubmit="return confirm('Supprimer cette annonce ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 ms-3" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bell-slash fs-1"></i>
                    <div class="mt-2">Aucune annonce pour le moment</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
