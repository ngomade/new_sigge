@extends('sige_app.backend.template.backend')

@section('title', 'Résultats par Niveau')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('consultation.note.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Résultats par Niveau</h1>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations du Niveau</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Niveau:</strong></td>
                                    <td id="levelInfo">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Classe:</strong></td>
                                    <td id="classInfo">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Semestre:</strong></td>
                                    <td id="semesterInfo">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Année:</strong></td>
                                    <td id="yearInfo">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Nombre d'étudiants:</strong></td>
                                    <td id="studentCount">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Moyenne du niveau:</strong></td>
                                    <td id="levelAverage">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Résultats des Étudiants</h5>
                </div>
                <div class="card-body">
                    <div id="levelResultsContent">
                        <div class="alert alert-info">
                            Sélectionnez un niveau pour afficher les résultats.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load level results
    function loadLevelResults(levelId, semesterId, yearId) {
        // This would make an AJAX call to getResultatsNiveau method
        // and populate the level information and results
        document.getElementById('levelInfo').textContent = 'Licence 1';
        document.getElementById('classInfo').textContent = 'Génie Informatique';
        document.getElementById('semesterInfo').textContent = 'Semestre 1';
        document.getElementById('yearInfo').textContent = '2022-2023';
        document.getElementById('studentCount').textContent = '75';
        document.getElementById('levelAverage').textContent = '13.2';
        
        document.getElementById('levelResultsContent').innerHTML = `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Nom de l'étudiant</th>
                            <th>Code</th>
                            <th>Classe</th>
                            <th>Moyenne générale</th>
                            <th>EC évalués</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Dupont Jean</td>
                            <td>ETU001</td>
                            <td>L1-GI</td>
                            <td>16.5</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Martin Marie</td>
                            <td>ETU002</td>
                            <td>L1-GI</td>
                            <td>15.8</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Bernard Pierre</td>
                            <td>ETU003</td>
                            <td>L1-GI</td>
                            <td>14.2</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Petit Sophie</td>
                            <td>ETU004</td>
                            <td>L1-GI</td>
                            <td>13.7</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Durand Luc</td>
                            <td>ETU005</td>
                            <td>L1-GI</td>
                            <td>12.9</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>...</td>
                            <td>...</td>
                            <td>...</td>
                            <td>...</td>
                            <td>...</td>
                            <td>...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Statistiques du Niveau</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">13.2</h4>
                                <p class="mb-0">Moyenne du niveau</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">18.5</h4>
                                <p class="mb-0">Note maximale</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">7.8</h4>
                                <p class="mb-0">Note minimale</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">86%</h4>
                                <p class="mb-0">Taux de réussite</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
</script>
@endsection
