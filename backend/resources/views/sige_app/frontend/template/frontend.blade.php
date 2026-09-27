<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>ESTLC-Bienvenue</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{asset('share/img/logo_estlc.png')}}" rel="icon">
    <link href="{{asset('share/img/logo_estlc.png')}}" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="{{asset('vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <link href="{{asset('sige_app/frontend/css/style.css')}}" rel="stylesheet">

    <link href="{{asset('css/app.css')}}" rel="stylesheet">

    <style>
        /* Modales d'authentification — cohérentes avec le thème du site */
        .auth-modal .modal-content {
            border: none;
            border-radius: var(--app-radius-lg, 16px);
            overflow: hidden;
            box-shadow: 0 20px 45px var(--app-shadow, rgba(17,61,53,.14));
        }
        .auth-modal .modal-header {
            background: var(--app-primary-dark, #11583f);
            color: #fff;
            border: none;
            padding: 1.25rem 1.5rem;
        }
        .auth-modal .modal-header .modal-title {
            font-weight: 700;
        }
        .auth-modal .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .auth-modal .modal-body {
            padding: 1.75rem 1.5rem 0.5rem;
        }
        .auth-modal .modal-body .intro-text {
            text-align: center;
            color: var(--app-text-muted, #61756c);
            font-size: 0.92rem;
            margin-bottom: 1.5rem;
        }
        .auth-modal .modal-body .intro-warning {
            text-align: center;
            color: var(--app-accent-hover, #c79612);
            font-style: italic;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }
        .auth-modal .form-control {
            border-radius: var(--app-radius-md, 12px);
            border: 1px solid var(--app-border, #dce9e2);
            padding: 0.65rem 1rem;
        }
        .auth-modal .form-control:focus {
            border-color: var(--app-primary, #0e8f74);
            box-shadow: 0 0 0 0.2rem var(--app-primary-soft, #dff2e9);
        }
        .auth-modal .text-link {
            display: inline-block;
            font-size: 0.85rem;
            text-decoration: none;
        }
        .auth-modal .text-link:hover { text-decoration: underline; }
        .auth-modal .modal-footer {
            border: none;
            padding: 1rem 1.5rem 1.5rem;
        }
        .auth-modal .btn-success {
            background-color: var(--app-primary, #0e8f74);
            border-color: var(--app-primary, #0e8f74);
        }
        .auth-modal .btn-success:hover {
            background-color: var(--app-primary-dark, #11583f);
            border-color: var(--app-primary-dark, #11583f);
        }

        /* Alertes de session — affichées dans le flux normal, juste sous le header,
           donc jamais en chevauchement avec celui-ci quelle que soit sa hauteur */
        .session-alerts {
            width: 100%;
            max-width: 720px;
            margin: 1rem auto 0;
            padding: 0 1rem;
        }
        .session-alerts .alert {
            border: none;
            border-radius: var(--app-radius-md, 12px);
            box-shadow: 0 8px 24px var(--app-shadow-soft, rgba(17,61,53,.08));
            display: flex;
            align-items: center;
        }
    </style>

    @yield("style")
</head>

<body>
    <div class="modal fade auth-modal" id="connexionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-shield-lock-fill me-2"></i>Authentification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/login" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p class="intro-text">Veuillez renseigner votre code et votre mot de passe</p>
                        <div class="row m-3">
                            <div class="col-sm-10 mb-4">
                                <input type="text" class="form-control" placeholder="Matricule" name="login_user" id="login_user" required>
                            </div>
                        </div>
                        <div class="row m-3 mb-0">
                            <div class="col-sm-10 mb-2">
                                <input type="password" class="form-control" placeholder="Mot de passe" name="pwd_user" id="pwd_user" required>
                            </div>
                        </div>
                        <div class="row m-3 mb-0">
                            <div class="col-sm-12 mb-2">
                                <a class="text-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#requestModal">J'ai oublié mon matricule ou mon mot de passe !!!</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-0">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Connexion</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade auth-modal" id="requestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-key-fill me-2"></i>Récupération de mot de passe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/recuperation_pwd" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p class="intro-text">Veuillez renseigner votre nom complet et votre email</p>
                        <p class="intro-warning">Nous vous enverrons par mail vos identifiants de connexion.</p>
                        <div class="row m-3">
                            <div class="col-sm-10 mb-4">
                                <input type="text" class="form-control" placeholder="Entrez votre matricule" name="nom_user" id="nom_user" required>
                            </div>
                        </div>
                        <div class="row m-3 mb-0">
                            <div class="col-sm-10 mb-4">
                                <input type="email" class="form-control" placeholder="Email" name="email_user" id="email_user" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-0">
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include("sige_app.frontend.template.header")

    @if (\Session::has('success') || \Session::has('errors'))
        <div class="session-alerts">
            @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-notif">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ \Session::get('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (\Session::has('errors'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="danger-notif">
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    {{ \Session::get('errors') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    @endif

    @yield("content")

    @include("sige_app.frontend.template.footer")

    <script src="{{asset('vendor/purecounter/purecounter_vanilla.js')}}"></script>
    <script src="{{asset('vendor/aos/aos.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('vendor/php-email-form/validate.js')}}"></script>
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>

    <script src="{{asset('sige_app/frontend/js/main.js')}}"></script>
    <script src="{{asset('sige_app/frontend/js/script.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#success-notif').fadeOut(15000);
            $('#danger-notif').fadeOut(15000);
        });
    </script>
     @yield("js")
</body>

</html>