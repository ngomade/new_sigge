@extends('sige_app.backend.template.backend')

@section('title', 'Détails de l\'Année Scolaire')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('annees.index') }}" 
                       class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Détails de l'Année Scolaire</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('annees.edit', $annee->code_annee) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <form method="POST" action="{{ route('annees.destroy', $annee->code_annee) }}" 
                          class="d-inline" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette année scolaire ?')">
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
                            <label class="form-label">Code Année</label>
                            <p class="form-control-plaintext h5">{{ $annee->code_annee }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de début</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar-alt me-2 text-secondary"></i>
                                {{ \Carbon\Carbon::parse($annee->debut_annee)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de fin</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar-alt me-2 text-secondary"></i>
                                {{ \Carbon\Carbon::parse($annee->fin_annee)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Durée</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-clock me-2 text-secondary"></i>
                                {{ \Carbon\Carbon::parse($annee->debut_annee)->diffInDays(\Carbon\Carbon::parse($annee->fin_annee)) + 1 }} jours
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section pour des informations supplémentaires si nécessaire -->
            @if(isset($annee->created_at) || isset($annee->updated_at))
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations système</h5>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        @if(isset($annee->created_at))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Créé le</label>
                            <p class="form-control-plaintext">{{ $annee->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif

                        @if(isset($annee->updated_at))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modifié le</label>
                            <p class="form-control-plaintext">{{ $annee->updated_at->format('d/m/Y à H:i') }}</p>
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
