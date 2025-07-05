@extends('laboratoires.public.layout')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">{{ $laboratoire->label_labo }}</h1>
                    <div class="lead mb-4">{!! $laboratoire->desc_labo !!}</div>
                    <div class="d-flex gap-3">
                        <a href="#projets" class="btn btn-light btn-lg">
                            <i class='bx bx-briefcase'></i> Nos Projets
                        </a>
                        <a href="{{ route('laboratoires.candidature.create', $laboratoire->code_lab) }}" class="btn btn-outline-light btn-lg">
                            <i class='bx bx-user-plus'></i> Rejoindre le Labo
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    @if($laboratoire->logo_labo)
                        <img src="{{ asset('storage/' . $laboratoire->logo_labo) }}" alt="Logo {{ $laboratoire->label_labo }}" class="img-fluid rounded" style="max-width: 300px;">
                    @else
                        <div class="bg-white bg-opacity-25 rounded p-5">
                            <i class='bx bx-flask' style="font-size: 8rem;"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Présentation Section -->
    <section id="presentation" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title">À propos de notre laboratoire</h2>
                    <div class="lead">{!! $laboratoire->desc_labo !!}</div>

                    @if($laboratoire->axes_recherche)
                        <h4 class="mt-4">Axes de recherche</h4>
                        <div>{!! $laboratoire->axes_recherche !!}</div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class='bx bx-info-circle'></i> Informations</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class='bx bx-envelope'></i> {{ $laboratoire->email_labo }}</li>
                                <li class="mb-2"><i class='bx bx-phone'></i> {{ $laboratoire->tel_labo }}</li>
                                <li class="mb-2"><i class='bx bx-map'></i> {{ $laboratoire->adresse_labo }}</li>
                                @if($laboratoire->sigle)
                                    <li class="mb-2"><i class='bx bx-bookmark'></i> {{ $laboratoire->sigle }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projets Section -->
    <section id="projets" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Nos Projets en Cours</h2>
            <div class="row">
                @forelse($laboratoire->projets as $projet)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $projet->theme_projet }}</h5>
                                <div class="card-text">{!! Str::limit(strip_tags($projet->description_projet), 150) !!}</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">{{ $projet->statut_projet }}</span>
                                    <small class="text-muted">
                                        <i class='bx bx-calendar'></i>
                                        {{ $projet->debut_projet ? $projet->debut_projet->format('M Y') : 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Aucun projet en cours pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Équipements Section -->
    <section id="equipements" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Nos Équipements</h2>
            <div class="row">
                @forelse($laboratoire->equipements as $equipement)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class='bx bx-cog' style="font-size: 3rem; color: var(--primary-color);"></i>
                                </div>
                                <h6 class="card-title">{{ $equipement->nom_equip }}</h6>
                                <div class="card-text small">{!! $equipement->desc_equip !!}</div>
                                <span class="badge bg-{{ $equipement->etat === 'disponible' ? 'success' : 'warning' }}">
                                    {{ ucfirst($equipement->etat) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Aucun équipement répertorié pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Membres Section -->
    <section id="membres" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Notre Équipe</h2>
            <div class="row">
                @php
                    $hasActiveMembers = false;
                @endphp
                            @forelse($laboratoire->membres as $membre)
                                @foreach($membre->affectations as $affectation)
                                    @if($affectation->code_lab === $laboratoire->code_lab && $affectation->statut === 'actif')
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <div class="card member-card">
                                                <div class="member-avatar">
                                                    <i class='bx bx-user'></i>
                                                </div>
                                                <h6 class="card-title">
                                                    @if($membre->type_pers_lab === 'personnel')
                                                        {{ \App\Models\Personnel::where('code_pers', $membre->id_pers_lab)->first()->nom_pers ?? 'Membre' }}
                                                    @elseif($membre->type_pers_lab === 'user')
                                                        {{ \App\Models\Users::where('code_user', $membre->id_pers_lab)->first()->nom_user ?? 'Membre' }}
                                                    @else
                                                        Membre
                                                    @endif
                                                </h6>
                                                @if($affectation->roleLabo)
                                                    <p class="text-muted small">{{ $affectation->roleLabo->lib_rl }}</p>
                                                @endif
                                                <small class="text-muted">
                                                    <i class='bx bx-calendar'></i>
                                                    {{ $affectation->date_affectation ? $affectation->date_affectation->format('M Y') : 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                @if(!$hasActiveMembers)
                    <div class="col-12 text-center">
                        <p class="text-muted">Aucun membre répertorié pour le moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));">
        <div class="container text-center text-white">
            <h2 class="mb-4">Rejoignez notre équipe !</h2>
            <p class="lead mb-4">Vous souhaitez contribuer à nos projets de recherche ? Postulez dès maintenant !</p>
            <a href="{{ route('laboratoires.candidature.create', $laboratoire->code_lab) }}" class="btn btn-light btn-lg">
                <i class='bx bx-user-plus'></i> Postuler maintenant
            </a>
        </div>
    </section>
@endsection
