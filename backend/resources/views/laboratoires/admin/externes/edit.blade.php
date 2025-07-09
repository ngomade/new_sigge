@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-user'></i> Modifier l'utilisateur externe - {{ $laboratoire->label_labo }}</h2>
        <a href="{{ route('laboratoires.admin.externes', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour à la liste
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('laboratoires.admin.externes.update', [$laboratoire->code_lab, $externe->id_user_ext]) }}" enctype="multipart/form-data">
                @csrf
               
                @method('PUT') 

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom_user_ext" class="form-label">Nom *</label>
                            <input type="text" class="form-control @error('nom_user_ext') is-invalid @enderror"
                                   id="nom_user_ext" name="nom_user_ext" value="{{ old('nom_user_ext', $externe->nom_user_ext) }}" required>
                            @error('nom_user_ext')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="prenom_user_ext" class="form-label">Prénom *</label>
                            <input type="text" class="form-control @error('prenom_user_ext') is-invalid @enderror"
                                   id="prenom_user_ext" name="prenom_user_ext" value="{{ old('prenom_user_ext', $externe->prenom_user_ext) }}" required>
                            @error('prenom_user_ext')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email_user_ext" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email_user_ext') is-invalid @enderror"
                                   id="email_user_ext" name="email_user_ext" value="{{ old('email_user_ext', $externe->email_user_ext) }}" required>
                            @error('email_user_ext')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tel_user_ext" class="form-label">Téléphone *</label>
                            <input type="text" class="form-control @error('tel_user_ext') is-invalid @enderror"
                                   id="tel_user_ext" name="tel_user_ext" value="{{ old('tel_user_ext', $externe->tel_user_ext) }}" required>
                            @error('tel_user_ext')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut *</label>
                            <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                <option value="">Sélectionner un statut</option>
                                <option value="actif" {{ old('statut', $externe->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut', $externe->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="id_rl" class="form-label">Rôle *</label>
                            <select class="form-select @error('id_rl') is-invalid @enderror" id="id_rl" name="id_rl" required>
                                <option value="">Sélectionner un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id_rl }}" {{ old('id_rl', $affectation->id_rl ?? '') == $role->id_rl ? 'selected' : '' }}>
                                        {{ $role->lib_rl }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_rl')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début *</label>
                            <input type="date" class="form-control @error('date_debut') is-invalid @enderror"
                                   id="date_debut" name="date_debut" value="{{ old('date_debut', $externe->date_debut) }}" required>
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin (optionnel)</label>
                            <input type="date" class="form-control @error('date_fin') is-invalid @enderror"
                                   id="date_fin" name="date_fin" value="{{ old('date_fin', $externe->date_fin) }}">
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cv" class="form-label">CV (optionnel)</label>
                            <input type="file" class="form-control @error('cv') is-invalid @enderror"
                                   id="cv" name="cv" accept=".pdf,.doc,.docx">
                            <div class="form-text">Formats acceptés : PDF, DOC, DOCX (max 2MB)</div>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="motivation" class="form-label">Motivation (optionnel)</label>
                    <input type="file" class="form-control @error('motivation') is-invalid @enderror"
                           id="motivation" name="motivation" accept=".pdf,.doc,.docx">
                    <div class="form-text">Formats acceptés : PDF, DOC, DOCX (max 2MB)</div>
                    @error('motivation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('laboratoires.admin.externes', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                        <i class='bx bx-x'></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
