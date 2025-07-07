@extends('laboratoires.public.layout')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Statistiques d'Utilisation des Équipements</h1>
                    <p class="text-muted mb-0">{{ $laboratoire->label_labo }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour au Dashboard
                    </a>
                    <a href="{{ route('laboratoires.admin.reporting', $laboratoire->code_lab) }}" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-text"></i> Rapports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="periode" class="form-label">Période d'analyse</label>
                            <select class="form-select" id="periode" name="periode" onchange="this.form.submit()">
                                <option value="7" {{ $periode == 7 ? 'selected' : '' }}>7 derniers jours</option>
                                <option value="30" {{ $periode == 30 ? 'selected' : '' }}>30 derniers jours</option>
                                <option value="90" {{ $periode == 90 ? 'selected' : '' }}>3 derniers mois</option>
                                <option value="180" {{ $periode == 180 ? 'selected' : '' }}>6 derniers mois</option>
                                <option value="365" {{ $periode == 365 ? 'selected' : '' }}>1 an</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <!-- Graphique des réservations par période -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Évolution des Réservations ({{ $periode }} jours)</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartReservations" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique des équipements les plus utilisés -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Équipements Utilisés</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartTopEquipements" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="row mb-4">
        <!-- Équipements les plus populaires -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Équipements les Plus Utilisés</h6>
                </div>
                <div class="card-body">
                    @if($equipementsPopulaires->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Équipement</th>
                                        <th>Réservations</th>
                                        <th>Statut</th>
                                        <th>Taux d'utilisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipementsPopulaires as $equipement)
                                    <tr>
                                        <td>{{ $equipement->nom_equip }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $equipement->reservations_count }}</span>
                                        </td>
                                                                                <td>
                                            @switch($equipement->etat)
                                                @case('disponible')
                                                    <span class="badge bg-success">Disponible</span>
                                                    @break
                                                @case('en utilisation')
                                                    <span class="badge bg-info">En utilisation</span>
                                                    @break
                                                @case('en maintenance')
                                                    <span class="badge bg-warning">En maintenance</span>
                                                    @break
                                                @case('hors service')
                                                    <span class="badge bg-danger">Hors service</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @php
                                                $taux = $equipement->reservations_count > 0 ?
                                                    min(($equipement->reservations_count / max($equipementsPopulaires->first()->reservations_count, 1)) * 100, 100) : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar"
                                                     style="width: {{ $taux }}%"
                                                     aria-valuenow="{{ $taux }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    {{ number_format($taux, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Aucune réservation dans cette période</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Équipements sous-utilisés -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Équipements Sous-utilisés</h6>
                </div>
                <div class="card-body">
                    @if($equipementsSousUtilises->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Équipement</th>
                                        <th>Statut</th>
                                        <th>Dernière utilisation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipementsSousUtilises->take(10) as $equipement)
                                    <tr>
                                        <td>{{ $equipement->nom_equip }}</td>
                                        <td>
                                            @switch($equipement->etat)
                                                @case('disponible')
                                                    <span class="badge bg-success">Disponible</span>
                                                    @break
                                                @case('en utilisation')
                                                    <span class="badge bg-info">En utilisation</span>
                                                    @break
                                                @case('en maintenance')
                                                    <span class="badge bg-warning">En maintenance</span>
                                                    @break
                                                @case('hors service')
                                                    <span class="badge bg-danger">Hors service</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <span class="text-muted">Aucune réservation</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($equipementsSousUtilises->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">Et {{ $equipementsSousUtilises->count() - 10 }} autres équipements...</small>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center">Tous les équipements ont été utilisés récemment</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques d'utilisation par équipement -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistiques Détaillées par Équipement</h6>
                </div>
                <div class="card-body">
                    @if(count($statsUtilisation) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Équipement</th>
                                        <th>Nombre de réservations</th>
                                        <th>Total heures</th>
                                        <th>Taux de réussite</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statsUtilisation as $equipementId => $stats)
                                    @php
                                        $equipement = \App\Models\laboratoires\Equipements::find($equipementId);
                                    @endphp
                                    @if($equipement)
                                    <tr>
                                        <td>{{ $equipement->nom_equip }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $stats['nombre_reservations'] }}</span>
                                        </td>
                                        <td>{{ $stats['total_heures'] }} heures</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width: {{ $stats['taux_utilisation'] }}%"
                                                     aria-valuenow="{{ $stats['taux_utilisation'] }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    {{ number_format($stats['taux_utilisation'], 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Détails
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Aucune statistique disponible pour cette période</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des réservations par période
    const dates = @json($statsParPeriode->pluck('date'));
    const reservations = @json($statsParPeriode->pluck('total_reservations'));

    // Graphique des réservations par période
    const ctxReservations = document.getElementById('chartReservations');
    if (ctxReservations) {
        new Chart(ctxReservations, {
            type: 'line',
            data: {
                labels: dates.map(date => new Date(date).toLocaleDateString('fr-FR')),
                datasets: [{
                    label: 'Nombre de réservations',
                    data: reservations,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Données pour le graphique des équipements les plus utilisés
    const equipements = @json($equipementsPopulaires->take(5)->pluck('nom_equip'));
    const utilisations = @json($equipementsPopulaires->take(5)->pluck('reservations_count'));

    // Graphique des équipements les plus utilisés
    const ctxTopEquipements = document.getElementById('chartTopEquipements');
    if (ctxTopEquipements) {
        new Chart(ctxTopEquipements, {
            type: 'doughnut',
            data: {
                labels: equipements,
                datasets: [{
                    data: utilisations,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endsection
