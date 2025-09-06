@extends('sige_app.backend.template.backend')

@section('title', 'Modifier une Période')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('periodes.show', [$periode->code_salle, $periode->code_ec]) }}" class="text-secondary me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="h3 mb-0">Modifier une Période</h1>
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
                    <form method="POST" action="{{ route('periodes.update', [$periode->code_salle, $periode->code_ec]) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="new_code_salle" value="{{ old('new_code_salle', $periode->code_salle) }}">
                        <input type="hidden" name="new_code_ec" value="{{ old('new_code_ec', $periode->code_ec) }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_code_salle" class="form-label">Salle <span class="text-danger">*</span></label>
                                <select name="new_code_salle" id="new_code_salle" class="form-select" required>
                                    <option value="">Sélectionnez une salle</option>
                                    @foreach($salles as $salle)
                                        <option value="{{ $salle->code_salle }}" {{ (old('new_code_salle', $periode->code_salle) == $salle->code_salle) ? 'selected' : '' }}>
                                            {{ $salle->code_salle }} ({{ $salle->nb_place_salle }} places)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="new_code_ec" class="form-label">Élément Constitutif <span class="text-danger">*</span></label>
                                <select name="new_code_ec" id="new_code_ec" class="form-select" required>
                                    <option value="">Sélectionnez un EC</option>
                                    @foreach($ecs as $ec)
                                        <option value="{{ $ec->code_ec }}" {{ (old('new_code_ec', $periode->code_ec) == $ec->code_ec) ? 'selected' : '' }}>
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
                                       value="{{ old('debut_periode', $periode->debut_periode ? \Carbon\Carbon::parse($periode->debut_periode)->format('Y-m-d\TH:i') : '') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fin_periode" class="form-label">Date Fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="fin_periode" id="fin_periode" class="form-control" 
                                       value="{{ old('fin_periode', $periode->fin_periode ? \Carbon\Carbon::parse($periode->fin_periode)->format('Y-m-d\TH:i') : '') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jour_periode" class="form-label">Jour de la Semaine <span class="text-danger">*</span></label>
                                <select name="jour_periode" id="jour_periode" class="form-select" required>
                                    <option value="">Sélectionnez un jour</option>
                                    <option value="1" {{ (old('jour_periode', $periode->jour_periode) == 1) ? 'selected' : '' }}>Lundi</option>
                                    <option value="2" {{ (old('jour_periode', $periode->jour_periode) == 2) ? 'selected' : '' }}>Mardi</option>
                                    <option value="3" {{ (old('jour_periode', $periode->jour_periode) == 3) ? 'selected' : '' }}>Mercredi</option>
                                    <option value="4" {{ (old('jour_periode', $periode->jour_periode) == 4) ? 'selected' : '' }}>Jeudi</option>
                                    <option value="5" {{ (old('jour_periode', $periode->jour_periode) == 5) ? 'selected' : '' }}>Vendredi</option>
                                    <option value="6" {{ (old('jour_periode', $periode->jour_periode) == 6) ? 'selected' : '' }}>Samedi</option>
                                    <option value="7" {{ (old('jour_periode', $periode->jour_periode) == 7) ? 'selected' : '' }}>Dimanche</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="duree_periode" class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                                <input type="number" name="duree_periode" id="duree_periode" class="form-control" 
                                       value="{{ old('duree_periode', $periode->duree_periode) }}" min="30" max="480" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('periodes.show', [$periode->code_salle, $periode->code_ec]) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à Jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations actuelles -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations Actuelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Salle:</strong></td>
                                    <td>{{ $periode->salle->code_salle ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Élément Constitutif:</strong></td>
                                    <td>{{ $periode->ec->intitule_ec ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unité d'Enseignement:</strong></td>
                                    <td>{{ $periode->ec->ue->intitule_ue ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Date Début:</strong></td>
                                    <td>{{ $periode->debut_periode ? \Carbon\Carbon::parse($periode->debut_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date Fin:</strong></td>
                                    <td>{{ $periode->fin_periode ? \Carbon\Carbon::parse($periode->fin_periode)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jour:</strong></td>
                                    <td>
                                        @switch($periode->jour_periode)
                                            @case(1) Lundi @break
                                            @case(2) Mardi @break
                                            @case(3) Mercredi @break
                                            @case(4) Jeudi @break
                                            @case(5) Vendredi @break
                                            @case(6) Samedi @break
                                            @case(7) Dimanche @break
                                            @default N/A
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
