@extends('laboratoires.public.layout', ['laboratoire' => $laboratoire])

@section('title', 'Alertes actives - ' . $laboratoire->label_labo)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-exclamation-triangle"></i> Alertes actives
            </h1>
            <p class="text-muted">Surveillance des échéances de projets et maintenances d'équipements</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('laboratoires.admin.notifications', $laboratoire->code_lab) }}" class="btn btn-info">
                <i class="bi bi-bell"></i> Notifications
            </a>
            <form method="POST" action="{{ route('laboratoires.admin.alertes.check', $laboratoire->code_lab) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-arrow-clockwise"></i> Vérifier les alertes
                </button>
            </form>
        </div>
    </div>

    <!-- Statistiques des alertes -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Alertes Urgentes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $alertStats['total_urgent'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Alertes Importantes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $alertStats['total_important'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-circle fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Projets Urgents
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $alertStats['projets_urgents'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-x fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Maintenances Urgentes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $alertStats['maintenances_urgentes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tools fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projets en échéance urgente -->
    @if($projetsUrgents->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="bi bi-exclamation-triangle"></i> Projets en échéance urgente (≤ 7 jours)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Responsable</th>
                                <th>Date de fin</th>
                                <th>Jours restants</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projetsUrgents as $projet)
                                @php
                                    $joursRestants = now()->diffInDays($projet->fin_projet, false);
                                @endphp
                                <tr class="table-danger">
                                    <td>
                                        <strong>{{ $projet->theme_projet }}</strong>
                                        <br><small class="text-muted">{{ $projet->code_projet }}</small>
                                    </td>
                                    <td>
                                        @if($projet->responsable)
                                            {{ $projet->responsable->nom_pers_lab ?? 'Non assigné' }}
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-danger font-weight-bold">
                                            {{ \Carbon\Carbon::parse($projet->fin_projet)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $joursRestants }} jour(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $projet->statut_projet }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Projets en échéance importante -->
    @if($projetsImportants->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="bi bi-exclamation-circle"></i> Projets en échéance importante (≤ 30 jours)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Responsable</th>
                                <th>Date de fin</th>
                                <th>Jours restants</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projetsImportants as $projet)
                                @php
                                    $joursRestants = now()->diffInDays($projet->fin_projet, false);
                                @endphp
                                <tr class="table-warning">
                                    <td>
                                        <strong>{{ $projet->theme_projet }}</strong>
                                        <br><small class="text-muted">{{ $projet->code_projet }}</small>
                                    </td>
                                    <td>
                                        @if($projet->responsable)
                                            {{ $projet->responsable->nom_pers_lab ?? 'Non assigné' }}
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-warning font-weight-bold">
                                            {{ \Carbon\Carbon::parse($projet->fin_projet)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $joursRestants }} jour(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $projet->statut_projet }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Maintenances d'équipements urgentes -->
    @if($maintenancesUrgentes->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="bi bi-tools"></i> Maintenances d'équipements urgentes (≤ 3 jours)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Équipement</th>
                                <th>Localisation</th>
                                <th>Date de maintenance</th>
                                <th>Jours restants</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($maintenancesUrgentes as $entretien)
                                @php
                                    $joursRestants = now()->diffInDays($entretien->debut_entretien, false);
                                @endphp
                                <tr class="table-danger">
                                    <td>
                                        <strong>{{ $entretien->equipement->nom_equip }}</strong>
                                        <br><small class="text-muted">{{ $entretien->equipement->code_equip }}</small>
                                    </td>
                                    <td>{{ $entretien->equipement->localisation }}</td>
                                    <td>
                                        <span class="text-danger font-weight-bold">
                                            {{ \Carbon\Carbon::parse($entretien->debut_entretien)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $joursRestants }} jour(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $entretien->equipement->etat }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $entretien->equipement->code_equip]) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Maintenances d'équipements importantes -->
    @if($maintenancesImportantes->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="bi bi-tools"></i> Maintenances d'équipements importantes (≤ 30 jours)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Équipement</th>
                                <th>Localisation</th>
                                <th>Date de maintenance</th>
                                <th>Jours restants</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($maintenancesImportantes as $entretien)
                                @php
                                    $joursRestants = now()->diffInDays($entretien->debut_entretien, false);
                                @endphp
                                <tr class="table-warning">
                                    <td>
                                        <strong>{{ $entretien->equipement->nom_equip }}</strong>
                                        <br><small class="text-muted">{{ $entretien->equipement->code_equip }}</small>
                                    </td>
                                    <td>{{ $entretien->equipement->localisation }}</td>
                                    <td>
                                        <span class="text-warning font-weight-bold">
                                            {{ \Carbon\Carbon::parse($entretien->debut_entretien)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $joursRestants }} jour(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $entretien->equipement->etat }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $entretien->equipement->code_equip]) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Aucune alerte -->
    @if($projetsUrgents->count() == 0 && $projetsImportants->count() == 0 && $maintenancesUrgentes->count() == 0 && $maintenancesImportantes->count() == 0)
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-success">Aucune alerte active</h5>
                <p class="text-muted">Tous les projets et équipements sont à jour. Aucune action urgente n'est requise.</p>
            </div>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Actualiser automatiquement les alertes
setInterval(function() {
    location.reload();
}, 300000); // Actualiser toutes les 5 minutes
</script>
@endpush
