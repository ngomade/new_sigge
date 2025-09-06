@extends('sige_app.backend.template.backend')

@section('title', 'Modifier l\'Assignation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('assignations.show', $assignation->code_ass) }}" 
                   class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Modifier l'Assignation</h1>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

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
                    <h5 class="card-title mb-0">Modification de l'assignation</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('assignations.update', $assignation->code_ass) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="code_class" class="form-label">
                                Classe <span class="text-danger">*</span>
                            </label>
                            <select id="code_class" 
                                    name="code_class" 
                                    class="form-select @error('code_class') is-invalid @enderror">
                                <option value="">Sélectionnez une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->code_class }}" {{ (old('code_class', $assignation->code_class) == $classe->code_class) ? 'selected' : '' }}>
                                        {{ $classe->label_class }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code_pers" class="form-label">
                                Enseignant <span class="text-danger">*</span>
                            </label>
                            <select id="code_pers" 
                                    name="code_pers" 
                                    class="form-select @error('code_pers') is-invalid @enderror">
                                <option value="">Sélectionnez un enseignant</option>
                                @foreach($personnels as $personnel)
                                    <option value="{{ $personnel->code_pers }}" {{ (old('code_pers', $assignation->code_pers) == $personnel->code_pers) ? 'selected' : '' }}>
                                        {{ $personnel->nom_pers }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_pers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code_ec" class="form-label">
                                Élément Constitutif <span class="text-danger">*</span>
                            </label>
                            <select id="code_ec" 
                                    name="code_ec" 
                                    class="form-select @error('code_ec') is-invalid @enderror">
                                <option value="">Sélectionnez un EC</option>
                                @foreach($ecs as $ec)
                                    <option value="{{ $ec->code_ec }}" {{ (old('code_ec', $assignation->code_ec) == $ec->code_ec) ? 'selected' : '' }}>
                                        {{ $ec->intitule_ec }} ({{ $ec->ue->intitule_ue ?? '' }} - {{ $ec->ue->semestre->label_sem ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('code_ec')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('assignations.show', $assignation->code_ass) }}" 
                               class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations actuelles -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations actuelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Classe:</strong> {{ $assignation->classe->label_class ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Enseignant:</strong> {{ $assignation->personnel->nom_pers ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Élément Constitutif:</strong> {{ $assignation->ec->intitule_ec ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>UE:</strong> {{ $assignation->ec->ue->intitule_ue ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
