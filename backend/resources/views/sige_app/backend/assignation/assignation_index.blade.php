@extends('sige_app.backend.template.backend')

@section('title', 'Assignations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Assignations</h1>
        <a href="{{ route('assignations.create') }}" 
           class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle Assignation
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('assignations.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="classe" class="form-label">Classe</label>
                        <select id="classe" name="classe" class="form-select">
                            <option value="">Toutes les classes</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->code_class }}" {{ request('classe') == $classe->code_class ? 'selected' : '' }}>
                                    {{ $classe->label_class }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="personnel" class="form-label">Enseignant</label>
                        <select id="personnel" name="personnel" class="form-select">
                            <option value="">Tous les enseignants</option>
                            @foreach($personnels as $personnel)
                                <option value="{{ $personnel->code_pers }}" {{ request('personnel') == $personnel->code_pers ? 'selected' : '' }}>
                                    {{ $personnel->nom_pers }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="ec" class="form-label">Élément Constitutif</label>
                        <select id="ec" name="ec" class="form-select">
                            <option value="">Tous les ECs</option>
                            @foreach($ecs as $ec)
                                <option value="{{ $ec->code_ec }}" {{ request('ec') == $ec->code_ec ? 'selected' : '' }}>
                                    {{ $ec->intitule_ec }} ({{ $ec->ue->intitule_ue ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <div class="btn-group" role="group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                            <a href="{{ route('assignations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Effacer
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Liste des Assignations</h5>
        </div>
        <div class="card-body">
            @if($assignations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Classe</th>
                                <th>Enseignant</th>
                                <th>Élément Constitutif</th>
                                <th>UE</th>
                                <th>Semestre</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignations as $assignation)
                                <tr>
                                    <td>{{ $assignation->classe->label_class ?? 'N/A' }}</td>
                                    <td>{{ $assignation->personnel->nom_pers ?? 'N/A' }}</td>
                                    <td>{{ $assignation->ec->intitule_ec ?? 'N/A' }}</td>
                                    <td>{{ $assignation->ec->ue->intitule_ue ?? 'N/A' }}</td>
                                    <td>{{ $assignation->ec->ue->semestre->label_sem ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('assignations.show', $assignation->code_ass) }}" 
                                           class="btn btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('assignations.edit', $assignation->code_ass) }}" 
                                           class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('assignations.destroy', $assignation->code_ass) }}" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette assignation ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">Aucune assignation trouvée.</p>
                    <a href="{{ route('assignations.create') }}" 
                       class="btn btn-primary mt-2">
                        Créer la première assignation
                    </a>
                </div>
            @endif
        </div>
        @if($assignations->hasPages())
            <div class="card-footer">
                {{ $assignations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
