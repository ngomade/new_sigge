<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>ESTLC-Administration</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="{{asset("sige_app/backend/img/favicon/favicon.ico")}}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
      <!-- Dans la section head -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

      <!-- Avant la fermeture du body -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href=" {{asset("vendor/fonts/boxicons.css")}}" />
    <link rel="stylesheet" href=" {{asset("vendor/css/core.css")}}" />
    <link rel="stylesheet" href=" {{asset("vendor/css/theme-default.css")}}" />
    <link rel="stylesheet" href=" {{asset("sige_app/backend/css/demo.css")}}" />
    <link href="{{asset('/vendor/simple-datatables/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href=" {{asset("vendor/libs/perfect-scrollbar/perfect-scrollbar.css")}}" />
    <link href="{{asset('vendor/remixicon/remixicon.css')}}" rel="stylesheet"/>
    <link href="{{asset('share/css/basic_style.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href=" {{asset('vendor/libs/apex-charts/apex-charts.css')}}" />
    <script src=" {{asset("vendor/js/helpers.js")}}"></script>
    <script src=" {{asset("sige_app/backend/js/config.js")}}"></script>
      <link href="{{ asset('vendor/toastr/build/toastr.css') }}" rel="stylesheet"/>
      <link href="{{ asset('vendor/sweetalert2/dist/sweetalert2.css')  }}" />

    <style>
        /* Forcer SweetAlert2 à être toujours au-dessus des modals Bootstrap */
        .swal2-container, .swal2-container-custom {
            z-index: 20000 !important;
        }
        .swal2-popup {
            z-index: 21000 !important;
        }
    </style>

  </head>

  <body>
    <?php $personnel = \Session::get("pers");?>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @include("sige_app.backend.template.left-side")
        <div class="layout-page">
        @include("sige_app.backend.template.header")

          <div class="content-wrapper">
            <div style=" width: 85%; position: absolute;">
                @if (\Session::has('success'))
                    <div class="alert alert-primary alert-dismissible  show" role="alert" id="success-notif">
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
            <div class="container-xxl flex-grow-1 container-p-y">
                @yield("content")
            </div>

            @include("sige_app.backend.template.footer")

            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>

      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script src=" {{asset("vendor/jquery/jquery.min.js")}}"></script>
    <script src=" {{asset("vendor/libs/popper/popper.js")}}"></script>
    <script src=" {{asset("vendor/js/bootstrap.js")}}"></script>
    <script src=" {{asset("vendor/libs/perfect-scrollbar/perfect-scrollbar.js")}}"></script>
    <script src=" {{asset("vendor/js/menu.js")}}"></script>
    <script src=" {{asset("vendor/libs/apex-charts/apexcharts.js")}}"></script>
    <script src=" {{asset("vendor/simple-datatables/simple-datatables.js")}}"></script>
    <script src="{{asset("vendor/tinymce/tinymce.js")}}"></script>
    <script src=" {{asset("sige_app/backend/js/dashboards-analytics.js")}}"></script>
    <script src=" {{asset("sige_app/frontend/js/script.js")}}"></script>
    <script src=" {{asset("sige_app/backend/js/main.js")}}"></script>
    <script src="{{ asset('vendor/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset("vendor/sweetalert2/dist/sweetalert2.all.js") }}"></script>
    <script>
        $(document).ready(function() {
            // Configuration de base de Toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        });
    </script>

    @yield("js")
    <script>
        $(document).ready(function() {
            $('#success-notif').fadeOut(12000);
            $('#danger-notif').fadeOut(12000);
        });
        tinymce.init({
            selector:'textarea.tinymce-editor',
        });
    </script>
    @stack('scripts')
  </body>
</html>
