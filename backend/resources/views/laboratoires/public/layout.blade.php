<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $laboratoire->label_labo ?? 'Laboratoire' }} - ESTLC</title>

    @php
        $isAdmin = false;
        if(session('user_id') && session('laboratoire_code') === $laboratoire->code_lab && session('user_type') === 'personnel') {
            $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
                ->where('id_pers_lab', session('user_id'))
                ->where('statut', 'actif')
                ->where('date_affectation', '<=', now())
                ->where(function($query) {
                    $query->whereNull('date_fin_affectation')
                          ->orWhere('date_fin_affectation', '>=', now());
                })
                ->with('roleLabo')
                ->first();
            if($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }
    @endphp

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #696cff;
            --primary-hover: #5f61e6;
            --primary-dark: #595cd9;
            --secondary-color: #6c757d;
            --accent-color: #dc3545;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --text-color: #697a8d;
            --text-dark: #566a7f;
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            padding: 80px 0;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem 0 rgba(105, 108, 255, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem 0 rgba(105, 108, 255, 0.4);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 0.125rem 0.25rem 0 rgba(105, 108, 255, 0.4);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: white;
        }

        .section-title {
            color: var(--text-dark);
            font-weight: bold;
            margin-bottom: 30px;
        }

        .member-card {
            text-align: center;
            padding: 20px;
        }

        .member-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 2rem;
        }

        .badge.bg-primary {
            background-color: var(--primary-color) !important;
        }

        .text-muted {
            color: var(--text-color) !important;
        }

        .bg-light {
            background-color: var(--light-bg) !important;
        }

        .navbar-light {
            background-color: white !important;
            color: var(--text-color);
        }

        .navbar-light .navbar-nav .nav-link {
            color: var(--text-color);
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ session('user_id') && session('laboratoire_code') === $laboratoire->code_lab ? ($isAdmin ? route('laboratoires.admin.dashboard', $laboratoire->code_lab) : route('laboratoires.espace.membre', $laboratoire->code_lab)) : route('laboratoires.show', $laboratoire->code_lab) }}">
                <i class='bx bx-flask'></i> {{ $laboratoire->label_labo }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        @if(session('user_id') && session('laboratoire_code') === $laboratoire->code_lab && session('user_type') === 'personnel' && $isAdmin)
                            <a class="nav-link" href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}">
                                Présentation
                            </a>
                        @endif
                    </li>
                    <li class="nav-item">
                        @if(session('user_id') && session('laboratoire_code') === $laboratoire->code_lab && session('user_type') === 'personnel' && $isAdmin)
                            <a class="nav-link" href="{{ route('laboratoires.admin.projets', $laboratoire->code_lab) }}">
                                Projets
                            </a>
                        @endif
                    </li>
                    <li class="nav-item">
                        @if(session('user_id') && session('laboratoire_code') === $laboratoire->code_lab && session('user_type') === 'personnel' && $isAdmin)
                            <a class="nav-link" href="{{ route('laboratoires.admin.equipements', $laboratoire->code_lab) }}">
                                Équipements
                            </a>
                        @endif
                    </li>
                    <li class="nav-item">
                        @if(session('user_id') && session('laboratoire_code') === $laboratoire->code_lab && session('user_type') === 'personnel' && $isAdmin)
                            <a class="nav-link" href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}">
                                Membres
                            </a>
                        @endif
                    </li>
                    <li class="nav-item">
                        @if(session('user_id') && session('laboratoire_code') === $laboratoire->code_lab)
                            <a class="nav-link" href="{{ $isAdmin ? route('labo.publications.index', $laboratoire->code_lab) : route('labo.publications.index') }}">
                                Publications
                            </a>
                        @else
                            <a class="nav-link" href="#projets">Publications</a>
                        @endif
                    </li>
                </ul>

                <div class="navbar-nav">
                    @if(session('user_id') && session('laboratoire_code') === $laboratoire->code_lab)
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bx-user'></i> {{ session('user_name') }}
                                <span class="badge bg-secondary ms-1">{{ $isAdmin ? 'Admin' : ucfirst(session('user_type')) }}</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><span class="dropdown-item-text text-muted">Connecté en tant que</span></li>
                                <li><span class="dropdown-item-text fw-bold">{{ session('user_name') }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                @if($isAdmin)
                                    <li>
                                        <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="dropdown-item">
                                            <i class='bx bx-cog'></i> Dashboard admin
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}" class="dropdown-item">
                                            <i class='bx bx-group'></i> Gérer les membres
                                        </a>
                                    </li>
                                @endif
                                {{-- <li>
                                    <a href="{{ $isAdmin ? route('laboratoires.admin.dashboard', $laboratoire->code_lab) : route('laboratoires.espace.membre', $laboratoire->code_lab) }}" class="dropdown-item">
                                        <i class='bx bx-user-circle'></i> {{ $isAdmin ? 'Espace admin' : 'Espace membre' }}
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="{{ route('laboratoires.profil', $laboratoire->code_lab) }}" class="dropdown-item">
                                        <i class='bx bx-edit'></i> Mon profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('laboratoires.logout', $laboratoire->code_lab) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class='bx bx-log-out'></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('laboratoires.login.form', $laboratoire->code_lab) }}" class="btn btn-outline-primary me-2">
                            <i class='bx bx-log-in'></i> Connexion
                        </a>
                        <a href="{{ route('laboratoires.candidature.create', $laboratoire->code_lab) }}" class="btn btn-primary">
                            <i class='bx bx-user-plus'></i> Postuler
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main>
        @include('laboratoires.partials.alerts')
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ $laboratoire->label_labo }}</h5>
                    <div>{!! $laboratoire->short_desc !!}</div>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><i class='bx bx-envelope'></i> {{ $laboratoire->email_labo }}</p>
                    <p><i class='bx bx-phone'></i> {{ $laboratoire->tel_labo }}</p>
                    <p><i class='bx bx-map'></i> {{ $laboratoire->adresse_labo }}</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; {{ date('Y') }} {{ $laboratoire->label_labo }} - ESTLC. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
