<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>ESTLC-Bienvenue</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="{{asset('frontend/img/logo.png')}}" rel="icon">
    <link href="{{asset('frontend/img/logo.png')}}" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="{{asset('vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
    <!--<link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">-->

    <link href="{{asset('frontend/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('concours/style.css')}}" rel="stylesheet">
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
                            <input type="text" class="form-control" placeholder="Code" name="ca_code" id="ca_code" required>
                            </div>
                        </div>
                        <div class="row m-3 mb-0">
                            <div class="col-sm-10 mb-4">
                            <input type="password" class="form-control" placeholder="Mot de passe" name="ca_pwd" id="ca_pwd" required>
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
                    <h5 class="modal-title">Authentification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/request_information" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p class="text-center" style="text-align: center; color: blue;">Veuillez renseigner Votre nom complet, votre et votre email</p>
                        <p class="text-center" style="text-align: center; color: rgb(233, 99, 99); font-style: italic;">Nous vous enverrons par mail vos identifiants de connexion.</p>
                        <div class="row m-3">
                            <div class="col-sm-10 mb-4">
                            <input type="text" class="form-control" placeholder="Votre nom complet" name="ca_name" id="ca_name" required>
                            </div>
                        </div>
                        <div class="row m-3 mb-0">
                            <div class="col-sm-10 mb-4">
                            <input type="email" class="form-control" placeholder="email" name="ca_email" id="ca_email" required>
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
    @include("concours.frontend.template.header")

    @yield("content")

    @include("concours.frontend.template.footer")

    <script src="{{asset('vendor/purecounter/purecounter_vanilla.js')}}"></script>
    <script src="{{asset('vendor/aos/aos.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('vendor/php-email-form/validate.js')}}"></script>
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('concours/script.js')}}"></script>

    <script src="{{asset('frontend/js/main.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#success-notif').fadeOut(5000);
            $('#danger-notif').fadeOut(5000);
        });
    </script>
</body>

</html>
