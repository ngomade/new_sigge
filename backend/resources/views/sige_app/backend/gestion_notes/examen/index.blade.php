@extends('sige_app.backend.template.backend')

@section('title', 'Gestion des Examens')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestion des Examens</h1>
                <a href="{{ route('examens.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvel Examen
                </a>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('examens.index') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="session" class="form-label">Session</label>
                                <select name="session" id="session" class="form-select">
                                    <option value="">Toutes les sessions</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->code_session }}" {{ request('session') == $session->code_session ? 'selected' : '' }}>
                                            {{ $session->label_session }} - {{ $session->anneeScolaire->code_annee ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="type_evaluation" class="form-label">Type d'Évaluation</label>
                                <select name="type_evaluation" id="type_evaluation" class="form-select">
                                    <option value="">Tous les types</option>
                                    @foreach($typesEvaluation as $key => $value)
                                        <option value="{{ $key }}" {{ request('type_evaluation') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="date_debut" class="form-label">Période</label>
                                <div class="input-group">
                                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                            <a href="{{ route('examens.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Examens</h5>
                            <p class="card-text display-6">{{ $stats['total_examens'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Ce Mois</h5>
                            <p class="card-text display-6">{{ $stats['examens_ce_mois'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Examens Actifs</h5>
                            <p class="card-text display-6">{{ $stats['examens_actifs'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Évaluations</h5>
                            <p class="card-text display-6">{{ $stats['total_evaluations'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des examens -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des Examens</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Session</th>
                                    <th>Année Scolaire</th>
                                    <th>Type</th>
                                    <th>Évaluations</th>
                                    <th>Étudiants</th>
                                    <th>EC Concernés</th>
                                    <th>Moyenne</th>
                                    <th>Taux Réussite</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($examens as $examen)
                                    <tr>
                                        <td>{{ $examen->sessionExamen->label_session ?? 'N/A' }}</td>
                                        <td>{{ $examen->sessionExamen->anneeScolaire->code_annee ?? 'N/A' }}</td>
                                        <td>{{ $examen->type_evaluation ?? 'N/A' }}</td>
                                        <td>{{ $examen->evaluations_count ?? 0 }}</td>
                                        <td>{{ $examen->evaluations->unique('code_user')->count() ?? 0 }}</td>
                                        <td>{{ $examen->evaluations->unique('code_ec')->count() ?? 0 }}</td>
                                        <td>{{ round($examen->evaluations->avg('note_eval') ?? 0, 2) }}</td>
                                        <td>
                                            @php
                                                $evaluations = $examen->evaluations;
                                                $tauxReussite = $evaluations->count() > 0 ? 
                                                    round(($evaluations->where('note_eval', '>=', 10)->count() / $evaluations->count()) * 100, 2) : 0;
                                            @endphp
                                            {{ $tauxReussite }}%
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('examens.show', $examen->code_examen) }}" 
                                                   class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('examens.edit', $examen->code_examen) }}" 
                                                   class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('examens.planifier', $examen->code_examen) }}" 
                                                   class="btn btn-sm btn-success" title="Planifier">
                                                    <i class="fas fa-calendar"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun examen trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $examens->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
