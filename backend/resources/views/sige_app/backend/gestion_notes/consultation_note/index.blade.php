@extends('sige_app.backend.template.backend')

@section('title', 'Consultation des Notes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4">Consultation des Notes</h1>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sélection des critères</h5>
                </div>
                <div class="card-body">
                    <form id="consultationForm">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type_consultation" class="form-label">Type de Consultation</label>
                                <select name="type_consultation" id="type_consultation" class="form-select" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="etudiant">Notes d'un étudiant</option>
                                    <option value="classe">Résultats d'une classe</option>
                                    <option value="niveau">Résultats d'un niveau</option>
                                    <option value="filiere">Résultats d'une filière</option>
                                    <option value="classement">Classement</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="annee_scolaire" class="form-label">Année Scolaire</label>
                                <select name="annee_scolaire" id="annee_scolaire" class="form-select" required>
                                    <option value="">Sélectionnez une année</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="semestre" class="form-label">Semestre</label>
                                <select name="semestre" id="semestre" class="form-select" required>
                                    <option value="">Sélectionnez un semestre</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="row" id="criteriaFields">
                            <!-- Dynamic fields will be added here based on type of consultation -->
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Consulter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4" id="resultContainer" style="display: none;">
                <div class="card-header">
                    <h5 class="card-title mb-0" id="resultTitle">Résultats</h5>
                </div>
                <div class="card-body" id="resultContent">
                    <!-- Results will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission
        document.getElementById('consultationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            loadResults();
        });
        
        // Handle type consultation change
        document.getElementById('type_consultation').addEventListener('change', function() {
            loadCriteriaFields(this.value);
        });
    });
    
    function loadCriteriaFields(type) {
        const criteriaFields = document.getElementById('criteriaFields');
        criteriaFields.innerHTML = '';
        
        switch(type) {
            case 'etudiant':
                criteriaFields.innerHTML = `
                    <div class="col-md-12 mb-3">
                        <label for="etudiant" class="form-label">Étudiant</label>
                        <select name="etudiant" id="etudiant" class="form-select" required>
                            <option value="">Sélectionnez un étudiant</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                `;
                break;
            case 'classe':
                criteriaFields.innerHTML = `
                    <div class="col-md-12 mb-3">
                        <label for="classe" class="form-label">Classe</label>
                        <select name="classe" id="classe" class="form-select" required>
                            <option value="">Sélectionnez une classe</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                `;
                break;
            case 'niveau':
                criteriaFields.innerHTML = `
                    <div class="col-md-12 mb-3">
                        <label for="niveau" class="form-label">Niveau</label>
                        <select name="niveau" id="niveau" class="form-select" required>
                            <option value="">Sélectionnez un niveau</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                `;
                break;
            case 'filiere':
                criteriaFields.innerHTML = `
                    <div class="col-md-12 mb-3">
                        <label for="filiere" class="form-label">Filière</label>
                        <select name="filiere" id="filiere" class="form-select" required>
                            <option value="">Sélectionnez une filière</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                `;
                break;
            case 'classement':
                criteriaFields.innerHTML = `
                    <div class="col-md-6 mb-3">
                        <label for="classe_filter" class="form-label">Classe (facultatif)</label>
                        <select name="classe_filter" id="classe_filter" class="form-select">
                            <option value="">Toutes les classes</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="niveau_filter" class="form-label">Niveau (facultatif)</label>
                        <select name="niveau_filter" id="niveau_filter" class="form-select">
                            <option value="">Tous les niveaux</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                `;
                break;
        }
    }
    
    function loadResults() {
        // This function would make AJAX calls to the controller methods
        // and display the results in the resultContent div
        const resultContainer = document.getElementById('resultContainer');
        const resultContent = document.getElementById('resultContent');
        resultContainer.style.display = 'block';
        resultContent.innerHTML = '<div class="alert alert-info">Chargement des résultats...</div>';
        
        // Simulate loading
        setTimeout(function() {
            resultContent.innerHTML = `
                <div class="alert alert-success">
                    <h4>Résultats chargés avec succès</h4>
                    <p>Les résultats s'afficheraient ici en fonction des critères sélectionnés.</p>
                </div>
            `;
        }, 1000);
    }
</script>
@endsection
