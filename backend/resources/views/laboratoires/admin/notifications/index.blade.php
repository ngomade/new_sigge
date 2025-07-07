@extends('laboratoires.public.layout', ['laboratoire' => $laboratoire])

@section('title', 'Notifications - ' . $laboratoire->label_labo)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-bell"></i> Notifications
            </h1>
            <p class="text-muted">Gestion des notifications et alertes du laboratoire</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('laboratoires.admin.alertes', $laboratoire->code_lab) }}" class="btn btn-warning">
                <i class="bi bi-exclamation-triangle"></i> Alertes actives
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Notifications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bell fa-2x text-gray-300"></i>
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
                                Non lues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['non_lues'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bell-slash fa-2x text-gray-300"></i>
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
                                Échéances Projets
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['urgentes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-x fa-2x text-gray-300"></i>
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
                                Maintenances
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['maintenance'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
            @if($stats['non_lues'] > 0)
                <form method="POST" action="{{ route('laboratoires.admin.notifications.mark-all-read', $laboratoire->code_lab) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-all"></i> Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('laboratoires.admin.notifications', $laboratoire->code_lab) }}" class="row g-3">
                <div class="col-md-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="projet_echeance" {{ $type === 'projet_echeance' ? 'selected' : '' }}>Échéance de projet</option>
                        <option value="maintenance_equipement" {{ $type === 'maintenance_equipement' ? 'selected' : '' }}>Maintenance d'équipement</option>
                        <option value="system" {{ $type === 'system' ? 'selected' : '' }}>Système</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="lu" class="form-label">Statut</label>
                    <select name="lu" id="lu" class="form-select">
                        <option value="">Tous</option>
                        <option value="0" {{ $lu === '0' ? 'selected' : '' }}>Non lues</option>
                        <option value="1" {{ $lu === '1' ? 'selected' : '' }}>Lues</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ $search }}" placeholder="Rechercher dans le titre ou le message...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des notifications</h6>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Titre</th>
                                <th>Message</th>
                                <th>Expéditeur</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                                <tr class="{{ !$notification->lu ? 'table-warning' : '' }}">
                                    <td>
                                        @switch($notification->type)
                                            @case('projet_echeance')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-calendar-x"></i> Projet
                                                </span>
                                                @break
                                            @case('maintenance_equipement')
                                                <span class="badge bg-info">
                                                    <i class="bi bi-tools"></i> Maintenance
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-gear"></i> Système
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <strong>{{ $notification->titre }}</strong>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;" title="{{ $notification->message }}">
                                            {{ $notification->message }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($notification->expediteur)
                                            {{ $notification->expediteur->nom_pers_lab ?? 'Système' }}
                                        @else
                                            <span class="text-muted">Système</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $notification->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($notification->lu)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Lue
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-circle"></i> Non lue
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if(!$notification->lu)
                                                <button type="button" class="btn btn-success"
                                                        onclick="markAsRead({{ $notification->id_notif }})"
                                                        title="Marquer comme lue">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-danger"
                                                    onclick="deleteNotification({{ $notification->id_notif }})"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune notification trouvée</h5>
                    <p class="text-muted">Il n'y a actuellement aucune notification correspondant à vos critères.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Formulaire pour marquer comme lue -->
<form id="markAsReadForm" method="POST" style="display: none;">
    @csrf
</form>

<!-- Formulaire pour supprimer -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function markAsRead(notificationId) {
    const form = document.getElementById('markAsReadForm');
    let url = "{{ route('laboratoires.admin.notifications.mark-read', [$laboratoire->code_lab, 'NOTIF_ID']) }}";
    url = url.replace('NOTIF_ID', notificationId);
    form.action = url;
    form.submit();
}

function deleteNotification(notificationId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
        const form = document.getElementById('deleteForm');
        let url = "{{ route('laboratoires.admin.notifications.destroy', [$laboratoire->code_lab, 'NOTIF_ID']) }}";
        url = url.replace('NOTIF_ID', notificationId);
        form.action = url;
        form.submit();
    }
}

// Actualiser automatiquement les notifications non lues
setInterval(function() {
    fetch("{{ route('laboratoires.admin.notifications.unread-count', $laboratoire->code_lab) }}")
        .then(response => response.json())
        .then(data => {
            // Mettre à jour le compteur si nécessaire
            const countElement = document.querySelector('.text-warning .h5');
            if (countElement && data.count != {{ $stats['non_lues'] }}) {
                location.reload();
            }
        });
}, 30000); // Actualiser toutes les 30 secondes
</script>
@endpush
