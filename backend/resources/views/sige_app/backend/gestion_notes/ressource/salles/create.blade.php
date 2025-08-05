@extends('sige_app.backend.template.backend')

@section('title', 'Créer une Salle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Créer une nouvelle salle</h3>
                    <div class="card-tools">
                        <a href="{{ route('ressources.salles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('ressources.salles.store') }}" method="POST">
                    @csrf
                    
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code_salle">Code de la salle *</label>
                                    <input type="text" class="form-control @error('code_salle') is-invalid @enderror" 
                                           id="code_salle" name="code_salle" value="{{ old('code_salle') }}" required>
                                    @error('code_salle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nb_place_salle">Nombre de places *</label>
                                    <input type="number" class="form-control @error('nb_place_salle') is-invalid @enderror" 
                                           id="nb_place_salle" name="nb_place_salle" value="{{ old('nb_place_salle') }}" 
                                           min="1" required>
                                    @error('nb_place_salle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="etat_salle">État de la salle *</label>
                                    <select class="form-control @error('etat_salle') is-invalid @enderror" 
                                            id="etat_salle" name="etat_salle" required>
                                        <option value="1" {{ old('etat_salle') == '1' ? 'selected' : '' }}>Disponible</option>
                                        <option value="0" {{ old('etat_salle') == '0' ? 'selected' : '' }}>Indisponible</option>
                                    </select>
                                    @error('etat_salle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="desc_salle">Description</label>
                                    <textarea class="form-control @error('desc_salle') is-invalid @enderror" 
                                              id="desc_salle" name="desc_salle" 
                                              rows="3">{{ old('desc_salle') }}</textarea>
                                    @error('desc_salle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="{{ route('ressources.salles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
