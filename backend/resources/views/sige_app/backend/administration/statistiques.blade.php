@extends("sige_app.backend.template.backend")
@section('title', 'Statistiques des Requêtes')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">📊 Statistiques Globales des Requêtes</h4>
                        <div>
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-warning text-dark h-100">
                                    <div class="card-body text-center">
                                        <h2 class="mb-0">{{ $requetesEnAttente }}</h2>
                                        <p class="mb-0">En attente</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body text-center">
                                        <h2 class="mb-0">{{ $requetesEnCours }}</h2>
                                        <p class="mb-0">En cours</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body text-center">
                                        <h2 class="mb-0">{{ $requetesTraitees }}</h2>
                                        <p class="mb-0">Traitées</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Graphiques principaux -->
                        <div class="row mb-4">
                            <!-- Évolution mensuelle -->
                            <div class="col-lg-8 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">Évolution mensuelle (12 derniers mois)</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="evolutionChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Répartition par statut -->
                            <div class="col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">Répartition par statut</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="statutChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques par bureau et catégorie -->
                        <div class="row mb-4">
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">Répartition par bureau</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="bureauChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">Répartition par catégorie</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="categorieChart"></canvas>
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
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Utilisateur</th>
                                                        <th>Nombre de requêtes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($utilisateursActifs as $user)
                                                        <tr>
                                                            <td>{{ $user->user->nom_user ?? 'N/A' }}</td>
                                                            <td><span
                                                                    class="badge bg-primary">{{ $user->total_requetes }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
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
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Bureau</th>
                                                        <th>Temps moyen (jours)</th>
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
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique des statuts
            const statutCtx = document.getElementById('statutChart').getContext('2d');
            new Chart(statutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['En attente', 'En cours', 'Traitées'],
                    datasets: [{
                        data: [{{ $requetesEnAttente }}, {{ $requetesEnCours }},
                            {{ $requetesTraitees }}
                        ],
                        backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
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
                            '{{ $stat->bureau->label_bureau ?? 'N/A' }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Nombre de requêtes',
                        data: [
                            @foreach ($statistiquesParBureau as $stat)
                                {{ $stat->total }},
                            @endforeach
                        ],
                        backgroundColor: '#17a2b8',
                        borderColor: '#138496',
                        borderWidth: 1
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
                        y: {
                            beginAtZero: true
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
                            '{{ $stat->category->label_cat ?? 'N/A' }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Nombre de requêtes',
                        data: [
                            @foreach ($statistiquesParCategorie as $stat)
                                {{ $stat->total }},
                            @endforeach
                        ],
                        backgroundColor: '#28a745',
                        borderColor: '#1e7e34',
                        borderWidth: 1
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
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
