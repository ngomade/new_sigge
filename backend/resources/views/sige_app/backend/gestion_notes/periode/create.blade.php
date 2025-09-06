@extends('sige_app.backend.template.backend')

@section('title', 'Créer une Période')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('periodes.index') }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Créer une Période</h1>
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
                    <h5 class="card-title mb-0">Informations de la Période</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('periodes.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code_salle" class="form-label">Salle <span class="text-danger">*</span></label>
                                <select name="code_salle" id="code_salle" class="form-select" required>
                                    <option value="">Sélectionnez une salle</option>
                                    @foreach($salles as $salle)
                                        <option value="{{ $salle->code_salle }}" {{ old('code_salle') == $salle->code_salle ? 'selected' : '' }}>
                                            {{ $salle->code_salle }} ({{ $salle->nb_place_salle }} places)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

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
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="debut_periode" class="form-label">Date Début <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="debut_periode" id="debut_periode" class="form-control" 
                                       value="{{ old('debut_periode') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fin_periode" class="form-label">Date Fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="fin_periode" id="fin_periode" class="form-control" 
                                       value="{{ old('fin_periode') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jour_periode" class="form-label">Jour de la Semaine <span class="text-danger">*</span></label>
                                <select name="jour_periode" id="jour_periode" class="form-select" required>
                                    <option value="">Sélectionnez un jour</option>
                                    <option value="1" {{ old('jour_periode') == 1 ? 'selected' : '' }}>Lundi</option>
                                    <option value="2" {{ old('jour_periode') == 2 ? 'selected' : '' }}>Mardi</option>
                                    <option value="3" {{ old('jour_periode') == 3 ? 'selected' : '' }}>Mercredi</option>
                                    <option value="4" {{ old('jour_periode') == 4 ? 'selected' : '' }}>Jeudi</option>
                                    <option value="5" {{ old('jour_periode') == 5 ? 'selected' : '' }}>Vendredi</option>
                                    <option value="6" {{ old('jour_periode') == 6 ? 'selected' : '' }}>Samedi</option>
                                    <option value="7" {{ old('jour_periode') == 7 ? 'selected' : '' }}>Dimanche</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="duree_periode" class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                                <input type="number" name="duree_periode" id="duree_periode" class="form-control" 
                                       value="{{ old('duree_periode') }}" min="30" max="480" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('periodes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
