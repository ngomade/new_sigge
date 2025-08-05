@extends('sige_app.backend.template.backend')

@section('title', 'Détails de la Période')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('periodes.index') }}" class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Détails de la Période</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('periodes.edit', [$periode->code_salle, $periode->code_ec]) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <form method="POST" action="{{ route('periodes.destroy', [$periode->code_salle, $periode->code_ec]) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette période ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                <!-- Informations générales -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations Générales</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Salle:</strong></td>
                                    <td>{{ $periode->salle->code_salle ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Capacité:</strong></td>
                                    <td>{{ $periode->salle->nb_place_salle ?? 'N/A' }} places</td>
                                </tr>
                                <tr>
                                    <td><strong>Élément Constitutif:</strong></td>
                                    <td>{{ $periode->ec->intitule_ec ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unité d'Enseignement:</strong></td>
                                    <td>{{ $periode->ec->ue->intitule_ue ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Semestre:</strong></td>
                                    <td>{{ $periode->ec->ue->semestre->label_sem ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Détails de la période -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Détails de la Période</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Date Début:</strong></td>
                                    <td>{{ $periode->debut_periode ? \Carbon\Carbon::parse($periode->debut_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date Fin:</strong></td>
                                    <td>{{ $periode->fin_periode ? \Carbon\Carbon::parse($periode->fin_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jour:</strong></td>
                                    <td>
                                        @switch($periode->jour_periode)
                                            @case(1) Lundi @break
                                            @case(2) Mardi @break
                                            @case(3) Mercredi @break
                                            @case(4) Jeudi @break
                                            @case(5) Vendredi @break
                                            @case(6) Samedi @break
                                            @case(7) Dimanche @break
                                            @default N/A
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Durée:</strong></td>
                                    <td>{{ $periode->duree_periode ?? 'N/A' }} minutes</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Durée (heures)</h6>
                            <p class="card-text display-6">{{ $stats['duree_heures'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Capacité Salle</h6>
                            <p class="card-text display-6">{{ $stats['capacite_salle'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Étudiants Inscrits</h6>
                            <p class="card-text display-6">{{ $stats['etudiants_inscrits'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Taux Occupation</h6>
                            <p class="card-text display-6">{{ $stats['taux_occupation'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enseignants assignés -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Enseignants Assignés</h5>
                </div>
                <div class="card-body">
                    @if($periode->ec->assignations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Enseignant</th>
                                        <th>Classe</th>
                                        <th>Date d'Assignation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($periode->ec->assignations as $assignation)
                                        <tr>
                                            <td>{{ $assignation->personnel->nom_pers ?? 'N/A' }} {{ $assignation->personnel->prenom_pers ?? '' }}</td>
                                            <td>{{ $assignation->classe->label_class ?? 'N/A' }}</td>
                                            <td>{{ $assignation->created_at ? \Carbon\Carbon::parse($assignation->created_at)->format('d/m/Y') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center mb-0">Aucun enseignant assigné à cet élément constitutif.</p>
                    @endif
                </div>
            </div>

            <!-- Autres périodes dans la même salle -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Autres Périodes dans la Salle {{ $periode->salle->code_salle ?? 'N/A' }}</h5>
                </div>
                <div class="card-body">
                    @if($autresPeriodesSalle->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Élément Constitutif</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Durée (min)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($autresPeriodesSalle as $autrePeriode)
                                        <tr>
                                            <td>{{ $autrePeriode->ec->intitule_ec ?? 'N/A' }}</td>
                                            <td>{{ $autrePeriode->debut_periode ? \Carbon\Carbon::parse($autrePeriode->debut_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>{{ $autrePeriode->fin_periode ? \Carbon\Carbon::parse($autrePeriode->fin_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>{{ $autrePeriode->duree_periode ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('periodes.show', [$autrePeriode->code_salle, $autrePeriode->code_ec]) }}" 
                                                   class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center mb-0">Aucune autre période dans cette salle.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
