@extends('laboratoires.public.layout')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestion Administrative - {{ $laboratoire->label_labo }}</h1>
            <a href="{{ route('laboratoires.admin.dashboard.new', $laboratoire->code_lab) }}" class="btn btn-primary">
                <i class="bi bi-graph-up"></i> Consulter le Dashboard
            </a>
        </div>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Membres actifs</h5>
                        <p class="display-4">{{ $stats['membres'] }}</p>
                        <a href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}" class="btn btn-outline-primary">Gérer les membres</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Candidatures en attente</h5>
                        <p class="display-4">{{ $stats['candidatures'] }}</p>
                        <a href="{{ route('laboratoires.admin.candidatures', $laboratoire->code_lab) }}" class="btn btn-outline-warning">Voir les candidatures</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs externes actifs</h5>
                        <p class="display-4">{{ $stats['externes'] }}</p>
                         <a href="{{ route('laboratoires.admin.externes', $laboratoire->code_lab) }}" class="btn btn-outline-info">Gérer les externes</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Projets de recherche</h5>
                        <p class="display-4">{{ $stats['projets'] }}</p>
                        <a href="{{ route('laboratoires.admin.projets', $laboratoire->code_lab) }}" class="btn btn-outline-success">Gérer les projets</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Équipements</h5>
                        <p class="display-4">{{ $stats['equipements'] }}</p>
                        <a href="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">Gérer les équipements</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Publications</h5>
                        <p class="display-4">{{ $stats['publications'] }}</p>
                        <a href="{{ route('laboratoires.admin.publications.index', $laboratoire->code_lab) }}" class="btn btn-outline-dark">Voir les publications</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Rapports Personnalisés</h5>
                        <p class="display-4">
                            <i class="bi bi-file-earmark-text text-primary"></i>
                        </p>
                        <p class="text-muted">Créez et gérez vos rapports personnalisés</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('laboratoires.admin.rapports', $laboratoire->code_lab) }}" class="btn btn-outline-primary">
                                <i class="bi bi-collection"></i> Gérer les rapports
                            </a>
                            <a href="{{ route('laboratoires.admin.rapports.create', $laboratoire->code_lab) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Nouveau rapport
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Rapports Automatiques</h5>
                        <p class="display-4">
                            <i class="bi bi-graph-up text-success"></i>
                        </p>
                        <p class="text-muted">Générez des rapports automatiques</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('laboratoires.admin.reporting', $laboratoire->code_lab) }}" class="btn btn-outline-success">
                                <i class="bi bi-file-earmark-text"></i> Rapports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Notifications & Alertes</h5>
                        <p class="display-4">
                            <i class="bi bi-bell text-warning"></i>
                        </p>
                        <p class="text-muted">Surveillez les échéances et maintenances</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('laboratoires.admin.notifications', $laboratoire->code_lab) }}" class="btn btn-outline-warning">
                                <i class="bi bi-bell"></i> Notifications
                            </a>
                            <a href="{{ route('laboratoires.admin.alertes', $laboratoire->code_lab) }}" class="btn btn-warning">
                                <i class="bi bi-exclamation-triangle"></i> Alertes actives
                            </a>
                            <a href="{{ route('laboratoires.admin.annonces', $laboratoire->code_lab) }}" class="btn btn-outline-primary">
                                <i class="bi bi-megaphone"></i> Annonces
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>Bienvenue dans l'espace d'administration du laboratoire !</h4>
                    <p>Utilisez le menu ci-dessus pour gérer les membres, les candidatures, les projets, les équipements, les publications, les rapports, les notifications et alertes, et plus encore.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
