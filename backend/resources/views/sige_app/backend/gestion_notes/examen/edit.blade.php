@extends('sige_app.backend.template.backend')

@section('title', 'Modifier un Examen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('examens.show', $examen->code_examen) }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Modifier un Examen</h1>
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
                    <h5 class="card-title mb-0">Informations de l'Examen</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('examens.update', $examen->code_examen) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="code_session" class="form-label">Session d'Examen <span class="text-danger">*</span></label>
                            <select name="code_session" id="code_session" class="form-select" required>
                                <option value="">Sélectionnez une session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->code_session }}" {{ (old('code_session', $examen->code_session) == $session->code_session) ? 'selected' : '' }}>
                                        {{ $session->label_session }} - {{ $session->anneeScolaire->code_annee ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="type_evaluation" class="form-label">Type d'Évaluation <span class="text-danger">*</span></label>
                            <select name="type_evaluation" id="type_evaluation" class="form-select" required>
                                <option value="">Sélectionnez un type</option>
                                @foreach($typesEvaluation as $key => $value)
                                    <option value="{{ $key }}" {{ (old('type_evaluation', $examen->type_evaluation) == $key) ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('examens.show', $examen->code_examen) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à Jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations actuelles -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations Actuelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Session d'Examen:</strong></td>
                                    <td>{{ $examen->sessionExamen->label_session ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Année Scolaire:</strong></td>
                                    <td>{{ $examen->sessionExamen->anneeScolaire->code_annee ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Type d'Évaluation:</strong></td>
                                    <td>{{ $examen->type_evaluation ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Statut:</strong></td>
                                    <td>
                                        @if($examen->sessionExamen->statut_session ?? false)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
