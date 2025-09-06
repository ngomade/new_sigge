@extends('sige_app.backend.template.backend')

@section('title', 'Bulletin de l\'Étudiant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('consultation.note.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Bulletin de l'Étudiant</h1>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Établissement d'Enseignement Supérieur</h5>
                            <p class="mb-0">Bulletin de Notes - Semestre 1</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-0">Année Académique: 2022-2023</p>
                            <p class="mb-0">Date de génération: {{ date('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nom de l'étudiant:</strong></td>
                                    <td id="studentName">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Code étudiant:</strong></td>
                                    <td id="studentCode">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Moyenne générale:</strong></td>
                                    <td id="generalAverage" class="fw-bold">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Crédits obtenus:</strong></td>
                                    <td id="creditsObtained">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Détail des Unités d'Enseignement</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Unité d'Enseignement</th>
                                            <th>Élément Constitutif</th>
                                            <th>Crédits</th>
                                            <th>Moyenne EC</th>
                                            <th>Moyenne UE</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportCardContent">
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Sélectionnez un étudiant pour afficher son bulletin.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Statistiques</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td>Total des crédits:</td>
                                            <td id="totalCredits" class="text-end">-</td>
                                        </tr>
                                        <tr>
                                            <td>Crédits obtenus:</td>
                                            <td id="obtainedCredits" class="text-end">-</td>
                                        </tr>
                                        <tr>
                                            <td>Statut du semestre:</td>
                                            <td id="semesterStatus" class="text-end fw-bold">-</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Observations</h6>
                                </div>
                                <div class="card-body">
                                    <p id="observations">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer le bulletin
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load student report card
    function loadStudentReportCard(studentId, semesterId, yearId) {
        // This would make an AJAX call to getBulletinEtudiant method
        // and populate the report card
        document.getElementById('studentName').textContent = 'Nom de l\'étudiant';
        document.getElementById('studentCode').textContent = studentId;
        document.getElementById('generalAverage').textContent = '14.2';
        document.getElementById('creditsObtained').textContent = '28';
        document.getElementById('totalCredits').textContent = '30';
        document.getElementById('obtainedCredits').textContent = '28';
        document.getElementById('semesterStatus').innerHTML = '<span class="text-success">Validé</span>';
        document.getElementById('observations').textContent = 'Bonnes performances académiques. Continuez ainsi.';
        
        document.getElementById('reportCardContent').innerHTML = `
            <tr>
                <td rowspan="2">Mathématiques</td>
                <td>Algèbre</td>
                <td>3</td>
                <td>14.5</td>
                <td rowspan="2">13.25</td>
                <td rowspan="2"><span class="badge bg-success">Validé</span></td>
            </tr>
            <tr>
                <td>Analyse</td>
                <td>4</td>
                <td>12.0</td>
            </tr>
            <tr>
                <td rowspan="2">Informatique</td>
                <td>Programmation</td>
                <td>3</td>
                <td>16.0</td>
                <td rowspan="2">14.75</td>
                <td rowspan="2"><span class="badge bg-success">Validé</span></td>
            </tr>
            <tr>
                <td>Base de données</td>
                <td>4</td>
                <td>13.5</td>
            </tr>
        `;
    }
</script>
@endsection
