@extends('sige_app.backend.template.backend')

@section('title', 'Notes de l\'Étudiant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('consultation.note.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Notes de l'Étudiant</h1>
            </div>
            
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
                                    <td id="studentName">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td id="studentCode">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Nombre d'évaluations:</strong></td>
                                    <td id="evaluationCount">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Moyenne générale:</strong></td>
                                    <td id="averageGrade">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Détail des Notes</h5>
                </div>
                <div class="card-body">
                    <div id="notesContent">
                        <div class="alert alert-info">
                            Sélectionnez un étudiant pour afficher ses notes.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load student notes
    function loadStudentNotes(studentId) {
        // This would make an AJAX call to getNotesEtudiant method
        // and populate the student information and notes
        document.getElementById('studentName').textContent = 'Nom de l\'étudiant';
        document.getElementById('studentCode').textContent = studentId;
        document.getElementById('evaluationCount').textContent = '15';
        document.getElementById('averageGrade').textContent = '12.5';
        
        document.getElementById('notesContent').innerHTML = `
            <div class="accordion" id="notesAccordion">
                <!-- Semester 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSem1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSem1">
                            Semestre 1
                        </button>
                    </h2>
                    <div id="collapseSem1" class="accordion-collapse collapse show" data-bs-parent="#notesAccordion">
                        <div class="accordion-body">
                            <!-- UE 1 -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">UE 1 - Mathématiques</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Élément Constitutif</th>
                                                    <th>Coefficient</th>
                                                    <th>Note</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Algèbre</td>
                                                    <td>3</td>
                                                    <td>14.5</td>
                                                    <td>15/01/2023</td>
                                                </tr>
                                                <tr>
                                                    <td>Analyse</td>
                                                    <td>4</td>
                                                    <td>12.0</td>
                                                    <td>20/01/2023</td>
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
                    <h2 class="accordion-header" id="headingSem2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSem2">
                            Semestre 2
                        </button>
                    </h2>
                    <div id="collapseSem2" class="accordion-collapse collapse" data-bs-parent="#notesAccordion">
                        <div class="accordion-body">
                            <!-- UE 2 -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">UE 2 - Informatique</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Élément Constitutif</th>
                                                    <th>Coefficient</th>
                                                    <th>Note</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Programmation</td>
                                                    <td>3</td>
                                                    <td>16.0</td>
                                                    <td>10/03/2023</td>
                                                </tr>
                                                <tr>
                                                    <td>Base de données</td>
                                                    <td>4</td>
                                                    <td>13.5</td>
                                                    <td>15/03/2023</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
</script>
@endsection
