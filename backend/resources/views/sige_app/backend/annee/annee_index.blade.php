@extends('sige_app.backend.template.backend')

@section('title', 'Années Scolaires')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Années Scolaires</h1>
        <a href="{{ route('annees.create') }}" 
           class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle Année
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
            <h5 class="card-title mb-0">Liste des Années Scolaires</h5>
        </div>
        <div class="card-body">
            @if($annees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($annees as $annee)
                                <tr>
                                    <td>{{ $annee->code_annee }}</td>
                                    <td>{{ \Carbon\Carbon::parse($annee->debut_annee)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($annee->fin_annee)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('annees.show', $annee->code_annee) }}" 
                                           class="btn btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('annees.edit', $annee->code_annee) }}" 
                                           class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('annees.destroy', $annee->code_annee) }}" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette année scolaire ?')">
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
                    <p class="text-muted">Aucune année scolaire trouvée.</p>
                    <a href="{{ route('annees.create') }}" 
                       class="btn btn-primary mt-2">
                        Créer la première année scolaire
                    </a>
                </div>
            @endif
        </div>
        @if($annees->hasPages())
            <div class="card-footer">
                {{ $annees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
