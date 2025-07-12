@extends('laboratoires.public.layout')

@php
    $laboratoire = $invitation->laboratoire;
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <div class="mb-3">
                        <i class='bx bx-link-alt' style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="mb-1">Invitation au Laboratoire</h4>
                    <p class="mb-0">{{ $invitation->laboratoire->label_labo }}</p>
                </div>

                <div class="card-body p-4">
                    <!-- Informations sur l'invitation -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <h6><i class='bx bx-info-circle'></i> Informations sur l'invitation :</h6>
                                <ul class="mb-0">
                                    <li><strong>Laboratoire :</strong> {{ $invitation->laboratoire->label_labo }}</li>
                                    @if($invitation->roleLabo)
                                        <li><strong>Rôle proposé :</strong> {{ $invitation->roleLabo->lib_rl }}</li>
                                    @endif
                                    <li><strong>Date de fin d'affectation :</strong> {{ \Carbon\Carbon::parse($invitation->date_fin_affectation)->format('d/m/Y') }}</li>
                                    <li><strong>Lien valide jusqu'au :</strong> {{ \Carbon\Carbon::parse($invitation->date_expiration)->format('d/m/Y à H:i') }}</li>
                                    <li><strong>Utilisations :</strong> {{ $invitation->nombre_utilisations_actuelles }} / {{ $invitation->nombre_utilisations_max }} personnes</li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="mb-2">
                                    <small class="text-muted">Code QR du lien</small>
                                </div>
                                <div class="qr-code-container">
                                    {!! $invitation->qr_code !!}
                                </div>
                                <small class="text-muted d-block mt-2">Scannez pour partager</small>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="alert alert-warning mb-4">
                        <h6><i class='bx bx-shield-check'></i> Vérification d'identité :</h6>
                        <p class="mb-2">Pour rejoindre ce laboratoire, vous devez vous authentifier avec vos identifiants de l'école :</p>
                        <ul class="mb-0">
                            <li><strong>Personnel :</strong> Utilisez votre login et mot de passe du personnel</li>
                            <li><strong>Étudiant :</strong> Utilisez votre login et mot de passe étudiant</li>
                        </ul>
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

                    <!-- Formulaire de connexion -->
                    <form action="{{ route('laboratoires.invitation.traiter', $invitation->token) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="login" class="form-label">
                                <i class='bx bx-user'></i> Login
                            </label>
                            <input type="text" name="login" id="login" class="form-control form-control-lg"
                                   placeholder="Votre login" required autofocus>
                            <div class="form-text">Entrez votre login personnel ou étudiant</div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class='bx bx-lock-alt'></i> Mot de passe
                            </label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg"
                                   placeholder="Votre mot de passe" required>
                            <div class="form-text">Entrez votre mot de passe personnel ou étudiant</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class='bx bx-check-circle'></i> Rejoindre le laboratoire
                            </button>
                        </div>
                    </form>

                    <!-- Liens utiles -->
                    <div class="text-center mt-4">
                        <hr>
                        <p class="text-muted mb-2">
                            <i class='bx bx-help-circle'></i> Besoin d'aide ?
                        </p>
                        <div class="btn-group" role="group">
                            <a href="{{ route('laboratoires.show', $invitation->laboratoire->code_lab) }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class='bx bx-info-circle'></i> Voir le laboratoire
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                                <i class='bx bx-home'></i> Accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de sécurité -->
            <div class="card mt-3 border-0 bg-light">
                <div class="card-body text-center">
                    <small class="text-muted">
                        <i class='bx bx-shield'></i>
                        Vos informations de connexion ne sont utilisées que pour vérifier votre identité.
                        Aucune donnée personnelle n'est stockée lors de ce processus.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control {
    border-radius: 10px;
}

.btn {
    border-radius: 10px;
}

.alert {
    border-radius: 10px;
}

.qr-code-container {
    display: inline-block;
    padding: 10px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.qr-code-container svg {
    max-width: 100%;
    height: auto;
}
</style>
@endsection
