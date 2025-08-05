@extends('sige_app.backend.template.backend')

@section('title', 'Détails de l\'Évaluation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('evaluations.index') }}" class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Détails de l'Évaluation</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('evaluations.edit', [$evaluation->code_ec, $evaluation->code_examen]) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Informations générales -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations Générales</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Étudiant:</strong></td>
                                    <td>{{ $evaluation->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Élément Constitutif:</strong></td>
                                    <td>{{ $evaluation->ec->intitule_ec ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unité d'Enseignement:</strong></td>
                                    <td>{{ $evaluation->ec->ue->intitule_ue ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Semestre:</strong></td>
                                    <td>{{ $evaluation->ec->ue->semestre->label_sem ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Session d'Examen:</strong></td>
                                    <td>{{ $evaluation->examen->sessionExamen->label_session ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Année Scolaire:</strong></td>
                                    <td>{{ $evaluation->examen->sessionExamen->anneeScolaire->code_annee ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type d'Évaluation:</strong></td>
                                    <td>{{ $evaluation->examen->type_evaluation ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Détails de l'évaluation -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Détails de l'Évaluation</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Date d'Évaluation:</strong></td>
                                    <td>{{ $evaluation->date_evaluation ? \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date de Saisie:</strong></td>
                                    <td>{{ $evaluation->date_evalu ? \Carbon\Carbon::parse($evaluation->date_evalu)->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Note:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $evaluation->note_eval >= 10 ? 'success' : 'danger' }} fs-5">
                                            {{ $evaluation->note_eval }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Statut:</strong></td>
                                    <td>
                                        @if($evaluation->note_eval >= 10)
                                            <span class="badge bg-success">Validé</span>
                                        @else
                                            <span class="badge bg-danger">Non Validé</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Observations:</strong></td>
                                    <td>{{ $evaluation->code_ano ?? 'Aucune' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Autres évaluations de l'étudiant pour cet EC -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Autres Évaluations pour cet Élément Constitutif</h5>
                </div>
                <div class="card-body">
                    @if($autresEvaluations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Session</th>
                                        <th>Type</th>
                                        <th>Note</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($autresEvaluations as $autreEvaluation)
                                        <tr>
                                            <td>{{ $autreEvaluation->date_evaluation ? \Carbon\Carbon::parse($autreEvaluation->date_evaluation)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $autreEvaluation->examen->sessionExamen->label_session ?? 'N/A' }}</td>
                                            <td>{{ $autreEvaluation->examen->type_evaluation ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $autreEvaluation->note_eval >= 10 ? 'success' : 'danger' }}">
                                                    {{ $autreEvaluation->note_eval }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($autreEvaluation->note_eval >= 10)
                                                    <span class="badge bg-success">Validé</span>
                                                @else
                                                    <span class="badge bg-danger">Non Validé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('evaluations.show', [$autreEvaluation->code_ec, $autreEvaluation->code_examen, $autreEvaluation->code_user]) }}" 
                                                   class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center mb-0">Aucune autre évaluation trouvée pour cet élément constitutif.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
