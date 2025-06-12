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

    @yield("style")
</head>

<body>
    <div class="modal fade" id="connexionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success" style="color: white">
                    <h5 class="modal-title">Authentification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/login" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p class="text-center" style="text-align: center; color: blue;">Veuillez renseigner votre code et votre mot de passe</p>
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
                                <a class="text-link  text-danger" href="#" data-bs-toggle="modal" data-bs-target="#requestModal">J'ai oublié mon matricule ou mon mot de passe !!!</a>
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
    <div class="modal fade" id="requestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success" style="color: white">
                    <h5 class="modal-title">Recupération de mot de passe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/recuperation_pwd" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p  style="text-align: center; color: blue;">Veuillez renseigner Votre nom complet, et votre email</p>
                        <p  style="text-align: center; color: rgb(233, 99, 99); font-style: italic;">Nous vous enverrons par mail vos identifiants de connexion.</p>
                        <div class="row m-3">
                            <div class="col-sm-10 mb-4">
                            <input type="text" class="form-control" placeholder="Entrez votre matricule " name="nom_user" id="nom_user" required>
                            </div>
                        </div>
                        <div class="row m-3 mb-0">
                            <div class="col-sm-10 mb-4">
                            <input type="email" class="form-control" placeholder="email" name="email_user" id="email_user" required>
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
    <div style=" width: 100%; position: absolute;">
        @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-notif">
            <i class="bi bi-check-circle me-1"></i>
            {{ \Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (\Session::has('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="danger-notif">
            <i class="bi bi-exclamation-octagon me-1"></i>
            {{ \Session::get('errors') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    </div>
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
