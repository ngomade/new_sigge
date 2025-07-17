@extends('laboratoires.public.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class='bx bx-user-circle'></i> Mon profil
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class='bx bx-check-circle'></i> {{ session('success') }}
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

                    @if($user)
                        <form method="POST" action="{{ route('laboratoires.profil.update', $laboratoire->code_lab) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom"
                                               value="{{ $user->nom_user_ext ?? $user->nom_pers ?? $user->nom_user }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom"
                                               value="{{ $user->prenom_user_ext ?? $user->prenom_pers ?? $user->prenom_user }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ $user->email_user_ext ?? $user->email_pers ?? $user->email_user }}" required>
                            </div>

                                                        <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone"
                                       value="{{ $user->tel_user_ext ?? $user->first_phone_pers ?? $user->first_phone_user }}">
                            </div>

                            <!-- Informations spécifiques selon le type d'utilisateur -->
                            @if($userType === 'personnel')
                                <div class="alert alert-info">
                                    <h6><i class='bx bx-info-circle'></i> Informations Personnel</h6>
                                    <p class="mb-1"><strong>Code :</strong> {{ $user->code_pers }}</p>
                                    <p class="mb-1"><strong>Sexe :</strong> {{ ucfirst($user->sexe_pers) }}</p>
                                    <p class="mb-1"><strong>Date de naissance :</strong> {{ $user->date_naissance_pers ? $user->date_naissance_pers->format('d/m/Y') : 'Non définie' }}</p>
                                    <p class="mb-0"><strong>Lieu de naissance :</strong> {{ $user->lieu_naissance_pers ?? 'Non défini' }}</p>
                                </div>
                            @elseif($userType === 'users')
                                <div class="alert alert-info">
                                    <h6><i class='bx bx-info-circle'></i> Informations Étudiant</h6>
                                    <p class="mb-1"><strong>Code :</strong> {{ $user->code_user }}</p>
                                    <p class="mb-1"><strong>Sexe :</strong> {{ ucfirst($user->sexe_user) }}</p>
                                    <p class="mb-1"><strong>Date de naissance :</strong> {{ $user->date_naissance_user ? $user->date_naissance_user->format('d/m/Y') : 'Non définie' }}</p>
                                    <p class="mb-0"><strong>Lieu de naissance :</strong> {{ $user->lieu_naissance_user ?? 'Non défini' }}</p>
                                </div>
                            @elseif($userType === 'externe')
                                <div class="alert alert-info">
                                    <h6><i class='bx bx-info-circle'></i> Informations Utilisateur Externe</h6>
                                    <p class="mb-1"><strong>ID :</strong> {{ $user->id_user_ext }}</p>

                                    <p class="mb-1"><strong>Statut :</strong>
                                        <span class="badge bg-{{ $user->statut === 'actif' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($user->statut) }}
                                        </span>
                                    </p>
                                    @if($user->motivation)
                                    <p class="mb-1"><strong>Motivation :</strong></p>
                                    <div class="bg-light p-2 rounded">
                                        {{ Str::limit($user->motivation, 200) }}
                                    </div>
                                    @endif
                                </div>
                                @if($userType === 'externe')
                                    <div class="mb-3">
                                        <label for="pwd" class="form-label">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="pwd" name="pwd" minlength="6" autocomplete="new-password" placeholder="Laisser vide pour ne pas changer">
                                        <small class="form-text text-muted">Laisser vide si vous ne souhaitez pas modifier votre mot de passe.</small>
                                    </div>
                                @endif
                            @endif

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('laboratoires.espace.membre', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                                    <i class='bx bx-arrow-back'></i> Retour à l'espace membre
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-save'></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class='bx bx-error-circle'></i> Informations utilisateur non disponibles.
                        </div>
                        <a href="{{ route('laboratoires.espace.membre', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                            <i class='bx bx-arrow-back'></i> Retour à l'espace membre
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
