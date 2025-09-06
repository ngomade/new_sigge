@extends('sige_app.backend.template.backend')

@section('title', 'Modifier la Classe')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('classes.show', $classe->code_class) }}" 
                   class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Modifier la Classe</h1>
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
                    <h5 class="card-title mb-0">Modification de la classe : <strong>{{ $classe->label_class }}</strong></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('classes.update', $classe->code_class) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="label_class" class="form-label">
                                Libellé de la classe <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="label_class" 
                                   name="label_class" 
                                   value="{{ old('label_class', $classe->label_class) }}"
                                   class="form-control @error('label_class') is-invalid @enderror"
                                   placeholder="Ex: L1 Mathématiques"
                                   maxlength="100">
                            @error('label_class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code_user" class="form-label">
                                Utilisateur <span class="text-danger">*</span>
                            </label>
                            <select id="code_user" 
                                    name="code_user" 
                                    class="form-select @error('code_user') is-invalid @enderror">
                                <option value="">Sélectionnez un utilisateur</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->code_user }}" {{ (old('code_user', $classe->code_user) == $user->code_user) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('classes.show', $classe->code_class) }}" 
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
                            <p><strong>Code:</strong> {{ $classe->code_class }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Libellé:</strong> {{ $classe->label_class }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Utilisateur:</strong> {{ $classe->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
