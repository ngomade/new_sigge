@extends('sige_app.backend.template.backend')

@section('title', 'Modifier le Niveau')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('niveaux.show', $niveau->code_niveau) }}" 
                   class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Modifier le Niveau</h1>
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
                    <h5 class="card-title mb-0">Modification du niveau : <strong>{{ $niveau->label_niveau }}</strong></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('niveaux.update', $niveau->code_niveau) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="label_niveau" class="form-label">
                                Libellé du niveau <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="label_niveau" 
                                   name="label_niveau" 
                                   value="{{ old('label_niveau', $niveau->label_niveau) }}"
                                   class="form-control @error('label_niveau') is-invalid @enderror"
                                   placeholder="Ex: Licence 1"
                                   maxlength="128">
                            @error('label_niveau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code_class" class="form-label">
                                Classe
                            </label>
                            <select id="code_class" 
                                    name="code_class" 
                                    class="form-select @error('code_class') is-invalid @enderror">
                                <option value="">Sélectionnez une classe (optionnel)</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->code_class }}" {{ (old('code_class', $niveau->code_class) == $classe->code_class) ? 'selected' : '' }}>
                                        {{ $classe->label_class }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('niveaux.show', $niveau->code_niveau) }}" 
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
                            <p><strong>Code:</strong> {{ $niveau->code_niveau }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Libellé:</strong> {{ $niveau->label_niveau }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Classe:</strong> {{ $niveau->classe->label_class ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
