@extends('sige_app.backend.template.backend')

@section('title', 'Gestion des Évaluations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestion des Évaluations</h1>
                <a href="{{ route('evaluations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle Évaluation
                </a>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('evaluations.index') }}">
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
                                <label for="ec" class="form-label">Élément Constitutif</label>
                                <select name="ec" id="ec" class="form-select">
                                    <option value="">Tous les EC</option>
                                    @foreach($ecs as $ec)
                                        <option value="{{ $ec->code_ec }}" {{ request('ec') == $ec->code_ec ? 'selected' : '' }}>
                                            {{ $ec->intitule_ec }} ({{ $ec->ue->intitule_ue ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="etudiant" class="form-label">Étudiant</label>
                                <select name="etudiant" id="etudiant" class="form-select">
                                    <option value="">Tous les étudiants</option>
                                    @foreach($etudiants as $etudiant)
                                        <option value="{{ $etudiant->code_user }}" {{ request('etudiant') == $etudiant->code_user ? 'selected' : '' }}>
                                            {{ $etudiant->name }}
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
                            <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">
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
                            <h5 class="card-title">Total Évaluations</h5>
                            <p class="card-text display-6">{{ $stats['total_evaluations'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Ce Mois</h5>
                            <p class="card-text display-6">{{ $stats['evaluations_ce_mois'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Moyenne Générale</h5>
                            <p class="card-text display-6">{{ $stats['moyenne_generale'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Taux Réussite</h5>
                            <p class="card-text display-6">{{ $stats['taux_reussite'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des évaluations -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des Évaluations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Étudiant</th>
                                    <th>EC</th>
                                    <th>UE</th>
                                    <th>Semestre</th>
                                    <th>Session</th>
                                    <th>Type</th>
                                    <th>Note</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluations as $evaluation)
                                    <tr>
                                        <td>{{ $evaluation->date_evaluation ? \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $evaluation->user->name ?? 'N/A' }}</td>
                                        <td>{{ $evaluation->ec->intitule_ec ?? 'N/A' }}</td>
                                        <td>{{ $evaluation->ec->ue->intitule_ue ?? 'N/A' }}</td>
                                        <td>{{ $evaluation->ec->ue->semestre->label_sem ?? 'N/A' }}</td>
                                        <td>{{ $evaluation->examen->sessionExamen->label_session ?? 'N/A' }}</td>
                                        <td>{{ $evaluation->examen->type_evaluation ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $evaluation->note_eval >= 10 ? 'success' : 'danger' }}">
                                                {{ $evaluation->note_eval }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('evaluations.show', [$evaluation->code_ec, $evaluation->code_examen, $evaluation->code_user]) }}" 
                                                   class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('evaluations.edit', [$evaluation->code_ec, $evaluation->code_examen]) }}" 
                                                   class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucune évaluation trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $evaluations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
