@extends('laboratoires.public.layout')

@section('title', 'Détails du Rapport - ' . $laboratoire->nom_labo)

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header moderne -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-semibold text-dark">Détails du Rapport</h1>
            <p class="text-muted mb-0">{{ $laboratoire->label_labo }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laboratoires.admin.rapports', $laboratoire->code_lab) }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
            @if(file_exists(storage_path('app/' . $rapport->path_rl)))
                <a href="{{ route('laboratoires.admin.rapports.download', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-download me-1"></i>Télécharger
                </a>
            @endif
            @if($userRole)
                <form action="{{ route('laboratoires.admin.rapports.destroy', [$laboratoire->code_lab, $rapport->code_rl]) }}" method="POST" class="d-inline" id="delete-form-{{ $rapport->code_rl }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression de ce rapport ?')">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <!-- Informations du rapport -->
            <div class="card border-0 bg-white shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">Informations du Rapport</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Code du rapport</label>
                            <div class="fw-semibold">{{ $rapport->code_rl }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Type de fichier</label>
                            <div>
                                @if(str_contains($rapport->path_rl, '.pdf'))
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-file-pdf me-1"></i>PDF
                                    </span>
                                @elseif(str_contains($rapport->path_rl, '.docx'))
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="bi bi-file-word me-1"></i>Word
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="bi bi-file-earmark me-1"></i>Fichier
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Date de création</label>
                            <div>{{ \Carbon\Carbon::parse($rapport->created_at)->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Dernière modification</label>
                            <div>{{ \Carbon\Carbon::parse($rapport->updated_at)->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">Description</label>
                            <div>{{ $rapport->desc_rapport ?? 'Aucune description' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aperçu du fichier -->
            @if(file_exists(storage_path('app/' . $rapport->path_rl)))
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">Aperçu du Fichier</h5>
                    </div>
                    <div class="card-body p-0">
                        @if(str_contains($rapport->path_rl, '.pdf'))
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ route('laboratoires.admin.rapports.view', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                                        class="border-0"></iframe>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-file-earmark-text text-muted fs-1 mb-3"></i>
                                <h5 class="text-muted mb-2">Aperçu non disponible</h5>
                                <p class="text-muted mb-4">Ce type de fichier ne peut pas être prévisualisé</p>
                                <a href="{{ route('laboratoires.admin.rapports.download', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                                   class="btn btn-primary">
                                    <i class="bi bi-download me-1"></i>Télécharger pour voir
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-exclamation-triangle text-warning fs-1 mb-3"></i>
                        <h5 class="text-warning mb-2">Fichier non trouvé</h5>
                        <p class="text-muted mb-4">Le fichier associé à ce rapport n'existe plus sur le serveur</p>
                        <form action="{{ route('laboratoires.admin.rapports.destroy', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')">
                                <i class="bi bi-trash me-1"></i>Supprimer le rapport
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-xl-4">
            <!-- Actions rapides -->
            <div class="card border-0 bg-white shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">Actions</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @if(file_exists(storage_path('app/' . $rapport->path_rl)))
                            <a href="{{ route('laboratoires.admin.rapports.download', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                               class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle p-2 me-3">
                                        <i class="bi bi-download text-white small"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Télécharger</div>
                                        <small class="text-muted">Récupérer le fichier</small>
                                    </div>
                                </div>
                            </a>
                        @endif
                        <button type="button" class="list-group-item list-group-item-action border-0 py-3"
                                onclick="copyToClipboard('{{ $rapport->code_rl }}')">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-clipboard text-white small"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">Copier le code</div>
                                    <small class="text-muted">Code : {{ $rapport->code_rl }}</small>
                                </div>
                            </div>
                        </button>
                        <form action="{{ route('laboratoires.admin.rapports.destroy', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                              method="POST" class="list-group-item list-group-item-action border-0 p-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none w-100 text-start py-3"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger rounded-circle p-2 me-3">
                                        <i class="bi bi-trash text-white small"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Supprimer</div>
                                        <small class="text-muted">Supprimer définitivement</small>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="card border-0 bg-white shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">Informations Techniques</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Chemin du fichier</label>
                        <div class="small text-muted font-monospace">{{ $rapport->path_rl }}</div>
                    </div>
                    @if(file_exists(storage_path('app/' . $rapport->path_rl)))
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted">Taille du fichier</label>
                            <div>{{ number_format(filesize(storage_path('app/' . $rapport->path_rl)) / 1024, 2) }} KB</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted">Date de modification</label>
                            <div>{{ \Carbon\Carbon::createFromTimestamp(filemtime(storage_path('app/' . $rapport->path_rl)))->format('d/m/Y H:i') }}</div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Fichier manquant sur le serveur
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Afficher une notification de succès
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 p-3';
        toast.style.zIndex = '1050';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <strong class="me-auto">Succès</strong>
                    <button type="button" class="btn-close" onclick="this.closest('.toast').remove()"></button>
                </div>
                <div class="toast-body">
                    Code copié dans le presse-papiers : ${text}
                </div>
            </div>
        `;
        document.body.appendChild(toast);

        // Supprimer la notification après 3 secondes
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }).catch(function(err) {
        console.error('Erreur lors de la copie :', err);
    });
}
</script>

<style>
.card {
    border-radius: 12px;
    transition: all 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.fw-semibold {
    font-weight: 600;
}

.text-muted {
    color: #6c757d !important;
}

.font-monospace {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    background-color: #f8f9fa;
    padding: 4px 8px;
    border-radius: 4px;
}

.toast {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>
@endsection
