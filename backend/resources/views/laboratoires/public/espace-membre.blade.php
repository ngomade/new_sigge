@extends('laboratoires.public.layout')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Informations du laboratoire -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class='bx bx-building'></i> {{ $laboratoire->label_labo }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class='bx bx-info-circle'></i> Informations générales</h6>
                            <p><strong>Code :</strong> {{ $laboratoire->code_lab }}</p>
                            <p><strong>Responsable :</strong> {{ $laboratoire->responsable_labo }}</p>
                            <p><strong>Statut :</strong>
                                <span class="badge bg-{{ $laboratoire->statut_labo === 'actif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($laboratoire->statut_labo) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class='bx bx-calendar'></i> Dates</h6>
                            <p><strong>Création :</strong> {{ $laboratoire->date_creation_labo ? \Carbon\Carbon::parse($laboratoire->date_creation_labo)->format('d/m/Y') : 'Non définie' }}</p>
                            <p><strong>Dernière modification :</strong> {{ \Carbon\Carbon::parse($laboratoire->updated_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($laboratoire->desc_labo)
                    <hr>
                    <h6><i class='bx bx-file-text'></i> Description du laboratoire</h6>
                    <div class="bg-light p-3 rounded">
                        {!! $laboratoire->getCleanDescAttribute() !!}
                    </div>
                    @endif

                    @if($laboratoire->axes_recherche)
                    <hr>
                    <h6><i class='bx bx-target-lock'></i> Axes de recherche</h6>
                    <div class="bg-light p-3 rounded">
                        {!! $laboratoire->getCleanAxesAttribute() !!}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Projets du laboratoire -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class='bx bx-briefcase'></i> Projets en cours ({{ $projets->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($projets->count() > 0)
                        <div class="row">
                            @foreach($projets as $projet)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">{{ $projet->titre_projet }}</h6>
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($projet->desc_projet, 100) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $projet->statut_projet === 'en_cours' ? 'warning' : 'success' }}">
                                                {{ ucfirst(str_replace('_', ' ', $projet->statut_projet)) }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $projet->debut_projet ? \Carbon\Carbon::parse($projet->debut_projet)->format('d/m/Y') : 'Non définie' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Aucun projet en cours pour le moment.</p>
                    @endif
                </div>
            </div>

            <!-- Équipements du laboratoire -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class='bx bx-cog'></i> Équipements disponibles ({{ $equipements->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($equipements->count() > 0)
                        <div class="row">
                            @foreach($equipements as $equipement)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title text-info">{{ $equipement->nom_equip }}</h6>
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($equipement->desc_equip, 100) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $equipement->etat === 'disponible' ? 'success' : 'danger' }}">
{{ ucfirst($equipement->etat) }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $equipement->marque_equipement }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Aucun équipement disponible pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar avec informations utilisateur -->
        <div class="col-lg-4">
            <!-- Profil utilisateur -->
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class='bx bx-user'></i> Mon profil
                    </h5>
                </div>
                <div class="card-body">
                    @if($user)
                        <div class="text-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class='bx bx-user' style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h6 class="text-center">{{ $user->nom_user_ext ?? $user->nom_pers ?? $user->nom_user }} {{ $user->prenom_user_ext ?? $user->prenom_pers ?? $user->prenom_user }}</h6>
                        <p class="text-center text-muted mb-3">
                            <span class="badge bg-{{ $userType === 'personnel' ? 'primary' : ($userType === 'user' ? 'success' : 'warning') }}">
                                {{ ucfirst($userType) }}
                            </span>
                        </p>
                        <hr>
                        <div class="mb-2">
                            <i class='bx bx-envelope text-muted'></i>
                            <small>{{ $user->email_user_ext ?? $user->email_pers ?? $user->email_user }}</small>
                        </div>
                        @if($user->tel_user_ext ?? $user->first_phone_pers ?? $user->first_phone_user)
                        <div class="mb-2">
                            <i class='bx bx-phone text-muted'></i>
                            <small>{{ $user->tel_user_ext ?? $user->first_phone_pers ?? $user->first_phone_user }}</small>
                        </div>
                        @endif
                        <div class="d-grid mt-3">
                            <a href="{{ route('laboratoires.profil', $laboratoire->code_lab) }}" class="btn btn-outline-primary btn-sm">
                                <i class='bx bx-edit'></i> Modifier mon profil
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center">Informations utilisateur non disponibles.</p>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class='bx bx-menu'></i> Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('laboratoires.show', $laboratoire->code_lab) }}" class="btn btn-outline-primary">
                            <i class='bx bx-home'></i> Retour à l'accueil
                        </a>
                        <form action="{{ route('laboratoires.logout', $laboratoire->code_lab) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class='bx bx-log-out'></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
