@extends('laboratoires.public.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class='bx bx-log-in'></i> Connexion au Laboratoire
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <h6><i class='bx bx-info-circle'></i> Types d'utilisateurs autorisés :</h6>
                        <ul class="mb-0">
                            <li><strong>Personnel :</strong> Login et mot de passe du personnel</li>
                            <li><strong>Étudiants :</strong> Login et mot de passe des étudiants</li>
                            <li><strong>Utilisateurs externes :</strong> Email et mot de passe des comptes approuvés</li>
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

                    <form method="POST" action="{{ route('laboratoires.login', $laboratoire->code_lab) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="login" class="form-label">Login / Email</label>
                            <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-log-in'></i> Se connecter
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-2">Vous n'avez pas de compte ?</p>
                        <a href="{{ route('laboratoires.candidature.create', $laboratoire->code_lab) }}" class="btn btn-outline-primary">
                            <i class='bx bx-user-plus'></i> Postuler pour rejoindre le laboratoire
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
