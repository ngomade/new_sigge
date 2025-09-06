@extends('sige_app.backend.template.backend')

@section('title', 'Créer une Évaluation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('evaluations.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Créer une Évaluation</h1>
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

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de l'Évaluation</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('evaluations.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code_ec" class="form-label">Élément Constitutif <span class="text-danger">*</span></label>
                                <select name="code_ec" id="code_ec" class="form-select" required>
                                    <option value="">Sélectionnez un EC</option>
                                    @foreach($ecs as $ec)
                                        <option value="{{ $ec->code_ec }}" {{ old('code_ec') == $ec->code_ec ? 'selected' : '' }}>
                                            {{ $ec->intitule_ec }} ({{ $ec->ue->intitule_ue ?? '' }} - {{ $ec->ue->semestre->label_sem ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="code_examen" class="form-label">Examen <span class="text-danger">*</span></label>
                                <select name="code_examen" id="code_examen" class="form-select" required>
                                    <option value="">Sélectionnez un examen</option>
                                    @foreach($examens as $examen)
                                        <option value="{{ $examen->code_examen }}" {{ old('code_examen') == $examen->code_examen ? 'selected' : '' }}>
                                            {{ $examen->sessionExamen->label_session ?? '' }} - {{ $examen->type_evaluation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_evaluation" class="form-label">Date d'Évaluation <span class="text-danger">*</span></label>
                                <input type="date" name="date_evaluation" id="date_evaluation" class="form-control" value="{{ old('date_evaluation') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h5>Notes des Étudiants</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Étudiant</th>
                                            <th>Note (0-20)</th>
                                            <th>Observations</th>
                                        </tr>
                                    </thead>
                                    <tbody id="evaluations-table">
                                        @foreach($etudiants as $etudiant)
                                            <tr>
                                                <td>
                                                    {{ $etudiant->name }}
                                                    <input type="hidden" name="evaluations[{{ $loop->index }}][code_user]" value="{{ $etudiant->code_user }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" min="0" max="20" 
                                                           name="evaluations[{{ $loop->index }}][note_eval]" 
                                                           class="form-control" 
                                                           value="{{ old('evaluations.'.$loop->index.'.note_eval') }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="evaluations[{{ $loop->index }}][code_ano]" 
                                                           class="form-control" 
                                                           value="{{ old('evaluations.'.$loop->index.'.code_ano') }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script pour gérer l'affichage dynamique des étudiants selon l'EC sélectionné
    document.getElementById('code_ec').addEventListener('change', function() {
        const ecCode = this.value;
        if (ecCode) {
            // Appel AJAX pour récupérer les étudiants inscrits à cet EC
            fetch(`/evaluations/get-etudiants-by-ec/${ecCode}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('evaluations-table');
                    tableBody.innerHTML = '';
                    
                    data.etudiants.forEach((etudiant, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>
                                ${etudiant.name}
                                <input type="hidden" name="evaluations[${index}][code_user]" value="${etudiant.code_user}">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" max="20" 
                                       name="evaluations[${index}][note_eval]" 
                                       class="form-control">
                            </td>
                            <td>
                                <input type="text" name="evaluations[${index}][code_ano]" 
                                       class="form-control">
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Erreur:', error));
        }
    });
</script>
@endsection
