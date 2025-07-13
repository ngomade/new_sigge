@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-user-circle'></i> Détails de la candidature - {{ $laboratoire->label_labo }}</h2>
        <a href="{{ route('laboratoires.admin.candidatures', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour à la liste
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Informations personnelles -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class='bx bx-user'></i> Informations personnelles</h5>
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
                            <p><strong>Date candidature :</strong> {{ $candidature->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Statut :</strong>
                                @if($candidature->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($candidature->statut == 'actif')
                                    <span class="badge bg-success">Approuvé</span>
                                @else
                                    <span class="badge bg-danger">Rejeté</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lettre de motivation -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class='bx bx-message-square-text'></i> Lettre de motivation</h5>
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
                        <a href="{{ asset('storage/' . $candidature->cv_path) }}" target="_blank" class="btn btn-outline-primary">
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
                    @php
                        $userId = session('user_id');
                        $userType = session('user_type');
                        $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', session('laboratoire_code'))
                            ->where('statut', 'actif')
                            ->where(function ($q) use ($userId, $userType) {
                                if ($userType === 'externe') {
                                    $q->where('id_user_externe', $userId);
                                } else {
                                    $q->where('id_pers_lab', $userId);
                                }
                            })
                            ->with('roleLabo')
                            ->first();
                        $userRole = $affectation && $affectation->roleLabo ? strtolower($affectation->roleLabo->lib_rl) : null;
                    @endphp
                    @if($candidature->statut == 'en_attente' && ($userRole === 'admin' || $userRole === 'chef_projet'))
                        <div class="d-grid gap-2">
                            <form action="{{ route('laboratoires.admin.candidatures.approve', [$laboratoire->code_lab, $candidature->id_user_ext]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2"
                                        onclick="return confirm('Approuver cette candidature ? Le candidat recevra un email avec ses identifiants.')">
                                    <i class='bx bx-check'></i> Approuver la candidature
                                </button>
                            </form>
                            <form action="{{ route('laboratoires.admin.candidatures.reject', [$laboratoire->code_lab, $candidature->id_user_ext]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100"
                                        onclick="return confirm('Rejeter cette candidature ? Le candidat recevra un email de notification.')">
                                    <i class='bx bx-x'></i> Rejeter la candidature
                                </button>
                            </form>
                        </div>
                    @elseif($candidature->statut == 'en_attente')
                        <div class="alert alert-warning">
                            <i class='bx bx-lock'></i> Vous n'avez pas la permission de traiter cette candidature.
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class='bx bx-info-circle'></i>
                            Cette candidature a déjà été traitée.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class='bx bx-info-circle'></i> Informations</h5>
                </div>
                <div class="card-body">
                    <p><strong>ID candidat :</strong> {{ $candidature->id_user_ext }}</p>
                    <p><strong>Date de début :</strong> {{ $candidature->date_debut ? $candidature->date_debut->format('d/m/Y') : 'Non définie' }}</p>
                    <p><strong>Dernière modification :</strong> {{ $candidature->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
