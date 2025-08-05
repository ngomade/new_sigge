@extends('sige_app.backend.template.backend')

@section('title', 'Modifier les Évaluations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('evaluations.index') }}" class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Modifier les Évaluations</h1>
                </div>
                <a href="{{ route('evaluations.show', [$ec->code_ec, $examen->code_examen, $etudiants->first()->code_user ?? '']) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
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

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de l'Évaluation</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Élément Constitutif:</strong></td>
                                    <td>{{ $ec->intitule_ec }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unité d'Enseignement:</strong></td>
                                    <td>{{ $ec->ue->intitule_ue ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Semestre:</strong></td>
                                    <td>{{ $ec->ue->semestre->label_sem ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Examen:</strong></td>
                                    <td>{{ $examen->sessionExamen->label_session ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type d'Évaluation:</strong></td>
                                    <td>{{ $examen->type_evaluation }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notes des Étudiants</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('evaluations.update', [$ec->code_ec, $examen->code_examen]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="date_evaluation" class="form-label">Date d'Évaluation <span class="text-danger">*</span></label>
                            <input type="date" name="date_evaluation" id="date_evaluation" class="form-control" 
                                   value="{{ old('date_evaluation', $evaluations->first()->date_evaluation ?? now()->toDateString()) }}" required>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Note (0-20)</th>
                                        <th>Observations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiants as $etudiant)
                                        @php
                                            $evaluation = $evaluations[$etudiant->code_user] ?? null;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $etudiant->name }}
                                                <input type="hidden" name="evaluations[{{ $loop->index }}][code_user]" value="{{ $etudiant->code_user }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" max="20" 
                                                       name="evaluations[{{ $loop->index }}][note_eval]" 
                                                       class="form-control" 
                                                       value="{{ old('evaluations.'.$loop->index.'.note_eval', $evaluation->note_eval ?? '') }}">
                                            </td>
                                            <td>
                                                <input type="text" name="evaluations[{{ $loop->index }}][code_ano]" 
                                                       class="form-control" 
                                                       value="{{ old('evaluations.'.$loop->index.'.code_ano', $evaluation->code_ano ?? '') }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à Jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
