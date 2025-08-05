@extends('sige_app.backend.template.backend')

@section('title', 'Calcul des Moyennes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('evaluations.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Calcul des Moyennes</h1>
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

            <!-- Formulaire de sélection -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sélectionner un Étudiant et une Année</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('evaluations.moyennes') }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code_user" class="form-label">Étudiant <span class="text-danger">*</span></label>
                                <select name="code_user" id="code_user" class="form-select" required>
                                    <option value="">Sélectionnez un étudiant</option>
                                    <!-- Les options seront remplies dynamiquement -->
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="code_annee" class="form-label">Année Scolaire <span class="text-danger">*</span></label>
                                <select name="code_annee" id="code_annee" class="form-select" required>
                                    <option value="">Sélectionnez une année</option>
                                    <!-- Les options seront remplies dynamiquement -->
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calculator"></i> Calculer les Moyennes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Résultats des moyennes -->
            @if(isset($etudiant) && isset($annee))
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informations de l'Étudiant</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td><strong>Nom:</strong></td>
                                        <td>{{ $etudiant->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $etudiant->email ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td><strong>Année Scolaire:</strong></td>
                                        <td>{{ $annee->code_annee }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Moyenne Générale:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $moyenne_annee >= 10 ? 'success' : 'danger' }} fs-5">
                                                {{ $moyenne_annee }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Résultats par semestre -->
                @foreach($resultats as $semestreData)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                {{ $semestreData['semestre']->label_sem ?? 'Semestre' }}
                                <span class="float-end">
                                    Moyenne: 
                                    <span class="badge bg-{{ $semestreData['moyenne_semestre'] >= 10 ? 'success' : 'danger' }}">
                                        {{ $semestreData['moyenne_semestre'] }}
                                    </span>
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Unité d'Enseignement</th>
                                            <th>Élément Constitutif</th>
                                            <th>Crédits</th>
                                            <th>Moyenne</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($semestreData['ues'] as $ueData)
                                            @foreach($ueData['ecs'] as $ecData)
                                                <tr>
                                                    <td>{{ $ueData['ue']->intitule_ue ?? 'N/A' }}</td>
                                                    <td>{{ $ecData['ec']->intitule_ec ?? 'N/A' }}</td>
                                                    <td>{{ $ecData['ec']->credit_ec ?? 0 }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $ecData['moyenne'] >= 10 ? 'success' : 'danger' }}">
                                                            {{ $ecData['moyenne'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($ecData['moyenne'] >= 10)
                                                            <span class="badge bg-success">Validé</span>
                                                        @else
                                                            <span class="badge bg-danger">Non Validé</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <!-- Total de l'UE -->
                                            <tr class="table-secondary">
                                                <td colspan="2"><strong>Total {{ $ueData['ue']->intitule_ue ?? 'UE' }}</strong></td>
                                                <td><strong>{{ $ueData['total_credits_ue'] }}</strong></td>
                                                <td>
                                                    <strong>
                                                        <span class="badge bg-{{ $ueData['moyenne_ue'] >= 10 ? 'success' : 'danger' }}">
                                                            {{ $ueData['moyenne_ue'] }}
                                                        </span>
                                                    </strong>
                                                </td>
                                                <td>
                                                    @if($ueData['moyenne_ue'] >= 10)
                                                        <span class="badge bg-success">Validé</span>
                                                    @else
                                                        <span class="badge bg-danger">Non Validé</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
    // Script pour remplir dynamiquement les listes déroulantes
    document.addEventListener('DOMContentLoaded', function() {
        // Remplir la liste des étudiants
        fetch('/api/etudiants')
            .then(response => response.json())
            .then(data => {
                const etudiantSelect = document.getElementById('code_user');
                data.forEach(etudiant => {
                    const option = document.createElement('option');
                    option.value = etudiant.code_user;
                    option.textContent = etudiant.name;
                    if (etudiant.code_user === '{{ request('code_user') }}') {
                        option.selected = true;
                    }
                    etudiantSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur:', error));

        // Remplir la liste des années scolaires
        fetch('/api/annees-scolaires')
            .then(response => response.json())
            .then(data => {
                const anneeSelect = document.getElementById('code_annee');
                data.forEach(annee => {
                    const option = document.createElement('option');
                    option.value = annee.code_annee;
                    option.textContent = annee.code_annee;
                    if (annee.code_annee === '{{ request('code_annee') }}') {
                        option.selected = true;
                    }
                    anneeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur:', error));
    });
</script>
@endsection
