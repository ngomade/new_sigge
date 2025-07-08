@extends('sige_app.frontend.layouts.app')

@section('title', 'Détails de la candidature')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            <i class='bx bx-user'></i> Candidature de {{ $candidature->nom_user_ext }} {{ $candidature->prenom_user_ext }}
                        </h4>
                        <div>
                            <a href="{{ route('labo.candidatures.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Informations personnelles -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><i class='bx bx-user-circle'></i> Informations personnelles</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nom :</strong> {{ $candidature->nom_user_ext }}</p>
                                            <p><strong>Prénom :</strong> {{ $candidature->prenom_user_ext }}</p>
                                            <p><strong>Email :</strong> {{ $candidature->email_user_ext }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Téléphone :</strong> {{ $candidature->tel_user_ext }}</p>
                                            <p><strong>Laboratoire :</strong> {{ $candidature->laboratoire->label_labo }}</p>
                                            <p><strong>Date candidature :</strong> {{ $candidature->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lettre de motivation -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><i class='bx bx-message-square-detail'></i> Lettre de motivation</h5>
                                </div>
                                <div class="card-body">
                                    @if($candidature->motivation_path)
                                        <a href="{{ asset('storage/' . $candidature->motivation_path) }}" target="_blank" class="btn btn-outline-primary">
                                            <i class='bx bx-download'></i> Télécharger la lettre de motivation
                                        </a>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class='bx bx-file'></i>
                                                Fichier : {{ basename($candidature->motivation_path) }}
                                            </small>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class='bx bx-exclamation-triangle'></i>
                                            Aucune lettre de motivation fournie.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- CV -->
                            @if($candidature->cv_path)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5><i class='bx bx-file'></i> CV</h5>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ asset('storage/' . $candidature->cv_path) }}"
                                           target="_blank" class="btn btn-primary">
                                            <i class='bx bx-download'></i> Télécharger le CV
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class='bx bx-cog'></i> Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <form action="{{ route('labo.candidatures.approve', $candidature->id_user_ext) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100 mb-2"
                                                    onclick="return confirm('Approuver cette candidature ? Le candidat recevra un email avec ses identifiants.')">
                                                <i class='bx bx-check'></i> Approuver la candidature
                                            </button>
                                        </form>

                                        <form action="{{ route('labo.candidatures.reject', $candidature->id_user_ext) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger w-100"
                                                    onclick="return confirm('Rejeter cette candidature ? Le candidat recevra un email de notification.')">
                                                <i class='bx bx-x'></i> Rejeter la candidature
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5><i class='bx bx-info-circle'></i> Statut</h5>
                                </div>
                                <div class="card-body">
                                    @if($candidature->statut === 'en_attente')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($candidature->statut === 'actif')
                                        <span class="badge bg-success">Approuvé</span>
                                    @elseif($candidature->statut === 'rejeté')
                                        <span class="badge bg-danger">Rejeté</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
