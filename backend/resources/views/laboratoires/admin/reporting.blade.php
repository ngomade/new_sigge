@extends('laboratoires.public.layout')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Génération de Rapports</h1>
                    <p class="text-muted mb-0">{{ $laboratoire->label_labo }}</p>
                </div>
                <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour au Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Types de rapports -->
    <div class="row">
        <!-- Rapport général -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rapport Général</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Vue d'ensemble complète</div>
                            <small class="text-muted">Statistiques globales du laboratoire</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('laboratoires.admin.reports.pdf', $laboratoire->code_lab) }}?type=general"
                           class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('laboratoires.admin.reports.excel', $laboratoire->code_lab) }}?type=general"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport membres -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Rapport Membres</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Liste des membres</div>
                            <small class="text-muted">Membres actifs et leurs rôles</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('laboratoires.admin.reports.pdf', $laboratoire->code_lab) }}?type=membres"
                           class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('laboratoires.admin.reports.excel', $laboratoire->code_lab) }}?type=membres"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport projets -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rapport Projets</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Projets de recherche</div>
                            <small class="text-muted">Détails des projets et participants</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('laboratoires.admin.reports.pdf', $laboratoire->code_lab) }}?type=projets"
                           class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('laboratoires.admin.reports.excel', $laboratoire->code_lab) }}?type=projets"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport équipements -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Rapport Équipements</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Inventaire équipements</div>
                            <small class="text-muted">État et maintenance des équipements</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('laboratoires.admin.reports.pdf', $laboratoire->code_lab) }}?type=equipements"
                           class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('laboratoires.admin.reports.excel', $laboratoire->code_lab) }}?type=equipements"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport utilisations -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rapport Utilisations</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Historique des utilisations</div>
                            <small class="text-muted">Réservations et utilisations d'équipements</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('laboratoires.admin.reports.pdf', $laboratoire->code_lab) }}?type=utilisations"
                           class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('laboratoires.admin.reports.excel', $laboratoire->code_lab) }}?type=utilisations"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques équipements -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Statistiques Équipements</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Analyse d'utilisation</div>
                            <small class="text-muted">Graphiques et analyses détaillées</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bar-chart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('laboratoires.admin.equipements.stats', $laboratoire->code_lab) }}"
                           class="btn btn-sm btn-outline-info">
                            <i class="bi bi-graph-up"></i> Voir les statistiques
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Options avancées -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Options Avancées</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laboratoires.admin.reports.pdf', $laboratoire->code_lab) }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type de rapport</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="general">Rapport général</option>
                                <option value="membres">Rapport membres</option>
                                <option value="projets">Rapport projets</option>
                                <option value="equipements">Rapport équipements</option>
                                <option value="utilisations">Rapport utilisations</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="periode" class="form-label">Période</label>
                            <select class="form-select" id="periode" name="periode">
                                <option value="30">30 derniers jours</option>
                                <option value="90">3 derniers mois</option>
                                <option value="180">6 derniers mois</option>
                                <option value="365">1 an</option>
                                <option value="all">Toute la période</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="format" class="form-label">Format</label>
                            <select class="form-select" id="format" name="format">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-download"></i> Générer le rapport
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des rapports -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historique des Rapports Générés</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Format</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Aucun rapport généré récemment
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du formulaire avancé
    const formatSelect = document.getElementById('format');
    const form = document.querySelector('form');

    formatSelect.addEventListener('change', function() {
        const format = this.value;
        const type = document.getElementById('type').value;

        if (format === 'excel') {
            form.action = "{{ route('laboratoires.admin.reports.excel', $laboratoire->code_lab) }}";
        } else {
            form.action = "{{ route('laboratoires.admin.reports.pdf', $laboratoire->code_lab) }}";
        }
    });
});
</script>
@endsection
