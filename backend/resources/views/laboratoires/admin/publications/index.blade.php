@extends('laboratoires.public.layout')



@section('title', 'Liste des publications')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class='bx bx-book'></i> Gestion des publications</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}">
                            <i class='bx bx-home'></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Publications</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour au dashboard
            </a>
            <a href="{{ route('labo.publications.create') }}" class="btn btn-success">
                <i class='bx bx-plus'></i> Ajouter une publication
            </a>
        </div>
    </div>

    @include('laboratoires.partials.alerts')

    <!-- Filtres de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('labo.publications.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ $request->search }}" placeholder="Titre, domaine, tags...">
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ $request->type == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="domaine" class="form-label">Domaine</label>
                    <input type="text" class="form-control" id="domaine" name="domaine"
                           value="{{ $request->domaine }}" placeholder="Domaine...">
                </div>
                <div class="col-md-2">
                    <label for="annee" class="form-label">Année</label>
                    <select class="form-select" id="annee" name="annee">
                        <option value="">Toutes les années</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee }}" {{ $request->annee == $annee ? 'selected' : '' }}>
                                {{ $annee }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-search'></i> Rechercher
                        </button>
                        <a href="{{ route('labo.publications.index') }}" class="btn btn-outline-secondary">
                            <i class='bx bx-refresh'></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ $stats['total'] }}</h4>
                    <p class="text-muted mb-0">Total Publications</p>
                </div>
            </div>
        </div>
        @foreach($stats['par_type'] as $type => $total)
        <div class="col-xl-2 col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="mb-1">{{ ucfirst($type) }}</h5>
                    <span class="badge bg-info fs-5">{{ $total }}</span>
                </div>
            </div>
        </div>
        @endforeach
        @if(count($stats['par_annee']))
        <div class="col-xl-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="mb-1">Par année</h6>
                    @foreach($stats['par_annee'] as $annee => $total)
                        <span class="badge bg-secondary mb-1">{{ $annee }} : {{ $total }}</span><br>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class='bx bx-list-ul'></i>
                Publications ({{ $publications->total() }} résultat{{ $publications->total() > 1 ? 's' : '' }})
            </h5>
            @if($request->filled('search') || $request->filled('type') || $request->filled('domaine') || $request->filled('annee'))
                <small class="text-muted">
                    Filtres actifs :
                    @if($request->search) <span class="badge bg-primary">{{ $request->search }}</span> @endif
                    @if($request->type) <span class="badge bg-info">{{ ucfirst($request->type) }}</span> @endif
                    @if($request->domaine) <span class="badge bg-warning">{{ $request->domaine }}</span> @endif
                    @if($request->annee) <span class="badge bg-success">{{ $request->annee }}</span> @endif
                </small>
            @endif
        </div>
        <div class="card-body">
            @if($publications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Domaine</th>
                                <th>Créateur</th>
                                <th>Rapport</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($publications as $publication)
                            <tr>
                                <td>{{ $publication->titre_publi }}</td>
                                <td>{{ ucfirst($publication->type_publi) }}</td>
                                <td>{{ $publication->domaine }}</td>
                                <td>
                                    @if($publication->createur)
                                        <span class="badge bg-info me-1">{{ ucfirst($publication->createur->type_pers_lab) }}</span>
                                        {{ $publication->createur->nom_complet }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($publication->rapport_path)
                                        <span class="badge bg-success">
                                            <i class='bx bx-file-pdf'></i> Disponible
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ strtoupper(pathinfo($publication->rapport_path, PATHINFO_EXTENSION)) }}</small>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class='bx bx-x'></i> Aucun
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $publication->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('labo.publications.show', $publication->code_publi) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class='bx bx-show'></i>
                                        </a>
@if($publication->rapport_path)
    <a href="{{ Storage::url($publication->rapport_path) }}"
       target="_blank"
       class="btn btn-sm btn-primary"
       title="Consulter le rapport"
       style="font-size: 1.2rem; color: #004085;">
        <i class='bx bx-file-pdf'></i>
    </a>
@endif
                                        <a href="{{ route('labo.publications.edit', $publication->code_publi) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class='bx bx-edit'></i>
                                        </a>
<form action="{{ route('labo.publications.destroy', $publication->code_publi) }}" method="POST" class="d-inline delete-requete-form" id="delete-form-{{ $publication->code_publi }}">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-sm btn-danger btn-delete-publication" title="Supprimer"
        data-code="{{ $publication->code_publi }}"
        data-title="{{ $publication->titre_publi }}">
        <i class='bx bx-trash'></i>
    </button>
</form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $publications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class='bx bx-book-open' style="font-size: 4rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">Aucune publication trouvée</h5>
                    @if($request->filled('search') || $request->filled('type') || $request->filled('domaine') || $request->filled('annee'))
                        <p class="text-muted">Essayez de modifier vos critères de recherche</p>
                        <a href="{{ route('labo.publications.index') }}" class="btn btn-outline-primary">
                            <i class='bx bx-refresh'></i> Réinitialiser les filtres
                        </a>
                    @else
                        <p class="text-muted">Commencez par ajouter votre première publication</p>
                        <a href="{{ route('labo.publications.create') }}" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Ajouter une publication
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Modal de confirmation de suppression de publication -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fas fa-trash me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Êtes-vous sûr de vouloir supprimer cette publication ?</h6>
                <div class="alert alert-light border">
                    <div class="mb-2">
                        <strong>Code_publication:</strong> <span id="publicationCodeToDelete"></span>
                    </div>
                    <div>
                        <strong>Titre_publication:</strong> <span id="publicationTitleToDelete"></span>
                    </div>
                </div>
                <p class="text-muted mb-0">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Cette action est irréversible. Toutes les données associées à cette publication seront définitivement supprimées.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Supprimer la publication
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var confirmDeleteModal = document.getElementById('confirmDeleteModal');
    var publicationCodeSpan = document.getElementById('publicationCodeToDelete');
    var publicationTitleSpan = document.getElementById('publicationTitleToDelete');
    var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    var formToSubmit = null;

    // Attach click event to all delete buttons
    document.querySelectorAll('.btn-delete-publication').forEach(function(button) {
        button.addEventListener('click', function() {
            var code = this.getAttribute('data-code');
            var title = this.getAttribute('data-title');
            publicationCodeSpan.textContent = code;
            publicationTitleSpan.textContent = title;
            formToSubmit = document.getElementById('delete-form-' + code);
            var modal = new bootstrap.Modal(confirmDeleteModal);
            modal.show();
        });
    });

    // Confirm delete button submits the form
    confirmDeleteBtn.addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });
});
</script>
@endsection
