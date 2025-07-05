@extends('sige_app.backend.template.backend')

@section('title', 'Modifier un membre - ' . $laboratoire->label_labo)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class='bx bx-edit'></i> Modifier un membre du laboratoire : {{ $laboratoire->label_labo }}
                    </h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Informations du membre -->
                    <div class="alert alert-info mb-4">
                        <h6><i class='bx bx-info-circle'></i> Informations du membre</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nom :</strong>
                                    @if($membre->persLab)
                                        @if($membre->persLab->type_pers_lab === 'personnel')
                                            @php
                                                $personnel = \App\Models\Personnel::where('code_pers', $membre->persLab->id_pers_lab)->first();
                                            @endphp
                                            {{ $personnel ? $personnel->nom_pers . ' ' . $personnel->prenom_pers : 'N/A' }}
                                        @elseif($membre->persLab->type_pers_lab === 'users')
                                            @php
                                                $user = \App\Models\Users::where('code_user', $membre->persLab->id_pers_lab)->first();
                                            @endphp
                                            {{ $user ? $user->nom_user . ' ' . $user->prenom_user : 'N/A' }}
                                        @elseif($membre->persLab->type_pers_lab === 'user_externe')
                                            @php
                                                $userExt = \App\Models\laboratoires\UserExterne::where('id_user_ext', $membre->persLab->id_pers_lab)->first();
                                            @endphp
                                            {{ $userExt ? $userExt->nom_user_ext . ' ' . $userExt->prenom_user_ext : 'N/A' }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="mb-1"><strong>Type :</strong>
                                    <span class="badge bg-{{ $membre->persLab->type_pers_lab === 'personnel' ? 'primary' : ($membre->persLab->type_pers_lab === 'users' ? 'success' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $membre->persLab->type_pers_lab)) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Code/ID :</strong> {{ $membre->persLab->id_pers_lab }}</p>
                                <p class="mb-0"><strong>Statut actuel :</strong>
                                    <span class="badge bg-{{ $membre->statut === 'actif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($membre->statut) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('labo.laboratoires.membres.update', [$laboratoire, $membre->id_pers_lab]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_rl" class="form-label">Rôle</label>
                                    <select class="form-select" id="id_rl" name="id_rl" required>
                                        <option value="">Sélectionner un rôle</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id_rl }}" {{ old('id_rl', $membre->id_rl) == $role->id_rl ? 'selected' : '' }}>
                                                {{ $role->lib_rl }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select" id="statut" name="statut" required>
                                        <option value="actif" {{ old('statut', $membre->statut) === 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactif" {{ old('statut', $membre->statut) === 'inactif' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_affectation" class="form-label">Date d'affectation</label>
                                    <input type="date" class="form-control" id="date_affectation" name="date_affectation"
                                           value="{{ old('date_affectation', $membre->date_affectation ? $membre->date_affectation->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_fin_affectation" class="form-label">Date de fin d'affectation (optionnel)</label>
                                    <input type="date" class="form-control" id="date_fin_affectation" name="date_fin_affectation"
                                           value="{{ old('date_fin_affectation', $membre->date_fin_affectation ? $membre->date_fin_affectation->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('labo.laboratoires.membres.index', $laboratoire) }}" class="btn btn-outline-secondary">
                                <i class='bx bx-arrow-back'></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
