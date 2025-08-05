@extends('sige_app.backend.template.backend')

@section('title', 'Détails de l\'Assignation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('assignations.index') }}" 
                       class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Détails de l'Assignation</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('assignations.edit', $assignation->code_ass) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <form method="POST" action="{{ route('assignations.destroy', $assignation->code_ass) }}" 
                          class="d-inline" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette assignation ?')">
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
                            <label class="form-label">Classe</label>
                            <p class="form-control-plaintext">{{ $assignation->classe->label_class ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Enseignant</label>
                            <p class="form-control-plaintext">{{ $assignation->personnel->nom_pers ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Élément Constitutif</label>
                            <p class="form-control-plaintext">{{ $assignation->ec->intitule_ec ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">UE</label>
                            <p class="form-control-plaintext">{{ $assignation->ec->ue->intitule_ue ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semestre</label>
                            <p class="form-control-plaintext">{{ $assignation->ec->ue->semestre->label_sem ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistiques</h5>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Évaluations</h5>
                                    <p class="card-text display-6">{{ $stats['total_evaluations'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Évaluations récentes</h5>
                                    <p class="card-text display-6">{{ $stats['evaluations_recentes'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Moyenne générale</h5>
                                    <p class="card-text display-6">{{ number_format($stats['moyenne_generale'], 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Examens associés -->
            @if($assignation->ec->evaluations->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Examens associés</h5>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Session</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignation->ec->evaluations as $evaluation)
                                    @if($evaluation->examen)
                                    <tr>
                                        <td>{{ $evaluation->examen->sessionExamen->label_sess ?? 'N/A' }}</td>
                                        <td>{{ $evaluation->examen->date_examen ? \Carbon\Carbon::parse($evaluation->examen->date_examen)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $evaluation->examen->type_examen ?? 'N/A' }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Section pour des informations supplémentaires si nécessaire -->
            @if(isset($assignation->created_at) || isset($assignation->updated_at))
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations système</h5>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        @if(isset($assignation->created_at))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Créé le</label>
                            <p class="form-control-plaintext">{{ $assignation->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif

                        @if(isset($assignation->updated_at))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modifié le</label>
                            <p class="form-control-plaintext">{{ $assignation->updated_at->format('d/m/Y à H:i') }}</p>
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
