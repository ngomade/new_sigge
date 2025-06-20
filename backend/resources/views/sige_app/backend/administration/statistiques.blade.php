@extends('sige_app.backend.template.backend')
@section('title', 'Statistiques des Requêtes')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">📊 {{ $roleSpecificData['title'] ?? 'Statistiques des Requêtes' }}</h4>
                            <small class="text-muted">{{ $roleSpecificData['scope'] ?? '' }}</small>
                        </div>
                        <div>
                            @if (in_array('view_all', $roleSpecificData['permissions'] ?? []))
                                <span class="badge bg-success me-2">Accès Global</span>
                            @elseif(in_array('view_bureau', $roleSpecificData['permissions'] ?? []))
                                <span class="badge bg-info me-2">Accès Bureau</span>
                            @endif
                            <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                                🖨️ Imprimer
                            </button>
                            <a href="{{ route('admin.requetes.index') }}" class="btn btn-secondary btn-sm">
                                Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Statistiques générales -->
                        <div class="row mb-4">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-primary text-white h-100">
                                    <div class="card-body text-center">
                                        <h2 class="mb-0">{{ $totalRequetes }}</h2>
                                        <p class="mb-0">Total des requêtes</p>
                                        @if ($userRole === 'ADMIN' || $userRole === 'ADMIN_GENERAL')
                                            <small>Toute l'organisation</small>
                                        @else
                                            <small>Vos bureaux</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <div class="card bg-warning text-dark h-100">
                                    <div class="card-body text-center">
                                        <h2 class="mb-0">{{ $requetesEnAttente }}</h2>
                                        <p class="mb-0">En attente</p>
                                        <small>{{ $totalRequetes > 0 ? round(($requetesEnAttente / $totalRequetes) * 100, 1) : 0 }}%</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body text-center">
                                        <h2 class="mb-0">{{ $requetesEnCours }}</h2>
                                        <p class="mb-0">En cours</p>
                                        <small>{{ $totalRequetes > 0 ? round(($requetesEnCours / $totalRequetes) * 100, 1) : 0 }}%</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body text-center">
                                        <h2 class="mb-0">{{ $requetesTraitees }}</h2>
                                        <p class="mb-0">Traitées</p>
                                        <small>{{ $totalRequetes > 0 ? round(($requetesTraitees / $totalRequetes) * 100, 1) : 0 }}%</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body text-center">
                                        <h2 class="mb-0">{{ $requetesRejetees }}</h2>
                                        <p class="mb-0">Rejetées</p>
                                        <small>{{ $totalRequetes > 0 ? round(($requetesRejetees / $totalRequetes) * 100, 1) : 0 }}%</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Performance par bureau (pour les chefs de service) -->
                        @if (count($performanceParBureau) > 1 || in_array('view_all', $roleSpecificData['permissions'] ?? []))
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">🏢 Performance par Bureau</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Bureau</th>
                                                            <th>Total</th>
                                                            <th>En attente</th>
                                                            <th>En cours</th>
                                                            <th>Traitées</th>
                                                            <th>Rejetées</th>
                                                            <th>Temps moyen</th>
                                                            <th>Taux résolution</th>
                                                            <th>Performance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($performanceParBureau as $perf)
                                                            <tr>
                                                                <td><strong>{{ $perf['bureau']->label_bureau ?? 'N/A' }}</strong>
                                                                </td>
                                                                <td>{{ $perf['total'] }}</td>
                                                                <td><span
                                                                        class="badge bg-warning">{{ $perf['en_attente'] }}</span>
                                                                </td>
                                                                <td><span
                                                                        class="badge bg-info">{{ $perf['en_cours'] }}</span>
                                                                </td>
                                                                <td><span
                                                                        class="badge bg-success">{{ $perf['traitees'] }}</span>
                                                                </td>
                                                                <td><span
                                                                        class="badge bg-danger">{{ $perf['rejetees'] }}</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge {{ $perf['temps_moyen'] <= 3 ? 'bg-success' : ($perf['temps_moyen'] <= 7 ? 'bg-warning' : 'bg-danger') }}">
                                                                        {{ $perf['temps_moyen'] }} jours
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge {{ $perf['taux_resolution'] >= 80 ? 'bg-success' : ($perf['taux_resolution'] >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                                                        {{ $perf['taux_resolution'] }}%
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $score = 0;
                                                                        if ($perf['taux_resolution'] >= 80) {
                                                                            $score += 40;
                                                                        } elseif ($perf['taux_resolution'] >= 60) {
                                                                            $score += 20;
                                                                        }

                                                                        if ($perf['temps_moyen'] <= 3) {
                                                                            $score += 30;
                                                                        } elseif ($perf['temps_moyen'] <= 7) {
                                                                            $score += 15;
                                                                        }

                                                                        if ($perf['total'] > 0) {
                                                                            $enCoursRate =
                                                                                ($perf['en_cours'] / $perf['total']) *
                                                                                100;
                                                                            if ($enCoursRate <= 20) {
                                                                                $score += 30;
                                                                            } elseif ($enCoursRate <= 40) {
                                                                                $score += 15;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    @if ($score >= 80)
                                                                        <span class="badge bg-success">Excellent</span>
                                                                    @elseif($score >= 60)
                                                                        <span class="badge bg-info">Bon</span>
                                                                    @elseif($score >= 40)
                                                                        <span class="badge bg-warning">Moyen</span>
                                                                    @else
                                                                        <span class="badge bg-danger">À améliorer</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Graphiques principaux -->
                        <div class="row mb-4">
                            <!-- Évolution mensuelle -->
                            <div class="col-lg-8 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">📈 Évolution mensuelle (12 derniers mois)</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="evolutionChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Répartition par statut -->
                            <div class="col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">📊 Répartition par statut</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="statutChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques par bureau et catégorie -->
                        <div class="row mb-4">
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">🏢 Répartition par bureau</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="bureauChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">📁 Répartition par catégorie</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="categorieChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tableaux détaillés -->
                        <div class="row">
                            <!-- Top utilisateurs -->
                            <div class="col-lg-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">👥 Top 10 des utilisateurs les plus actifs</h6>
                                        @if (!in_array('view_all', $roleSpecificData['permissions'] ?? []))
                                            <small class="text-muted">(Dans vos bureaux)</small>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        @if (count($utilisateursActifs) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Rang</th>
                                                            <th>Utilisateur</th>
                                                            <th>Nombre de requêtes</th>
                                                            <th>Activité</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($utilisateursActifs as $index => $user)
                                                            <tr>
                                                                <td>
                                                                    @if ($index == 0)
                                                                        🥇
                                                                    @elseif($index == 1)
                                                                        🥈
                                                                    @elseif($index == 2)
                                                                        🥉
                                                                    @else
                                                                        {{ $index + 1 }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ $user->user->nom_user ?? 'N/A' }}</td>
                                                                <td><span
                                                                        class="badge bg-primary">{{ $user->total_requetes }}</span>
                                                                </td>
                                                                <td>
                                                                    @if ($user->total_requetes >= 20)
                                                                        <span class="badge bg-success">Très actif</span>
                                                                    @elseif($user->total_requetes >= 10)
                                                                        <span class="badge bg-info">Actif</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Modéré</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center text-muted">
                                                <p>Aucune donnée disponible</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Temps de traitement par bureau -->
                            <div class="col-lg-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">⏱️ Temps moyen de traitement par bureau</h6>
                                    </div>
                                    <div class="card-body">
                                        @if (count($tempsTraitementParBureau) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Bureau</th>
                                                            <th>Temps moyen</th>
                                                            <th>Performance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tempsTraitementParBureau as $temps)
                                                            <tr>
                                                                <td>{{ $temps->bureau->label_bureau ?? 'N/A' }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge {{ $temps->moyenne_jours <= 3 ? 'bg-success' : ($temps->moyenne_jours <= 7 ? 'bg-warning' : 'bg-danger') }}">
                                                                        {{ round($temps->moyenne_jours, 1) }} jours
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if ($temps->moyenne_jours <= 3)
                                                                        <span class="badge bg-success">Excellent</span>
                                                                    @elseif($temps->moyenne_jours <= 7)
                                                                        <span class="badge bg-warning">Bon</span>
                                                                    @elseif($temps->moyenne_jours <= 14)
                                                                        <span class="badge bg-orange">Moyen</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Lent</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center text-muted">
                                                <p>Aucune donnée de traitement disponible</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Résumé des permissions (debug info pour les admins) -->
                        @if (in_array('view_all', $roleSpecificData['permissions'] ?? []))
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">ℹ️ Informations de session</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Rôle actuel:</strong> {{ $userRole }}
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Bureaux accessibles:</strong> {{ count($userBureaux) }}
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Permissions:</strong>
                                                    {{ implode(', ', $roleSpecificData['permissions'] ?? []) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            // Configuration commune des couleurs
            const colors = {
                primary: '#007bff',
                warning: '#ffc107',
                info: '#17a2b8',
                success: '#28a745',
                danger: '#dc3545',
                secondary: '#6c757d'
            };

            // Graphique d'évolution mensuelle
            const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
            new Chart(evolutionCtx, {
                type: 'line',
                data: {
                    labels: [
                        @foreach ($evolutionMensuelle->reverse() as $evolution)
                            '{{ sprintf('%02d', $evolution->mois) }}/{{ $evolution->annee }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Nombre de requêtes',
                        data: [
                            @foreach ($evolutionMensuelle->reverse() as $evolution)
                                {{ $evolution->total }},
                            @endforeach
                        ],
                        borderColor: colors.primary,
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: colors.primary,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    }
                }
            });

            // Graphique des statuts (avec rejeté)
            const statutCtx = document.getElementById('statutChart').getContext('2d');
            new Chart(statutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['En attente', 'En cours', 'Traitées', 'Rejetées'],
                    datasets: [{
                        data: [
                            {{ $requetesEnAttente }},
                            {{ $requetesEnCours }},
                            {{ $requetesTraitees }},
                            {{ $requetesRejetees }}
                        ],
                        backgroundColor: [
                            colors.warning,
                            colors.info,
                            colors.success,
                            colors.danger
                        ],
                        borderWidth: 3,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100)
                                        .toFixed(1) : 0;
                                    return context.label + ': ' + context.parsed + ' (' + percentage +
                                        '%)';
                                }
                            }
                        }
                    }
                }
            });

            // Graphique par bureau
            const bureauCtx = document.getElementById('bureauChart').getContext('2d');
            new Chart(bureauCtx, {
                type: 'bar',
                data: {
                    labels: [
                        @foreach ($statistiquesParBureau as $stat)
                            '{{ Str::limit($stat->bureau->label_bureau ?? 'N/A', 20) }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Nombre de requêtes',
                        data: [
                            @foreach ($statistiquesParBureau as $stat)
                                {{ $stat->total }},
                            @endforeach
                        ],
                        backgroundColor: colors.info,
                        borderColor: '#138496',
                        borderWidth: 1,
                        borderRadius: 4
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
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    }
                }
            });

            // Graphique par catégorie
            const categorieCtx = document.getElementById('categorieChart').getContext('2d');
            new Chart(categorieCtx, {
                type: 'horizontalBar',
                data: {
                    labels: [
                        @foreach ($statistiquesParCategorie as $stat)
                            '{{ Str::limit($stat->category->label_cat ?? 'N/A', 25) }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Nombre de requêtes',
                        data: [
                            @foreach ($statistiquesParCategorie as $stat)
                                {{ $stat->total }},
                            @endforeach
                        ],
                        backgroundColor: colors.success,
                        borderColor: '#1e7e34',
                        borderWidth: 1,
                        borderRadius: 4
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
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });

        // Fonction pour l'impression
        function printStatistics() {
            window.print();
        }

        // Auto-refresh des statistiques (optionnel)
        @if (in_array('view_all', $roleSpecificData['permissions'] ?? []))
            setInterval(function() {
                // Auto-refresh pour les admins toutes les 5 minutes
                // location.reload();
            }, 300000);
        @endif
    </script>

    <style>
        @media print {

            .btn,
            .card-header .badge {
                display: none !important;
            }

            .card {
                break-inside: avoid;
                margin-bottom: 20px;
            }

            .chart-container {
                page-break-inside: avoid;
            }
        }

        .badge.bg-orange {
            background-color: #fd7e14 !important;
        }

        .card-body canvas {
            max-height: 300px;
        }

        .performance-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
    </style>
@endsection
