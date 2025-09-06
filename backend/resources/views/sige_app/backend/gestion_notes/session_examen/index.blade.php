@extends('sige_app.backend.template.backend')

@section('title', 'Gestion des Sessions d\'Examen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestion des Sessions d'Examen</h1>
                <a href="{{ route('sessionsExamen.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle Session
                </a>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Sessions</h5>
                            <p class="card-text display-6">{{ $stats['total_sessions'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Sessions Actives</h5>
                            <p class="card-text display-6">{{ $stats['sessions_actives'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Sessions en Cours</h5>
                            <p class="card-text display-6">{{ $stats['sessions_en_cours'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Examens</h5>
                            <p class="card-text display-6">{{ $stats['examens_total'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des sessions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des Sessions d'Examen</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Libellé</th>
                                    <th>Année Scolaire</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                    <th>Statut</th>
                                    <th>Examens</th>
                                    <th>Évaluations</th>
                                    <th>Moyenne</th>
                                    <th>Taux Réussite</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                    <tr>
                                        <td>{{ $session->label_session }}</td>
                                        <td>{{ $session->anneeScolaire->code_annee ?? 'N/A' }}</td>
                                        <td>{{ $session->date_debut_session ? \Carbon\Carbon::parse($session->date_debut_session)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $session->date_fin_session ? \Carbon\Carbon::parse($session->date_fin_session)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @if($session->statut_session)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $session->examens->count() }}</td>
                                        <td>{{ $session->examens->sum(function ($examen) { return $examen->evaluations->count(); }) }}</td>
                                        <td>{{ round($session->examens->flatMap->evaluations->avg('note_eval') ?? 0, 2) }}</td>
                                        <td>
                                            @php
                                                $evaluations = $session->examens->flatMap->evaluations;
                                                $tauxReussite = $evaluations->count() > 0 ? 
                                                    round(($evaluations->where('note_eval', '>=', 10)->count() / $evaluations->count()) * 100, 2) : 0;
                                            @endphp
                                            {{ $tauxReussite }}%
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('sessionsExamen.show', $session->code_session) }}" 
                                                   class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('sessionsExamen.edit', $session->code_session) }}" 
                                                   class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('sessionsExamen.toggleStatus', $session->code_session) }}" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir changer le statut de cette session ?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $session->statut_session ? 'btn-secondary' : 'btn-success' }}" 
                                                            title="{{ $session->statut_session ? 'Désactiver' : 'Activer' }}">
                                                        <i class="fas {{ $session->statut_session ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Aucune session d'examen trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $sessions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
