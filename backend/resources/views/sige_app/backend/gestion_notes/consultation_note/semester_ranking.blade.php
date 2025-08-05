@extends('sige_app.backend.template.backend')

@section('title', 'Classement du Semestre')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('consultation.note.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Classement du Semestre</h1>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Critères de Classement</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Classe:</strong></td>
                                    <td id="classFilter">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Niveau:</strong></td>
                                    <td id="levelFilter">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Filière:</strong></td>
                                    <td id="fieldFilter">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Total étudiants:</strong></td>
                                    <td id="totalStudents">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Moyenne générale:</strong></td>
                                    <td id="generalAverage">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Meilleure note:</strong></td>
                                    <td id="bestGrade">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Classement des Étudiants</h5>
                </div>
                <div class="card-body">
                    <div id="rankingContent">
                        <div class="alert alert-info">
                            Sélectionnez les critères pour afficher le classement.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load semester ranking
    function loadSemesterRanking(semesterId, yearId, filters = {}) {
        // This would make an AJAX call to getClassementSemestre method
        // and populate the ranking information
        document.getElementById('semesterInfo').textContent = 'Semestre 1';
        document.getElementById('yearInfo').textContent = '2022-2023';
        document.getElementById('classFilter').textContent = filters.class || 'Toutes';
        document.getElementById('levelFilter').textContent = filters.level || 'Tous';
        document.getElementById('fieldFilter').textContent = filters.field || 'Toutes';
        document.getElementById('totalStudents').textContent = '150';
        document.getElementById('generalAverage').textContent = '12.9';
        document.getElementById('bestGrade').textContent = '18.5';
        
        document.getElementById('rankingContent').innerHTML = `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Nom de l'étudiant</th>
                            <th>Code</th>
                            <th>Niveau</th>
                            <th>Filière</th>
                            <th>Moyenne générale</th>
                            <th>EC évalués</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Dupont Jean</td>
                            <td>ETU001</td>
                            <td>L1</td>
                            <td>Génie Informatique</td>
                            <td>16.5</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Martin Marie</td>
                            <td>ETU002</td>
                            <td>L1</td>
                            <td>Génie Informatique</td>
                            <td>15.8</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Bernard Pierre</td>
                            <td>ETU003</td>
                            <td>L1</td>
                            <td>Génie Informatique</td>
                            <td>14.2</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Petit Sophie</td>
                            <td>ETU004</td>
                            <td>L1</td>
                            <td>Génie Informatique</td>
                            <td>13.7</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Durand Luc</td>
                            <td>ETU005</td>
                            <td>L1</td>
                            <td>Génie Informatique</td>
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
                            <td>...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Statistiques du Classement</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">12.9</h4>
                                <p class="mb-0">Moyenne générale</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">18.5</h4>
                                <p class="mb-0">Meilleure note</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">6.2</h4>
                                <p class="mb-0">Note minimale</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">84%</h4>
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
