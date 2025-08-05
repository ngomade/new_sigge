@extends('sige_app.backend.template.backend')

@section('title', 'Détails du Niveau')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('niveaux.index') }}" 
                       class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Détails du Niveau</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('niveaux.edit', $niveau->code_niveau) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <form method="POST" action="{{ route('niveaux.destroy', $niveau->code_niveau) }}" 
                          class="d-inline" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce niveau ?')">
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
                            <label class="form-label">Code Niveau</label>
                            <p class="form-control-plaintext h5">{{ $niveau->code_niveau }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Libellé</label>
                            <p class="form-control-plaintext">{{ $niveau->label_niveau }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Classe</label>
                            <p class="form-control-plaintext">
                                {{ $niveau->classe->label_class ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Semestres associés -->
            @if($niveau->semestres->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Semestres associés</h5>
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
                                @foreach($niveau->semestres as $semestre)
                                <tr>
                                    <td>{{ $semestre->code_sem }}</td>
                                    <td>{{ $semestre->label_sem }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Section pour des informations supplémentaires si nécessaire -->
            @if(isset($niveau->created_at) || isset($niveau->updated_at))
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations système</h5>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        @if(isset($niveau->created_at))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Créé le</label>
                            <p class="form-control-plaintext">{{ $niveau->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif

                        @if(isset($niveau->updated_at))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modifié le</label>
                            <p class="form-control-plaintext">{{ $niveau->updated_at->format('d/m/Y à H:i') }}</p>
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
