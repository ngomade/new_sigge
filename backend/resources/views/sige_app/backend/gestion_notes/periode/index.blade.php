@extends('sige_app.backend.template.backend')

@section('title', 'Gestion des Périodes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestion des Périodes</h1>
                <a href="{{ route('periodes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle Période
                </a>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('periodes.index') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="salle" class="form-label">Salle</label>
                                <select name="salle" id="salle" class="form-select">
                                    <option value="">Toutes les salles</option>
                                    @foreach($salles as $salle)
                                        <option value="{{ $salle->code_salle }}" {{ request('salle') == $salle->code_salle ? 'selected' : '' }}>
                                            {{ $salle->code_salle }} ({{ $salle->nb_place_salle }} places)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="ec" class="form-label">Élément Constitutif</label>
                                <select name="ec" id="ec" class="form-select">
                                    <option value="">Tous les EC</option>
                                    @foreach($ecs as $ec)
                                        <option value="{{ $ec->code_ec }}" {{ request('ec') == $ec->code_ec ? 'selected' : '' }}>
                                            {{ $ec->intitule_ec }} ({{ $ec->ue->intitule_ue ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="date_debut" class="form-label">Période</label>
                                <div class="input-group">
                                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                            <a href="{{ route('periodes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-2 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Total Périodes</h6>
                            <p class="card-text display-6">{{ $stats['total_periodes'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Aujourd'hui</h6>
                            <p class="card-text display-6">{{ $stats['periodes_aujourdhui'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Cette Semaine</h6>
                            <p class="card-text display-6">{{ $stats['periodes_cette_semaine'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Salles Utilisées</h6>
                            <p class="card-text display-6">{{ $stats['salles_utilisees'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Durée Totale (min)</h6>
                            <p class="card-text display-6">{{ $stats['duree_totale'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Taux Occupation (%)</h6>
                            <p class="card-text display-6">{{ $stats['taux_occupation_moyen'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendrier -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Calendrier des Périodes</h5>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Liste des périodes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des Périodes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Salle</th>
                                    <th>Élément Constitutif</th>
                                    <th>UE</th>
                                    <th>Semestre</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                    <th>Durée (min)</th>
                                    <th>Jour</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($periodes as $periode)
                                    <tr>
                                        <td>{{ $periode->salle->code_salle ?? 'N/A' }}</td>
                                        <td>{{ $periode->ec->intitule_ec ?? 'N/A' }}</td>
                                        <td>{{ $periode->ec->ue->intitule_ue ?? 'N/A' }}</td>
                                        <td>{{ $periode->ec->ue->semestre->label_sem ?? 'N/A' }}</td>
                                        <td>{{ $periode->debut_periode ? \Carbon\Carbon::parse($periode->debut_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>{{ $periode->fin_periode ? \Carbon\Carbon::parse($periode->fin_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>{{ $periode->duree_periode ?? 'N/A' }}</td>
                                        <td>{{ $periode->jour_periode ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('periodes.show', [$periode->code_salle, $periode->code_ec]) }}" 
                                                   class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('periodes.edit', [$periode->code_salle, $periode->code_ec]) }}" 
                                                   class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('periodes.destroy', [$periode->code_salle, $periode->code_ec]) }}" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette période ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucune période trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json($evenements),
            eventClick: function(info) {
                // Rediriger vers la page de détail de la période
                window.location.href = info.event.extendedProps.url;
            }
        });
        calendar.render();
    });
</script>
@endsection
