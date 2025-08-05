@extends('sige_app.backend.template.backend')

@section('title', 'Détails de la Classe')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('classes.index') }}" 
                       class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Détails de la Classe</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('classes.edit', $classe->code_class) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <form method="POST" action="{{ route('classes.destroy', $classe->code_class) }}" 
                          class="d-inline" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
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
                    <h5 class="card-title mb-0">Informations générales</h5>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code Classe</label>
                            <p class="form-control-plaintext h5">{{ $classe->code_class }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Libellé</label>
                            <p class="form-control-plaintext">{{ $classe->label_class }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Utilisateur</label>
                            <p class="form-control-plaintext">
                                {{ $classe->user->name ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre de niveaux</label>
                            <p class="form-control-plaintext">
                                {{ $stats['total_niveaux'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Niveaux associés -->
            @if($classe->niveaux->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Niveaux associés</h5>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Libellé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classe->niveaux as $niveau)
                                <tr>
                                    <td>{{ $niveau->code_niveau }}</td>
                                    <td>{{ $niveau->label_niveau }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Section pour des informations supplémentaires si nécessaire -->
            @if(isset($classe->created_at) || isset($classe->updated_at))
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations système</h5>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        @if(isset($classe->created_at))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Créé le</label>
                            <p class="form-control-plaintext">{{ $classe->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif

                        @if(isset($classe->updated_at))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modifié le</label>
                            <p class="form-control-plaintext">{{ $classe->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
