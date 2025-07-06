@extends('laboratoires.public.layout')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Dashboard Administrateur - {{ $laboratoire->label_labo }}</h1>
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
                    <a href="#" class="btn btn-outline-dark">Voir les publications</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h4>Bienvenue dans l'espace d'administration du laboratoire !</h4>
                <p>Utilisez le menu ci-dessus pour gérer les membres, les candidatures, les projets, les équipements, les publications et plus encore.</p>
            </div>
        </div>
    </div>
</div>
@endsection
