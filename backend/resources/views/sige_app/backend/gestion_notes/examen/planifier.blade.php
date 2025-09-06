@extends('sige_app.backend.template.backend')

@section('title', 'Planifier un Examen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('examens.show', $examen->code_examen) }}" class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Planifier un Examen</h1>
                </div>
                <a href="{{ route('examens.show', $examen->code_examen) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de l'Examen</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Session d'Examen:</strong></td>
                                    <td>{{ $examen->sessionExamen->label_session ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Année Scolaire:</strong></td>
                                    <td>{{ $examen->sessionExamen->anneeScolaire->code_annee ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Type d'Évaluation:</strong></td>
                                    <td>{{ $examen->type_evaluation ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Statut:</strong></td>
                                    <td>
                                        @if($examen->sessionExamen->statut_session ?? false)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Planification des Périodes</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('examens.storePlanification', $examen->code_examen) }}">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-bordered" id="planification-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Salle</th>
                                        <th>Élément Constitutif</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Jour</th>
                                        <th>Durée (min)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="planification-rows">
                                    <tr>
                                        <td>
                                            <select name="planifications[0][code_salle]" class="form-select salle-select" required>
                                                <option value="">Sélectionnez une salle</option>
                                                @foreach($salles as $salle)
                                                    <option value="{{ $salle->code_salle }}">
                                                        {{ $salle->code_salle }} ({{ $salle->nb_place_salle }} places)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="planifications[0][code_ec]" class="form-select ec-select" required>
                                                <option value="">Sélectionnez un EC</option>
                                                @foreach($ecs as $ec)
                                                    <option value="{{ $ec->code_ec }}">
                                                        {{ $ec->intitule_ec }} ({{ $ec->ue->intitule_ue ?? '' }} - {{ $ec->ue->semestre->label_sem ?? '' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="datetime-local" name="planifications[0][debut_periode]" class="form-control" required>
                                        </td>
                                        <td>
                                            <input type="datetime-local" name="planifications[0][fin_periode]" class="form-control" required>
                                        </td>
                                        <td>
                                            <select name="planifications[0][jour_periode]" class="form-select" required>
                                                <option value="">Sélectionnez un jour</option>
                                                <option value="1">Lundi</option>
                                                <option value="2">Mardi</option>
                                                <option value="3">Mercredi</option>
                                                <option value="4">Jeudi</option>
                                                <option value="5">Vendredi</option>
                                                <option value="6">Samedi</option>
                                                <option value="7">Dimanche</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="planifications[0][duree_periode]" class="form-control" min="30" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-success" id="add-row">
                                <i class="fas fa-plus"></i> Ajouter une ligne
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer la Planification
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Périodes existantes -->
            @if($periodesTourning->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Périodes Existantes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Salle</th>
                                        <th>Élément Constitutif</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Jour</th>
                                        <th>Durée (min)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($periodesTourning as $periode)
                                        <tr>
                                            <td>{{ $periode->salle->code_salle ?? 'N/A' }}</td>
                                            <td>{{ $periode->ec->intitule_ec ?? 'N/A' }}</td>
                                            <td>{{ $periode->debut_periode ? \Carbon\Carbon::parse($periode->debut_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>{{ $periode->fin_periode ? \Carbon\Carbon::parse($periode->fin_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>{{ $periode->jour_periode ?? 'N/A' }}</td>
                                            <td>{{ $periode->duree_periode ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowIndex = 1;

        // Ajouter une nouvelle ligne
        document.getElementById('add-row').addEventListener('click', function() {
            const tbody = document.getElementById('planification-rows');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="planifications[${rowIndex}][code_salle]" class="form-select salle-select" required>
                        <option value="">Sélectionnez une salle</option>
                        @foreach($salles as $salle)
                            <option value="{{ $salle->code_salle }}">
                                {{ $salle->code_salle }} ({{ $salle->nb_place_salle }} places)
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="planifications[${rowIndex}][code_ec]" class="form-select ec-select" required>
                        <option value="">Sélectionnez un EC</option>
                        @foreach($ecs as $ec)
                            <option value="{{ $ec->code_ec }}">
                                {{ $ec->intitule_ec }} ({{ $ec->ue->intitule_ue ?? '' }} - {{ $ec->ue->semestre->label_sem ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="datetime-local" name="planifications[${rowIndex}][debut_periode]" class="form-control" required>
                </td>
                <td>
                    <input type="datetime-local" name="planifications[${rowIndex}][fin_periode]" class="form-control" required>
                </td>
                <td>
                    <select name="planifications[${rowIndex}][jour_periode]" class="form-select" required>
                        <option value="">Sélectionnez un jour</option>
                        <option value="1">Lundi</option>
                        <option value="2">Mardi</option>
                        <option value="3">Mercredi</option>
                        <option value="4">Jeudi</option>
                        <option value="5">Vendredi</option>
                        <option value="6">Samedi</option>
                        <option value="7">Dimanche</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="planifications[${rowIndex}][duree_periode]" class="form-control" min="30" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
            rowIndex++;
        });

        // Supprimer une ligne
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
                const row = e.target.closest('tr') || e.target.closest('.remove-row').closest('tr');
                if (row) {
                    row.remove();
                }
            }
        });
    });
</script>
@endsection
