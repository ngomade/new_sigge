@extends('sige_app.backend.template.backend')

@section('title', 'Modifier l\'Année Scolaire')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('annees.show', $annee->code_annee) }}" 
                   class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Modifier l'Année Scolaire</h1>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Modification de l'année scolaire : <strong>{{ $annee->code_annee }}</strong></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('annees.update', $annee->code_annee) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="debut_annee" class="form-label">
                                    Date de début <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       id="debut_annee" 
                                       name="debut_annee" 
                                       value="{{ old('debut_annee', $annee->debut_annee) }}"
                                       class="form-control @error('debut_annee') is-invalid @enderror">
                                @error('debut_annee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fin_annee" class="form-label">
                                    Date de fin <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       id="fin_annee" 
                                       name="fin_annee" 
                                       value="{{ old('fin_annee', $annee->fin_annee) }}"
                                       class="form-control @error('fin_annee') is-invalid @enderror">
                                @error('fin_annee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('annees.show', $annee->code_annee) }}" 
                               class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations actuelles -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations actuelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Dates actuelles:</strong></p>
                            <p>Du {{ \Carbon\Carbon::parse($annee->debut_annee)->format('d/m/Y') }} 
                               au {{ \Carbon\Carbon::parse($annee->fin_annee)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
