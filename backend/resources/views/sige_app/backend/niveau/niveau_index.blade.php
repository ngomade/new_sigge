@extends('sige_app.backend.template.backend')

@section('title', 'Niveaux')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Niveaux</h1>
        <a href="{{ route('niveaux.create') }}" 
           class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau Niveau
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

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Liste des Niveaux</h5>
        </div>
        <div class="card-body">
            @if($niveaux->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Libellé</th>
                                <th>Classe</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($niveaux as $niveau)
                                <tr>
                                    <td>{{ $niveau->code_niveau }}</td>
                                    <td>{{ $niveau->label_niveau }}</td>
                                    <td>{{ $niveau->classe->label_class ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('niveaux.show', $niveau->code_niveau) }}" 
                                           class="btn btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('niveaux.edit', $niveau->code_niveau) }}" 
                                           class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('niveaux.destroy', $niveau->code_niveau) }}" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce niveau ?')">
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
                    <p class="text-muted">Aucun niveau trouvé.</p>
                    <a href="{{ route('niveaux.create') }}" 
                       class="btn btn-primary mt-2">
                        Créer le premier niveau
                    </a>
                </div>
            @endif
        </div>
        @if($niveaux->hasPages())
            <div class="card-footer">
                {{ $niveaux->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
