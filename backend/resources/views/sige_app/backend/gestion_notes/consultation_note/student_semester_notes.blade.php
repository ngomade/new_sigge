@extends('sige_app.backend.template.backend')

@section('title', 'Notes du Semestre')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('consultation.note.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Notes du Semestre</h1>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Étudiant:</strong></td>
                                    <td id="studentInfo">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td id="studentCode">-</td>
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
                                    <td><strong>Moyenne générale:</strong></td>
                                    <td id="generalAverage">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Crédits totaux:</strong></td>
                                    <td id="totalCredits">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notes du Semestre</h5>
                </div>
                <div class="card-body">
                    <div id="semesterNotesContent">
                        <div class="alert alert-info">
                            Sélectionnez un étudiant et un semestre pour afficher les notes.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load student semester notes
    function loadStudentSemesterNotes(studentId, semesterId) {
        // This would make an AJAX call to getNotesEtudiantSemestre method
        // and populate the information and notes
        document.getElementById('studentInfo').textContent = 'Nom de l\'étudiant';
        document.getElementById('studentCode').textContent = studentId;
        document.getElementById('semesterInfo').textContent = 'Semestre 1';
        document.getElementById('yearInfo').textContent = '2022-2023';
        document.getElementById('generalAverage').textContent = '13.2';
        document.getElementById('totalCredits').textContent = '30';
        
        document.getElementById('semesterNotesContent').innerHTML = `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Unité d'Enseignement</th>
                            <th>Élément Constitutif</th>
                            <th>Coefficient</th>
                            <th>Note</th>
                            <th>Date d'évaluation</th>
                            <th>Session d'examen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">Mathématiques</td>
                            <td>Algèbre</td>
                            <td>3</td>
                            <td>14.5</td>
                            <td>15/01/2023</td>
                            <td>Session Normale</td>
                        </tr>
                        <tr>
                            <td>Analyse</td>
                            <td>4</td>
                            <td>12.0</td>
                            <td>20/01/2023</td>
                            <td>Session Normale</td>
                        </tr>
                        <tr>
                            <td rowspan="2">Informatique</td>
                            <td>Programmation</td>
                            <td>3</td>
                            <td>16.0</td>
                            <td>10/03/2023</td>
                            <td>Session Normale</td>
                        </tr>
                        <tr>
                            <td>Base de données</td>
                            <td>4</td>
                            <td>13.5</td>
                            <td>15/03/2023</td>
                            <td>Session Normale</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Moyennes par UE</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Unité d'Enseignement</th>
                                    <th>Moyenne</th>
                                    <th>Crédits</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Mathématiques</td>
                                    <td>13.25</td>
                                    <td>7</td>
                                    <td><span class="badge bg-success">Validé</span></td>
                                </tr>
                                <tr>
                                    <td>Informatique</td>
                                    <td>14.75</td>
                                    <td>7</td>
                                    <td><span class="badge bg-success">Validé</span></td>
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
