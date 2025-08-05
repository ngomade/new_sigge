@extends('sige_app.backend.template.backend')

@section('title', 'Détails de l\'Examen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('examens.index') }}" class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Détails de l'Examen</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('examens.edit', $examen->code_examen) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('examens.planifier', $examen->code_examen) }}" class="btn btn-success">
                        <i class="fas fa-calendar me-2"></i>Planifier
                    </a>
                    <a href="{{ route('examens.exportPlanning', ['code_examen' => $examen->code_examen, 'format' => 'csv']) }}" class="btn btn-info">
                        <i class="fas fa-file-export me-2"></i>Exporter CSV
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
                                    <td><strong>Session d'Examen:</strong></td>
                                    <td>{{ $examen->sessionExamen->label_session ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Année Scolaire:</strong></td>
                                    <td>{{ $examen->sessionExamen->anneeScolaire->code_annee ?? 'N/A' }}</td>
                                </tr>
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

                <!-- Statistiques -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Statistiques</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Évaluations</h6>
                                            <p class="card-text display-6">{{ $stats['total_evaluations'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Étudiants</h6>
                                            <p class="card-text display-6">{{ $stats['etudiants_evalues'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">EC Concernés</h6>
                                            <p class="card-text display-6">{{ $stats['ecs_concernes'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Moyenne Générale</h6>
                                            <p class="card-text display-6">{{ $stats['moyenne_generale'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Taux de réussite -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Taux de Réussite</h5>
                </div>
                <div class="card-body">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $stats['taux_reussite'] }}%" 
                             aria-valuenow="{{ $stats['taux_reussite'] }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            {{ $stats['taux_reussite'] }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition par EC -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Répartition par Élément Constitutif</h5>
                </div>
                <div class="card-body">
                    @if($repartitionEc->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Élément Constitutif</th>
                                        <th>UE</th>
                                        <th>Semestre</th>
                                        <th>Nombre d'Évaluations</th>
                                        <th>Moyenne</th>
                                        <th>Taux Réussite</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($repartitionEc as $ecData)
                                        <tr>
                                            <td>{{ $ecData['ec']->intitule_ec ?? 'N/A' }}</td>
                                            <td>{{ $ecData['ec']->ue->intitule_ue ?? 'N/A' }}</td>
                                            <td>{{ $ecData['ec']->ue->semestre->label_sem ?? 'N/A' }}</td>
                                            <td>{{ $ecData['count'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $ecData['moyenne'] >= 10 ? 'success' : 'danger' }}">
                                                    {{ $ecData['moyenne'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $ecData['taux_reussite'] >= 50 ? 'success' : 'danger' }}">
                                                    {{ $ecData['taux_reussite'] }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center mb-0">Aucune donnée disponible.</p>
                    @endif
                </div>
            </div>

            <!-- Périodes planifiées -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Périodes Planifiées</h5>
                </div>
                <div class="card-body">
                    @if($examen->periodes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Salle</th>
                                        <th>Élément Constitutif</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Jour</th>
                                        <th>Durée (min)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($examen->periodes as $periode)
                                        <tr>
                                            <td>{{ $periode->salle->code_salle ?? 'N/A' }}</td>
                                            <td>{{ $periode->ec->intitule_ec ?? 'N/A' }}</td>
                                            <td>{{ $periode->debut_periode ? \Carbon\Carbon::parse($periode->debut_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>{{ $periode->fin_periode ? \Carbon\Carbon::parse($periode->fin_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>{{ $periode->jour_periode ?? 'N/A' }}</td>
                                            <td>{{ $periode->duree_periode ?? 'N/A' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('periodes.show', [$periode->code_salle, $periode->code_ec]) }}" 
                                                       class="btn btn-sm btn-info" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center mb-0">Aucune période planifiée pour cet examen.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
