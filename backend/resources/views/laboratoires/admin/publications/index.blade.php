@extends('laboratoires.public.layout')

@section('title', 'Liste des publications')

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
            <div>
                <h2><i class='bx bx-book'></i> Gestion des publications</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @if($userRole === 'admin')
                    <li class="breadcrumb-item">
                        <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}">
                            <i class='bx bx-home'></i> Dashboard
                        </a>
                    </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">Publications</li>
                </ol>
            </nav>
        </div>
            @if($userRole === 'admin' || $userRole === 'chef_projet')
                <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}"
                   class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour au dashboard
        </a>
        @endif
            <a href="{{ route('laboratoires.admin.publications.create', $laboratoire->code_lab) }}"
               class="btn btn-success">
            <i class='bx bx-plus'></i> Ajouter une publication
        </a>
    </div>
    @include('laboratoires.partials.alerts')
    <!-- Filtres de recherche -->
    <div class="card mb-4">
        <div class="card-body">
                <form method="GET" action="{{ route('laboratoires.admin.publications.index', $laboratoire->code_lab) }}"
                      class="row g-3">
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
                            <a href="{{ route('laboratoires.admin.publications.index', $laboratoire->code_lab) }}"
                               class="btn btn-outline-secondary">
                            <i class='bx bx-refresh'></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
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
                        @if($request->search)
                            <span class="badge bg-primary">{{ $request->search }}</span>
                        @endif
                        @if($request->type)
                            <span class="badge bg-info">{{ ucfirst($request->type) }}</span>
                        @endif
                        @if($request->domaine)
                            <span class="badge bg-warning">{{ $request->domaine }}</span>
                        @endif
                        @if($request->annee)
                            <span class="badge bg-success">{{ $request->annee }}</span>
                        @endif
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
                                            <span
                                                class="badge bg-info me-1">{{ ucfirst($publication->createur->type_pers_lab) }}</span>
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
                                            <small
                                                class="text-muted">{{ strtoupper(pathinfo($publication->rapport_path, PATHINFO_EXTENSION)) }}</small>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class='bx bx-x'></i> Aucun
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $publication->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                            <a href="{{ route('laboratoires.admin.publications.show', [$laboratoire->code_lab, $publication->code_publi]) }}"
                                               class="btn btn-sm btn-info" title="Voir">
                                            <i class='bx bx-show'></i>
                                        </a>
                                            @if($publication->id_pers_lab == $userId)
                                                <a href="{{ route('laboratoires.admin.publications.edit', [$laboratoire->code_lab, $publication->code_publi]) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Modifier">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <form
                                                    action="{{ route('laboratoires.admin.publications.destroy', [$laboratoire->code_lab, $publication->code_publi]) }}"
                                                    method="POST" class="d-inline delete-requete-form"
                                                    id="delete-form-{{ $publication->code_publi }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger btn-delete-publication"
                                                            title="Supprimer"
                                                            data-code="{{ $publication->code_publi }}"
                                                            data-title="{{ $publication->titre_publi }}">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </form>
                                            @endif
@if($publication->rapport_path)
    <a href="{{ Storage::url($publication->rapport_path) }}"
       target="_blank"
       class="btn btn-sm btn-primary"
       title="Consulter le rapport"
       style="font-size: 1.2rem;">
        <i class='bx bx-file-pdf'></i>
    </a>
@endif
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
                            <a href="{{ route('laboratoires.admin.publications.index', $laboratoire->code_lab) }}"
                               class="btn btn-outline-primary">
                            <i class='bx bx-refresh'></i> Réinitialiser les filtres
                        </a>
                    @else
                            @if(!$publications->count())
                        <p class="text-muted">Commencez par ajouter votre première publication</p>
                                <a href="{{ route('laboratoires.admin.publications.create', $laboratoire->code_lab) }}"
                                   class="btn btn-primary">
                            <i class='bx bx-plus'></i> Ajouter une publication
                        </a>
                    @endif
            @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-publication').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            const titre = btn.getAttribute('data-title') || 'cette publication';
            if (confirm('Voulez-vous vraiment supprimer « ' + titre + ' » ?')) {
                btn.closest('form').submit();
            }
        });
    });
});
</script>
@endpush
