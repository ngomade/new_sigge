@extends('sige_app.backend.template.backend')

@section('title', 'Classes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Classes</h1>
        <a href="{{ route('classes.create') }}" 
           class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle Classe
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

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('classes.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               class="form-control" 
                               placeholder="Rechercher par libellé">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="user" class="form-label">Utilisateur</label>
                        <select id="user" name="user" class="form-select">
                            <option value="">Tous les utilisateurs</option>
                            @foreach($users as $user)
                                <option value="{{ $user->code_user }}" {{ request('user') == $user->code_user ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="btn-group" role="group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                            <a href="{{ route('classes.index') }}" class="btn btn-secondary">
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
            <h5 class="card-title mb-0">Liste des Classes</h5>
        </div>
        <div class="card-body">
            @if($classes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Libellé</th>
                                <th>Utilisateur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classes as $classe)
                                <tr>
                                    <td>{{ $classe->code_class }}</td>
                                    <td>{{ $classe->label_class }}</td>
                                    <td>{{ $classe->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('classes.show', $classe->code_class) }}" 
                                           class="btn btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('classes.edit', $classe->code_class) }}" 
                                           class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('classes.destroy', $classe->code_class) }}" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')">
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
                    <p class="text-muted">Aucune classe trouvée.</p>
                    <a href="{{ route('classes.create') }}" 
                       class="btn btn-primary mt-2">
                        Créer la première classe
                    </a>
                </div>
            @endif
        </div>
        @if($classes->hasPages())
            <div class="card-footer">
                {{ $classes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
