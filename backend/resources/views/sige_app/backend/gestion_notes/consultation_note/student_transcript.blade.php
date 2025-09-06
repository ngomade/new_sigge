@extends('sige_app.backend.template.backend')

@section('title', 'Relevé de Notes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('consultation.note.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Relevé de Notes</h1>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Établissement d'Enseignement Supérieur</h5>
                            <p class="mb-0">Relevé de Notes</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-0">Année Académique: <span id="academicYear">-</span></p>
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
                    </div>
                    
                    <div id="transcriptContent">
                        <div class="alert alert-info">
                            Sélectionnez un étudiant pour afficher son relevé de notes.
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer le relevé
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load student transcript
    function loadStudentTranscript(studentId, yearId) {
        // This would make an AJAX call to getReleveNotesEtudiant method
        // and populate the transcript
        document.getElementById('academicYear').textContent = '2022-2023';
        document.getElementById('studentName').textContent = 'Nom de l\'étudiant';
        document.getElementById('studentCode').textContent = studentId;
        
        document.getElementById('transcriptContent').innerHTML = `
            <div class="accordion" id="transcriptAccordion">
                <!-- Semester 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTranscriptSem1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTranscriptSem1">
                            Semestre 1
                        </button>
                    </h2>
                    <div id="collapseTranscriptSem1" class="accordion-collapse collapse show" data-bs-parent="#transcriptAccordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Unité d'Enseignement</th>
                                            <th>Élément Constitutif</th>
                                            <th>Coefficient</th>
                                            <th>Note</th>
                                            <th>Date</th>
                                            <th>Session</th>
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
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Moyennes du Semestre 1</h6>
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
                        </div>
                    </div>
                </div>
                
                <!-- Semester 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTranscriptSem2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTranscriptSem2">
                            Semestre 2
                        </button>
                    </h2>
                    <div id="collapseTranscriptSem2" class="accordion-collapse collapse" data-bs-parent="#transcriptAccordion">
                        <div class="accordion-body">
                            <div class="alert alert-info text-center">
                                Les notes du semestre 2 seront disponibles à la fin de la session d'examen.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
</script>
@endsection
