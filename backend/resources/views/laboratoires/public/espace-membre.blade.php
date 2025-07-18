@extends('laboratoires.public.layout')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Informations du laboratoire -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('chat.index', $laboratoire->code_lab) }}" class="btn btn-outline-primary">
                    <i class='bx bx-chat'></i> Salon de discussion
                </a>
            </div>
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
                            <p><strong>Responsable :</strong>
                                @php
                                    $admin = $laboratoire->admin_pers_labo;
                                    $nomResponsable = 'Non défini';
                                    $emailResponsable = '';
                                    $telephoneResponsable = '';
                                    if (is_object($admin) && method_exists($admin, 'getNomCompletAttribute')) {
                                        $nomResponsable = $admin->nom_complet;
                                        $emailResponsable = $admin->email ?? '';
                                        $telephoneResponsable = $admin->first_phone_pers ?? $admin->first_phone_user ?? '';
                                    } elseif (is_string($admin)) {
                                        $persLab = \App\Models\laboratoires\PersLab::find($admin);
                                        if ($persLab) {
                                            $nomResponsable = $persLab->nom_complet;
                                            $emailResponsable = $persLab->email;
                                            $telephoneResponsable = $persLab->telephone ?? '';
                                        } else {
                                            $userExt = \App\Models\laboratoires\UserExterne::find($admin);
                                            if ($userExt) {
                                                $nomResponsable = $userExt->nom_user_ext . ' ' . $userExt->prenom_user_ext;
                                                $emailResponsable = $userExt->email_user_ext;
                                                $telephoneResponsable = $userExt->tel_user_ext ?? '';
                                            }
                                        }
                                    }
                                @endphp
                                {{ $nomResponsable }}
                                @if($emailResponsable)
                                    <br><small class="text-muted">
                                        <i class='bx bx-envelope'></i>
                                        <a href="mailto:{{ $emailResponsable }}" class="text-decoration-none">
                                            {{ $emailResponsable }}
                                        </a>
                                    </small>
                                @endif
                                @if($telephoneResponsable)
                                    <br><small class="text-muted">
                                        <i class='bx bx-phone'></i>
                                        <a href="tel:{{ $telephoneResponsable }}" class="text-decoration-none">
                                            {{ $telephoneResponsable }}
                                        </a>
                                    </small>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class='bx bx-calendar'></i> Dates</h6>
                            <p><strong>Création :</strong> {{ $laboratoire->created_at ? \Carbon\Carbon::parse($laboratoire->created_at)->format('d/m/Y') : 'Non définie' }}</p>
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
                    @php
                        $userId = session('user_id');
                        $userType = session('user_type');
                        $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
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
                        $isAdmin = $affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin';
                    @endphp
                    @if($projets->count() > 0)
                        <div class="row">
                            @foreach($projets as $projet)
                                @php
                                    $estMembre = $isAdmin ? true : $projet->participants()
                                        ->where(function($q) use ($userId, $userType) {
                                            if ($userType === 'externe') {
                                                $q->where('id_user_ext', $userId);
                                            } else {
                                                $q->where('id_pers_lab', $userId);
                                            }
                                        })->exists();
                                @endphp
                                @if($estMembre)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}" class="text-decoration-underline">{{ $projet->theme_projet }}</a>
                                        </h6>
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
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Aucun projet en cours pour le moment.</p>
                    @endif
                </div>
            </div>

            <!-- Publications du laboratoire -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class='bx bx-book'></i> Publications récentes
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($publications) && $publications->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($publications as $publication)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $publication->titre_publi }}</strong>
                                            <br>
                                            <span class="text-muted small">{{ ucfirst($publication->type_publi) }} | {{ $publication->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <a href="{{ route('laboratoires.admin.publications.show', [$laboratoire->code_lab, $publication->code_publi]) }}" class="btn btn-outline-primary btn-sm"><i class='bx bx-show'></i> Voir</a>
                                    </div>
                                    @if($publication->code_projet)
                                        <div class="small text-muted mt-1">Projet lié :
                                            @if($publication->projetLabo)
                                                <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $publication->code_projet]) }}" class="fw-bold text-decoration-underline">{{ $publication->projetLabo->theme_projet }}</a>
                                            @else
                                                <span class="fw-bold">{{ $publication->code_projet }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-end mt-2">
                            <a href="{{ route('laboratoires.admin.publications.index', $laboratoire->code_lab) }}" class="btn btn-primary btn-sm"><i class='bx bx-list-ul'></i> Voir toutes les publications</a>
                        </div>
                    @else
                        <p class="text-muted text-center">Aucune publication récente.</p>
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
                                <div class="card h-100 border-0 shadow-sm d-flex flex-row align-items-center">
                                    <div class="p-2">
                                        @if($equipement->image_path)
                                            <img src="{{ asset('storage/' . $equipement->image_path) }}" alt="Image de l'équipement" class="img-fluid rounded shadow" style="max-height: 60px; max-width: 80px;">
                                        @else
                                            <i class='bx bx-cog' style="font-size: 2rem; color: var(--primary-color);"></i>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title text-info">
                                            <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="text-decoration-underline">{{ $equipement->nom_equip }}</a>
                                        </h6>
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
                            <span class="badge bg-{{ $userType === 'personnel' ? 'primary' : ($userType === 'users' ? 'success' : 'warning') }}">
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
