@extends('sige_app.backend.template.backend')

@section('title', 'Créer une Session d\'Examen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('sessionsExamen.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Créer une Session d'Examen</h1>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de la Session</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sessionsExamen.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="code_annee" class="form-label">Année Scolaire <span class="text-danger">*</span></label>
                            <select name="code_annee" id="code_annee" class="form-select" required>
                                <option value="">Sélectionnez une année</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->code_annee }}" {{ old('code_annee') == $annee->code_annee ? 'selected' : '' }}>
                                        {{ $annee->code_annee }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="label_session" class="form-label">Libellé de la Session <span class="text-danger">*</span></label>
                            <input type="text" name="label_session" id="label_session" class="form-control" 
                                   value="{{ old('label_session') }}" required maxlength="128">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut_session" class="form-label">Date Début <span class="text-danger">*</span></label>
                                <input type="date" name="date_debut_session" id="date_debut_session" class="form-control" 
                                       value="{{ old('date_debut_session') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_fin_session" class="form-label">Date Fin</label>
                                <input type="date" name="date_fin_session" id="date_fin_session" class="form-control" 
                                       value="{{ old('date_fin_session') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="statut_session" class="form-label">Statut <span class="text-danger">*</span></label>
                            <select name="statut_session" id="statut_session" class="form-select" required>
                                <option value="0" {{ old('statut_session') == 0 ? 'selected' : '' }}>Inactive</option>
                                <option value="1" {{ old('statut_session') == 1 ? 'selected' : '' }}>Active</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('sessionsExamen.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
