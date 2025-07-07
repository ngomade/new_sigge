@extends('laboratoires.public.layout')

@section('title', 'Analytics - ' . $laboratoire->nom_labo)

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header moderne style Google Analytics -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-semibold text-dark">Analytics</h1>
            <p class="text-muted mb-0">{{ $laboratoire->label_labo }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
            <a href="{{ route('laboratoires.admin.reporting', $laboratoire->code_lab) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-download me-1"></i>Exporter
            </a>
        </div>
    </div>

    <!-- Métriques principales style Stripe -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-people text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 fw-medium">Membres Actifs</h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['membres'] ?? 0) }}</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i>+12% ce mois
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-folder text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 fw-medium">Projets Actifs</h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['projets'] ?? 0) }}</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i>+8% ce mois
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-tools text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 fw-medium">Équipements</h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['equipements'] ?? 0) }}</h3>
                            <small class="text-muted">
                                <i class="bi bi-dash me-1"></i>Stable
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-person-plus text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 fw-medium">Candidatures</h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['candidatures'] ?? 0) }}</h3>
                            <small class="text-warning">
                                <i class="bi bi-clock me-1"></i>En attente
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques style GitHub Insights -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card border-0 bg-white shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold text-dark">Activité des Projets</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active">7j</button>
                            <button type="button" class="btn btn-outline-secondary">30j</button>
                            <button type="button" class="btn btn-outline-secondary">90j</button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 mb-1 fw-bold text-success">{{ $projetsStats['en_cours'] ?? 0 }}</div>
                                <small class="text-muted">En cours</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 mb-1 fw-bold text-primary">{{ $projetsStats['termines'] ?? 0 }}</div>
                                <small class="text-muted">Terminés</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 mb-1 fw-bold text-warning">{{ $projetsStats['en_attente'] ?? 0 }}</div>
                                <small class="text-muted">En attente</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 mb-1 fw-bold text-danger">{{ $projetsStats['suspendus'] ?? 0 }}</div>
                                <small class="text-muted">Suspendus</small>
                            </div>
                        </div>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="chartProjets"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">État des Équipements</h5>
                </div>
                <div class="card-body p-4">
                    <div style="height: 200px;">
                        <canvas id="chartEquipements"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Disponibles</span>
                            <span class="fw-semibold">{{ $equipementsStats['disponibles'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">En utilisation</span>
                            <span class="fw-semibold">{{ $equipementsStats['en_utilisation'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">En maintenance</span>
                            <span class="fw-semibold">{{ $equipementsStats['en_maintenance'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Hors service</span>
                            <span class="fw-semibold">{{ $equipementsStats['hors_service'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activité récente style Slack -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">Activité Récente</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="bi bi-person-plus text-white small"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">Nouvelles candidatures</div>
                                    <small class="text-muted">{{ $activiteRecente['nouvelles_candidatures'] ?? 0 }} ce mois</small>
                                </div>
                                <span class="badge bg-warning rounded-pill">{{ $activiteRecente['nouvelles_candidatures'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-folder-plus text-white small"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">Nouveaux projets</div>
                                    <small class="text-muted">{{ $activiteRecente['nouveaux_projets'] ?? 0 }} ce mois</small>
                                </div>
                                <span class="badge bg-success rounded-pill">{{ $activiteRecente['nouveaux_projets'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-calendar-check text-white small"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">Nouvelles réservations</div>
                                    <small class="text-muted">{{ $activiteRecente['nouvelles_reservations'] ?? 0 }} ce mois</small>
                                </div>
                                <span class="badge bg-info rounded-pill">{{ $activiteRecente['nouvelles_reservations'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 bg-white shadow-sm h-100">
                <div class="card-header bg-transparent border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold text-dark">Projets Récents</h5>
                        <a href="{{ route('laboratoires.admin.projets', $laboratoire->code_lab) }}" class="btn btn-sm btn-outline-primary">
                            Voir tous
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(isset($projetsRecents) && $projetsRecents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 py-3 px-4 fw-medium">Projet</th>
                                        <th class="border-0 py-3 fw-medium">Statut</th>
                                        <th class="border-0 py-3 fw-medium">Date</th>
                                        <th class="border-0 py-3 text-center fw-medium">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projetsRecents as $projet)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-medium">{{ Str::limit($projet->theme_projet, 50) }}</div>
                                        </td>
                                        <td class="py-3">
                                            @switch($projet->statut_projet)
                                                @case('en_cours')
                                                    <span class="badge bg-success-subtle text-success">En cours</span>
                                                    @break
                                                @case('termine')
                                                    <span class="badge bg-primary-subtle text-primary">Terminé</span>
                                                    @break
                                                @case('en_attente')
                                                    <span class="badge bg-warning-subtle text-warning">En attente</span>
                                                    @break
                                                @case('suspendu')
                                                    <span class="badge bg-danger-subtle text-danger">Suspendu</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="py-3 text-muted">
                                            {{ \Carbon\Carbon::parse($projet->debut_projet)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 text-center">
                                            <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-folder-x text-muted fs-1 mb-3"></i>
                            <p class="text-muted">Aucun projet récent</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Équipements populaires style Notion -->
    <div class="card border-0 bg-white shadow-sm">
        <div class="card-header bg-transparent border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-dark">Équipements les Plus Utilisés</h5>
                <a href="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}" class="btn btn-sm btn-outline-primary">
                    Voir tous
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if(isset($equipementsPopulaires) && $equipementsPopulaires->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 py-3 px-4 fw-medium">Équipement</th>
                                <th class="border-0 py-3 fw-medium">Statut</th>
                                <th class="border-0 py-3 fw-medium">Utilisation</th>
                                <th class="border-0 py-3 text-center fw-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipementsPopulaires as $equipement)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-medium">{{ $equipement->nom_equip }}</div>
                                </td>
                                <td class="py-3">
                                    @switch($equipement->etat)
                                        @case('disponible')
                                            <span class="badge bg-success-subtle text-success">Disponible</span>
                                            @break
                                        @case('en utilisation')
                                            <span class="badge bg-info-subtle text-info">En utilisation</span>
                                            @break
                                        @case('en maintenance')
                                            <span class="badge bg-warning-subtle text-warning">En maintenance</span>
                                            @break
                                        @case('hors service')
                                            <span class="badge bg-danger-subtle text-danger">Hors service</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                            @php
                                                $maxReservations = $equipementsPopulaires->first()->reservations_count ?? 1;
                                                $currentReservations = $equipement->reservations_count ?? 0;
                                                $percentage = $maxReservations > 0 ? min(($currentReservations / $maxReservations) * 100, 100) : 0;
                                            @endphp
                                            <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="fw-medium small">{{ $equipement->reservations_count ?? 0 }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-tools text-muted fs-1 mb-3"></i>
                    <p class="text-muted">Aucun équipement avec des réservations</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Configuration globale style moderne
        Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#6c757d';
        Chart.defaults.plugins.legend.display = false;

        // Graphique des projets
        const ctxProjets = document.getElementById('chartProjets');
        if (ctxProjets) {
            new Chart(ctxProjets, {
                type: 'bar',
                data: {
                    labels: ['En cours', 'Terminés', 'En attente', 'Suspendus'],
                    datasets: [{
                        data: [
                            {{ $projetsStats['en_cours'] ?? 0 }},
                            {{ $projetsStats['termines'] ?? 0 }},
                            {{ $projetsStats['en_attente'] ?? 0 }},
                            {{ $projetsStats['suspendus'] ?? 0 }}
                        ],
                        backgroundColor: ['#28a745', '#007bff', '#ffc107', '#dc3545'],
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Graphique des équipements
        const ctxEquipements = document.getElementById('chartEquipements');
        if (ctxEquipements) {
            new Chart(ctxEquipements, {
                type: 'doughnut',
                data: {
                    labels: ['Disponibles', 'En utilisation', 'En maintenance', 'Hors service'],
                    datasets: [{
                        data: [
                            {{ $equipementsStats['disponibles'] ?? 0 }},
                            {{ $equipementsStats['en_utilisation'] ?? 0 }},
                            {{ $equipementsStats['en_maintenance'] ?? 0 }},
                            {{ $equipementsStats['hors_service'] ?? 0 }}
                        ],
                        backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    } catch (error) {
        console.error('Erreur lors du chargement des graphiques:', error);
    }
});
</script>

<style>
/* Style moderne inspiré des grandes plateformes */
.card {
    border-radius: 12px;
    transition: all 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.btn-outline-primary:hover {
    transform: translateY(-1px);
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

/* Animations subtiles */
.bg-opacity-10 {
    transition: all 0.2s ease;
}

.card:hover .bg-opacity-10 {
    transform: scale(1.05);
}

/* Typography moderne */
.fw-semibold {
    font-weight: 600;
}

.text-muted {
    color: #6c757d !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endsection
