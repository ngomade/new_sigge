@extends('sige_app.backend.template.backend')

@section('title', 'Résultats par Filière')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('consultation.note.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Résultats par Filière</h1>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de la Filière</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Filière:</strong></td>
                                    <td id="fieldInfo">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td id="fieldCode">-</td>
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
                                    <td><strong>Moyenne de la filière:</strong></td>
                                    <td id="fieldAverage">-</td>
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
                    <div id="fieldResultsContent">
                        <div class="alert alert-info">
                            Sélectionnez une filière pour afficher les résultats.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load field results
    function loadFieldResults(fieldId, semesterId, yearId) {
        // This would make an AJAX call to getResultatsFiliere method
        // and populate the field information and results
        document.getElementById('fieldInfo').textContent = 'Génie Informatique';
        document.getElementById('fieldCode').textContent = fieldId;
        document.getElementById('semesterInfo').textContent = 'Semestre 1';
        document.getElementById('yearInfo').textContent = '2022-2023';
        document.getElementById('studentCount').textContent = '150';
        document.getElementById('fieldAverage').textContent = '12.9';
        
        document.getElementById('fieldResultsContent').innerHTML = `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Nom de l'étudiant</th>
                            <th>Code</th>
                            <th>Niveau</th>
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
                            <td>L1</td>
                            <td>L1-GI</td>
                            <td>16.5</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Martin Marie</td>
                            <td>ETU002</td>
                            <td>L1</td>
                            <td>L1-GI</td>
                            <td>15.8</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Bernard Pierre</td>
                            <td>ETU003</td>
                            <td>L1</td>
                            <td>L1-GI</td>
                            <td>14.2</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Petit Sophie</td>
                            <td>ETU004</td>
                            <td>L1</td>
                            <td>L1-GI</td>
                            <td>13.7</td>
                            <td>8/8</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Durand Luc</td>
                            <td>ETU005</td>
                            <td>L1</td>
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
                            <td>...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Statistiques par Niveau</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Niveau</th>
                                    <th>Nombre d'étudiants</th>
                                    <th>Moyenne</th>
                                    <th>Note maximale</th>
                                    <th>Note minimale</th>
                                    <th>Taux de réussite</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>L1</td>
                                    <td>75</td>
                                    <td>13.2</td>
                                    <td>18.5</td>
                                    <td>7.8</td>
                                    <td>86%</td>
                                </tr>
                                <tr>
                                    <td>L2</td>
                                    <td>50</td>
                                    <td>12.5</td>
                                    <td>17.8</td>
                                    <td>8.2</td>
                                    <td>82%</td>
                                </tr>
                                <tr>
                                    <td>L3</td>
                                    <td>25</td>
                                    <td>12.1</td>
                                    <td>16.9</td>
                                    <td>9.1</td>
                                    <td>78%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
    }
</script>
@endsection
