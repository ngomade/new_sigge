@extends('laboratoires.public.layout')

@section('title', 'Rapports Personnalisés - ' . $laboratoire->nom_labo)

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header moderne -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-semibold text-dark">Rapports Personnalisés</h1>
            <p class="text-muted mb-0">{{ $laboratoire->label_labo }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laboratoires.admin.reporting', $laboratoire->code_lab) }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
            <a href="{{ route('laboratoires.admin.rapports.create', $laboratoire->code_lab) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Nouveau Rapport
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-file-text text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 fw-medium">Total Rapports</h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ $rapports->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-file-pdf text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 fw-medium">Rapports PDF</h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ $rapports->where('path_rl', 'like', '%.pdf')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-file-word text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 fw-medium">Rapports Word</h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ $rapports->where('path_rl', 'like', '%.docx')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar3 text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 fw-medium">Ce Mois</h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ $rapports->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rapports -->
    <div class="card border-0 bg-white shadow-sm">
        <div class="card-header bg-transparent border-0 py-3">
            <h5 class="mb-0 fw-semibold text-dark">Mes Rapports</h5>
        </div>
        <div class="card-body p-0">
            @if($rapports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 py-3 px-4 fw-medium">Code</th>
                                <th class="border-0 py-3 fw-medium">Type</th>
                                <th class="border-0 py-3 fw-medium">Description</th>
                                <th class="border-0 py-3 fw-medium">Date Création</th>
                                <th class="border-0 py-3 text-center fw-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rapports as $rapport)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-medium">{{ $rapport->code_rl }}</div>
                                </td>
                                <td class="py-3">
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
                                </td>
                                <td class="py-3">
                                    <div class="fw-medium">{{ Str::limit($rapport->desc_rapport ?? 'Aucune description', 50) }}</div>
                                </td>
                                <td class="py-3 text-muted">
                                    {{ \Carbon\Carbon::parse($rapport->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('laboratoires.admin.rapports.show', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                                           class="btn btn-outline-secondary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(file_exists(storage_path('app/' . $rapport->path_rl)))
                                            <a href="{{ route('laboratoires.admin.rapports.download', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                                               class="btn btn-outline-primary" title="Télécharger">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('laboratoires.admin.rapports.destroy', [$laboratoire->code_lab, $rapport->code_rl]) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($rapports->hasPages())
                    <div class="card-footer bg-transparent border-0 py-3">
                        {{ $rapports->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-text text-muted fs-1 mb-3"></i>
                    <h5 class="text-muted mb-2">Aucun rapport créé</h5>
                    <p class="text-muted mb-4">Commencez par créer votre premier rapport personnalisé</p>
                    <a href="{{ route('laboratoires.admin.rapports.create', $laboratoire->code_lab) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Créer un Rapport
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
    transition: all 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.btn-outline-primary:hover {
    transform: translateY(-1px);
}

.fw-semibold {
    font-weight: 600;
}

.text-muted {
    color: #6c757d !important;
}
</style>
@endsection
