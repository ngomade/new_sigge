@php
    $unreadCount = 0;
    if (isset($laboratoire)) {
        $unreadCount = \App\Models\laboratoires\LabNotif::where('code_lab', $laboratoire->code_lab)
            ->where('lu', false)
            ->count();
    }
@endphp

<div class="dropdown">
    <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Notifications</h6>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('laboratoires.admin.notifications.mark-all-read', $laboratoire->code_lab) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
        <div class="dropdown-divider"></div>

        @if($unreadCount > 0)
            @php
                $notifications = \App\Models\laboratoires\LabNotif::where('code_lab', $laboratoire->code_lab)
                    ->where('lu', false)
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get();
            @endphp

            @foreach($notifications as $notification)
                <a class="dropdown-item d-flex align-items-start py-2" href="{{ route('laboratoires.admin.notifications', $laboratoire->code_lab) }}">
                    <div class="flex-shrink-0 me-3">
                        @switch($notification->type)
                            @case('projet_echeance')
                                <i class="bi bi-calendar-x text-danger"></i>
                                @break
                            @case('maintenance_equipement')
                                <i class="bi bi-tools text-info"></i>
                                @break
                            @default
                                <i class="bi bi-gear text-secondary"></i>
                        @endswitch
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $notification->titre }}</div>
                        <div class="small text-muted">{{ Str::limit($notification->message, 100) }}</div>
                        <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </a>
                @if(!$loop->last)
                    <div class="dropdown-divider"></div>
                @endif
            @endforeach

            @if($unreadCount > 5)
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center" href="{{ route('laboratoires.admin.notifications', $laboratoire->code_lab) }}">
                    Voir toutes les notifications ({{ $unreadCount }})
                </a>
            @endif
        @else
            <div class="dropdown-item text-center py-3">
                <i class="bi bi-check-circle text-success mb-2"></i>
                <div class="text-muted">Aucune notification non lue</div>
            </div>
        @endif

        <div class="dropdown-divider"></div>
        <div class="dropdown-item d-flex justify-content-between">
            <a href="{{ route('laboratoires.admin.notifications', $laboratoire->code_lab) }}" class="text-decoration-none">
                <i class="bi bi-bell"></i> Toutes les notifications
            </a>
            <a href="{{ route('laboratoires.admin.alertes', $laboratoire->code_lab) }}" class="text-decoration-none">
                <i class="bi bi-exclamation-triangle"></i> Alertes
            </a>
        </div>
    </div>
</div>

<script>
// Actualiser automatiquement les notifications
setInterval(function() {
    fetch("{{ route('laboratoires.admin.notifications.unread-count', $laboratoire->code_lab) }}")
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.bi-bell + .badge');
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                } else {
                    const bell = document.querySelector('.bi-bell');
                    if (bell) {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                        newBadge.textContent = data.count > 99 ? '99+' : data.count;
                        bell.parentElement.appendChild(newBadge);
                    }
                }
            } else {
                if (badge) {
                    badge.remove();
                }
            }
        });
}, 30000); // Actualiser toutes les 30 secondes
</script>
