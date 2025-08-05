@extends('sige_app.backend.template.backend')

@section('title', 'Créer une Année Scolaire')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('annees.index') }}" 
                   class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Créer une Année Scolaire</h1>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de l'année scolaire</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('annees.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="code_annee" class="form-label">
                                Code de l'année <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   id="code_annee" 
                                   name="code_annee" 
                                   value="{{ old('code_annee') }}"
                                   class="form-control @error('code_annee') is-invalid @enderror"
                                   placeholder="Ex: 2023">
                            @error('code_annee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="debut_annee" class="form-label">
                                    Date de début <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       id="debut_annee" 
                                       name="debut_annee" 
                                       value="{{ old('debut_annee') }}"
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
                                       value="{{ old('fin_annee') }}"
                                       class="form-control @error('fin_annee') is-invalid @enderror">
                                @error('fin_annee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('annees.index') }}" 
                               class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
