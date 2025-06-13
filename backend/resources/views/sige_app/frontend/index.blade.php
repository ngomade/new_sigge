@extends("sige_app.frontend.template.frontend")
@section('js')
    <script>
       /* $(document).ready(function() {
            $('#ConcoursModal').modal("show");
        });*/
        $(document).ready(function(){
            $("#NewPasswordModal").modal('show');
        });
        function validatePasswords() {
        const password = document.getElementById("npwd").value;
        const confirmPassword = document.getElementById("npwdc").value;
        const message = document.getElementById("message");

        if (password !== confirmPassword) {
            message.textContent = "Les mots de passe ne correspondent pas.";
            return false; // Empêche l'envoi du formulaire
        } else {
            message.textContent = ""; // Réinitialise le message d'erreur
            return true; // Formulaire valide
        }
    }

    // Écouteur d'événement pour la vérification en temps réel
    document.getElementById("npwdc").addEventListener("input", function() {
        const password = document.getElementById("npwd").value;
        const confirmPassword = document.getElementById("npwdc").value;
        const message = document.getElementById("message");

        if (password !== confirmPassword) {
            message.textContent = "Les mots de passe ne correspondent pas.";
        } else {
            message.textContent = "";
        }
    });
    </script>
@endsection

@section('content')
@if(Session::exists("new_password"))

<div class="modal fade" id="NewPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success" style="color: white">
                <h5 class="modal-title">Changement de mot de passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/changer_pwd_first" method="post">
                {{ csrf_field() }}
                <div class="modal-body p-3 ">
                    <input type="hidden" name="code_user" value="{{Session::get('user')->code_user}}">
                        <div class="row">
                           <div class="col-sm-11 m-auto mb-4">
                                <input type="text" name="apwd" id="apwd" class="form-control" placeholder="votre ancien mot de passe">
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-11 m-auto mb-4">
                                <input type="password" name="npwd" id="npwd" class="form-control" required placeholder="votre nouveau mot de passe">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-11 m-auto">
                                <input type="password" name="npwdc" id="npwdc" class="form-control" required placeholder="votre nouveau mot de passe"><br>
                                <span id="message" style="color: red;"></span><br><br>
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
@endif
    <section id="hero" class="d-flex align-items-center">
        <div class="container mt-5" style="margin-top: 25px; clear:both;">
            <div class="row">
                <div class="col-lg-1 logo_home">
                    <a href="/">
                        <img src="{{ asset('share/img/logo_ueb.png') }}"  alt="" class="img-fluid"
                            data-aos="zoom-in" data-aos-delay="100" title="Université d'Ebolowa">
                    </a>
                </div>
                <div class="col-lg-1 logo_home">
                    <a href="/">
                        <img src="{{ asset('share/img/logo_estlc_ok.png') }}" class="img-fluid" alt=""
                            data-aos="zoom-in" data-aos-delay="100" title="Ecole Supérieure de Transport, de Logistique et de Commerce">
                    </a>
                </div>
                <div class="col-lg-10">
                    <h2 data-aos="fade-up">Ecole Supérieure de Transport, de Logistique et de Commerce -- ESTLC</h2>
                </div>
            </div>
            <div class="row" style="clear: both;">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                    <div data-aos="fade-up" data-aos-delay="600" class="mb-1 row" style="display: flex; justify-content: center; text-align: center;" >
                        <div class="col-sm-8 mb-1">
                            <a href="/download/Appel_Candidature_Recrutement_ESTLC" class=" blink btn btn-outline-primary" target="blank">Télécharger l'appel à candidature</a>
                        </div>
                        {{-- <div class="col-sm-7 mb-1">
                            <div class="row">
                                <div class="col-sm-10 mb-1">
                                    <a href="{{asset("fichiers/Appel_candidature_Master_II_Recherche_UFD_TSI_Ueb.pdf")}}" download="Appel_candidature_Master_II_Recherche_UFD_TSI_Ueb.pdf" class="btn btn-outline-primary" target="blank">Appel à candidature Master II Recherche</a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="/download/resultat_ISLAPE_2024" class="btn btn-outline-primary" target="blank">Résultats concours ISLAPE </a>
                                </div>
                            </div>
                        </div> --}}
                   </div>
                    {{-- <img src="{{asset('sige_app/frontend/img/team/recteur.jpg')}}" alt="Monsieur le Recteur"class="pt-5">
                   <h5 style="padding: 0px; text-align: center; text-style:italic;" class="pt-2">Le numérique: le monde de demain</h5> --}}
                    <h6 class="alert alert-primary" style="text-align: justify;" data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="2000">
                        <ul style="line-height: 20px;">
                            <li class="mb-4"> <b>Avis aux étudiants – Master Recherche à l'UFD-TSI</b>
                                Le Coordonnateur de l'UFD-TSI informe les étudiants nouvellement sélectionnés en Master Recherche pour l'année académique 2024-2025 qu'une réunion importante se tiendra le lundi 03 mars 2025 à 14h précises, au campus de Nkoumekeke, salle C1.
                                Présence obligatoire.

                                {{-- <ol>
                                    <li>Effectuez un paiement bancaire au numéro de compte présent sur l'arreté et scanné le reçu;</li>
                                    <li>Cliquez sur le bouton suivant <b>Je m'inscris au concours</b> remplir les informations et imprimer votre fiche d'inscription,</li>
                                </ol> --}}
                            </li>
                            <li class="mb-4"> <b>Rentrée académique – Master Recherche à l'UFD-TSI</b>
                                📅 Lundi 03 mars 2025
                                ℹ️ Pour les modalités d'inscription académique et administrative, veuillez vous rapprocher du secrétariat de l'UFD-TSI.  </li>
                            <li class="mb-4">
                                <b>📢 Recrutement de 150 Enseignants dans les Universités d'État ! 🎓</b>
La troisième phase de recrutement de 150 enseignants est lancée pour l'exercice 2025 dans les Universités d'État de Bertoua, Ebolowa et Garoua.
👨‍🏫 Les postes sont ouverts aux Camerounais titulaires du Doctorat ou du PhD!
📌 Ne manquez pas cette opportunité ! Restez connectés pour plus de détails sur les modalités de candidature. 🔍✍️
                            </li>
                            {{--<li class="mb-4">Veuillez consulter ce site régulièrement afin d'être à jour sur les informations du concours.</li> --}}

                        </ul>
                    </h6>
                </div>
                <div class="col-lg-6 hero-img" data-aos="zoom-in" data-aos-delay="200">

                    <div id="carouselExampleCaptions" class="carousel slide " style="width:100%; margin: auto;" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                                aria-label="Slide 4"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4"
                                aria-label="Slide 5"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="5"
                                aria-label="Slide 6"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="6"
                                aria-label="Slide 7"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="7"
                                aria-label="Slide 8"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="8"
                                aria-label="Slide 9"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="9"
                                aria-label="Slide 10"></button>
                        </div>
                        <div class="carousel-inner">
                            @foreach (\App\Models\Slide::orderBy("id", "desc")->take(10)->get() as $slide)
                            @if ($loop->index == 0)
                                <div class="carousel-item active mt-5">
                                    <img src='{{asset("storage").DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."slides".DIRECTORY_SEPARATOR.$slide->photo }}'
                                        class="d-block w-100 img-fluid animated" alt=" {{$slide->first_title}} ">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5> {{$slide->first_title}} </h5>
                                        <p>  {{$slide->second_title}} </p>
                                    </div>
                                </div>
                            @else
                            <div class="carousel-item mt-5">
                                <img src='{{asset("storage").DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."slides".DIRECTORY_SEPARATOR.$slide->photo }}'
                                    class="d-block w-100 img-fluid animated" alt=" {{$slide->first_title}} ">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5> {{$slide->first_title}} </h5>
                                    <p> {{$slide->second_title}} </p>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <main id="main">
        <section id="clients" class="clients clients">
            <div class="container">

                <div class="row">

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('share/img/logo_islape.jpg') }}" class="img-fluid" alt="" data-aos="zoom-in"
                            title="Institut Supérieur La Perle (ISLAPE)">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('share/img/logo_ueb.png') }}" class="img-fluid" alt=""
                            data-aos="zoom-in" data-aos-delay="100" title="Université d'Ebolowa">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('share/img/logo_ueb.png') }}" class="img-fluid" alt=""
                            data-aos="zoom-in" data-aos-delay="200" title="Université d'Ebolowa">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('share/img/logo_ueb.png') }}" class="img-fluid" alt=""
                            data-aos="zoom-in" data-aos-delay="300" title="Université d'Ebolowa">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('share/img/logo_ueb.png') }}" class="img-fluid" alt=""
                            data-aos="zoom-in" data-aos-delay="400" title="Université d'Ebolowa">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('share/img/logo_ueb.png') }}" class="img-fluid" alt=""
                            data-aos="zoom-in" data-aos-delay="500" title="Université d'Ebolowa">
                    </div>

                </div>

            </div>
        </section>

       <!-- <section id="site" class="sites">
            <div class="container">
                <div class="card" style="width:80%; margin: auto; margin-top:1%; margin-bottom: 1%;">
                    <div class="card-body">
                      <h1 style="padding-top: 12pt;padding-left: 23pt;text-indent: 0pt;line-height: 114%;text-align: center;">Concours
                          d'Entrée à l'Ecole Supérieure de Transport, de Logistique et de Commerce</h1><hr>
                      <h2 style="padding-top: 10pt;padding-left: 58pt;text-indent: 0pt;text-align: center;">LISTE DES SITES DE COMPOSITION
                      </h2><hr>
                      <table style="border-collapse:collapse; margin: auto; font-size: 1.1em;" cellspacing="0" >
                          <tr style="height:26pt; background-color: rgb(6, 153, 6); color: white; font-size: 1.3em; text-align: center; border: 1px solid black;">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s2" style="padding-left: 5pt;text-indent: 0pt;text-align: center;">Ville</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s2" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">Sites
                                      de composition</p>
                              </td>
                          </tr>
                          <tr style="height:26pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Ambam</p>
                              </td>
                              <td
                                  style="width:510pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">Lycée
                                      Bilingue d'Ambam</p>
                              </td>
                          </tr>
                          <tr style="height:42pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Ebolowa</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3"
                                      style="padding-left: 128pt;padding-right: 1pt;text-indent: -104pt;line-height: 114%;text-align: left;">
                                      Ecole Normale Supérieure d’Enseignement Technique d’Ebolowa</p>
                              </td>
                          </tr>
                          <tr style="height:42pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Maroua</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3"
                                      style="padding-left: 128pt;padding-right: 1pt;text-indent: -118pt;line-height: 114%;text-align: left;">
                                      Faculté des Sciences Juridiques et Politiques de l’Université de Maroua</p>
                              </td>
                          </tr>
                          <tr style="height:42pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Garoua</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 129pt;text-indent: -124pt;line-height: 114%;text-align: left;">Ecole
                                      Supérieure des Sciences Economiques et Commerciales de Garoua</p>
                              </td>
                          </tr>
                          <tr style="height:26pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Ngaoundéré</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">Lycée
                                      Classique de Ngaoundéré</p>
                              </td>
                          </tr>
                          <tr style="height:26pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Yaounde</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">Ecole
                                      Nationale Supérieure Polytechnique de Yaounde</p>
                              </td>
                          </tr>
                          <tr style="height:26pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Buea</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">
                                      Faculty of Engineering and Technology University of Buea</p>
                              </td>
                          </tr>
                          <tr style="height:26pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Douala</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">
                                      Institut Universitaire de Technologie de Douala</p>
                              </td>
                          </tr>
                          <tr style="height:26pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Bafoussam</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">Lycée
                                      Technique Banengo Bafoussam</p>
                              </td>
                          </tr>
                          <tr style="height:26pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Bamenda</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s4" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">
                                      Lycée Bilingue de Bamenda NKwen</p>
                              </td>
                          </tr>
                          <tr style="height:26pt">
                              <td
                                  style="width:151pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Bertoua</p>
                              </td>
                              <td
                                  style="width:310pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                                  <p class="s3" style="padding-left: 10pt;padding-right: 10pt;text-indent: 0pt;text-align: center;">Ecole
                                      Normale Supérieure de Bertoua</p>
                              </td>
                          </tr>
                      </table>
                      <div style="text-align: right;"><a href="/download/arrete_communique_ESTLC" class="btn btn-outline-success mt-2" target="blank"> Télécharger le communiqué </a> </div>
                    </div>
                  </div>
            </div>
        </section>-->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>Nos Parcours</h2>
                    <p>Actuellement, nous possédons différents parcours permettant aux apprenants de se spécialiser dans leurs formations</p>
                </div>

                <div class="row"  style="display: flex; justify-content: center;">
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                        <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                            <div class="icon"><i class="bx bxl-dribbble"></i></div>
                            <h4 class="title"><a href="">GLTCO</a></h4>
                            <p class="description">Gestion Logistique Transport et Commerce</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                        <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
                            <div class="icon"><i class="bx bx-file"></i></div>
                            <h4 class="title"><a href="">TTL</a></h4>
                            <p class="description">Technologie de Transport et de Logistique</p>
                        </div>
                    </div>
                </div>

            </div>
        </section><!-- End Services Section -->

        <!-- ======= More Services Section ======= -->
        <section id="more-services" class="more-services">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h2>Activités récentes</h2>
                    <p>Découvrez la vie de l'école à travers nos articles d'actualités </p>
                </div>
                <div class="row">
                    @foreach (\App\Models\Actualite::orderBy("created_at", "desc")->take(9)->get() as $actu)
                    <?php
                        $res = \App\Models\RessourceActu::where("actu_code", $actu->actu_code)->first();
                        $src = "";
                        if($res){
                            $d = $actu->created_at->format("Y-m-d-H-i");
                            $src =storage_path().DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."actualites".DIRECTORY_SEPARATOR.$actu->actu_code.DIRECTORY_SEPARATOR.$res->r_name;
                        }
                    ?>
                    <div class="col-md-4 d-flex align-items-stretch mt-3">
                        <div class="card"
                    style="background-image: url('{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."actualites".DIRECTORY_SEPARATOR.$actu->actu_code.DIRECTORY_SEPARATOR.$res->r_name)}}');"
                            data-aos="fade-up" data-aos-delay="100" title="">
                            <div class="card-body">
                                <div class="card-title" style="text-align: justify; font-weight: lighter;">{{$actu->actu_title}}</div>
                                <!--<p class="card-text"> { $actu->actu_content } </p>-->
                            </div>
                            <div class="card-footer" style="background-color: white; font-size: 0.9em;">
                                <div style="float: left;">Publié le {{$actu->created_at->format("d/m/Y")}} à {{$actu->created_at->format("H:i:s")}} </div>
                                <div class="read-more" style="float: right;"><a href="/details_actu/{{$actu->actu_code}}" class="btn btn-outline-info"> Lire plus <i class="bi bi-arrow-right"></i></a></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="mt-3" style="text-align: right;">
                        <a href="/all_actu" class="btn btn-info" > <i class='bx bx-list-ul'></i> Toutes nos actualités </a>
                    </div>
                </div>

            </div>
        </section><!-- End More Services Section -->

        <!--<section id="team" class="team section-bg">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>Notre équipe</h2>
                    <p>Une équipe jeune et dynamique pour vous servir</p>
                </div>

                <div class="row">

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch"  style="display:flex; justify-content:center;">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/team_1.png') }}" class="img-fluid" alt="">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. TAMBA Jean Gaston, Professeur</h4>
                                <span>Directeur</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/lankoul.jpg') }}" class="img-fluid" alt="">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. LANGOUL Francis</h4>
                                <span>Chef de centre de documentation et des archives</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/keudem.jpg') }}" class="img-fluid" alt="" title="PLEG en Informatique Fondamentale, M. KEUDEM ZONING Steve a servi au MINESEC dans le cadre des Enseignements avant de rejoindre le projet SIGIPES ou il se spécialise dans la gestion des Systèmes informatiques ; il rejoint plus tard le projet de digitalisation de l'Enseignement Supérieur à la cellule Réseau (ENHEN), Puis le Centre de Développement du Numérique Universitaire de Douala.">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. KEUDEM ZONING Steve</h4>
                                <span>Chef de cellule Informatique et des systèmes d'informations</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/dang.png') }}" class="img-fluid" alt="" title="Nomme en septembre 2023 comme Chef de service au Service des diplômes et de la certification de l’Ecole Supérieure de Transport de Logistique et de Commerce (ESTLC).  M.DANG KOKO Adamou est titulaire d’un PhD en Biophyque  optenu à l’UNIVERSITE DE YAOUNDE I où il est également membre du laboratoire de physique nucléaire, moléculaire et biophysique. Son domaine de recherche concerne la physique des rayonnements, la physique de la matière condensée et la biophysique. Ses principales publications portent sur le transport d’énergie dans l’ADN, les microtubules, la propagation de l’influ nerveux. Il travail également dans la modélisation de système biologiques. M. DANG KOKO est également membre du comité directeur de l’African Centre for Advanced Studies (ACAS)">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. DANG KOKO Adamou</h4>
                                <span>Chef de service des diplômes et de la certification</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/mvogo.png') }}" class="img-fluid" alt="" title="Dr. Mvogo Ahanda Joseph Jean Baptiste est un spécialiste en Energie et Systèmes Electriques et Electroniques, avec une emphase sur la Robotique. Il est titulaire d’un Doctorat PhD de l’Université de Yaoundé I au Cameroun. Il est Chargé de Cours au Département d’Ingénier Médicale et Biomédicale de l’Ecole Normale d’Enseignement Technique de l’Université d’Ebolowa au Cameroun. Son domaine de recherche concerne la conception, réalisation, modélisation et commande des robots (manipulateurs ou mobiles ou mobiles-manipulateurs) évoluant en environnement aléatoires ou déterministes, ainsi que la  conception, réalisation et la commande des prothèses/orthèses pour la réhabilitation et l’assistance aux personnes handicapés. Il est membre de l’IEEE (Institute of Electrical and Electronics Emgineers).">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. NVOGO AHANDA Joseph Jean Baptiste</h4>
                                <span>Chef de service de la recherche et de la coopération</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/iroume.jpg') }}" class="img-fluid" alt="">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. IROUME A BOUEBE Alexandre Turpin</h4>
                                <span>Chef de service de la scolarité et des statistiques</span>
                            </div>
                        </div>
                    </div>

                     <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/manga.jpg') }}" class="img-fluid" alt="" title="ETEME MANGA Cédric Wilfried est un enseignant de mathématiques. Il est titulaire d’un diplôme de professeur de l’enseignement secondaire à l’école normale de Yaoundé. Il est actuellement le chef de service de la maintenance et du matériel à l’Ecole Supérieure de Transport, de Logistique et de Commerce (ESTLC) de l’université d’Ebolowa au Cameroun. Il combine à la fois sa passion pour les mathématiques et sa maîtrise des processus techniques de gestion de matériel. ">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. MANGA ETEME Cédric Wilfried</h4>
                                <span>Chef de service de la maintenance et du matériel</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/mvondo.jpg') }}" class="img-fluid" alt="" title="M MVONDO Didier Serge est enseignant des Lycées d’enseignement général, titulaire d’un DIPES II (option CHIMIE) obtenu à l’Ecole Normale Supérieure de l’Université de Yaoundé I. Il a acquis une riche et forte expérience professionnelle dans les établissements secondaires pendant une quinzaine d’année d’une part et au Service de la Formation continue à  l’Ecole Normale d’Enseignement Technique (ENSET) de l’Université d’Ebolowa, d’autres parts. Il occupe actuellement les fonctions de Chef de Division de la Formation Continue et à Distance (DFCD) à l’Ecole Supérieures de Transport, de Logistique et de Commerce (ESTLC) de l’Université d’Ebolowa au Cameroun.">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. MVONDO Didier Serge</h4>
                                <span>Chef de division de la formation continue et à distance</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/nkonjoh.jpg') }}" class="img-fluid" alt="" title="Dr NKONJOH NGOMADE Armel est un chercheur spécialisé dans le domaine de la programmation distribuée et parallèle. Il est titulaire d’un Doctorat PhD de l’Université de Dschang au Cameroun. En parallèle à ses recherches. Il a également travaillé en tant que professeur de Lycées de l’enseignement technique et professionnel. Il occupe actuellement la fonction de Chef de Service de la Formation à distance à l’Ecole Supérieure de Transport, de Logistique et de Commerce (ESTLC) de l’Université d’Ebolowa au Cameroun. Ses domaines de recherche concernent principalement le calcul à haute performance, le Machine Learning, l’internet des objets (Iot), la conception des systèmes intelligents et embarqués.">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. NKONJOH NGOMADE Armel</h4>
                                <span>Chef de service de la formation à distance</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/nballa.png') }}" class="img-fluid" alt="" title="Dr MBALLA ELOUNDOU Aimé Christel est Enseignant-Chercheur et Expert-Consultant en Droit et Services publics des Transports. Titulaire d’un Doctorat Ph.D en Droit Public, il est Chargé de Cours à la Faculté des Sciences Juridiques et Politiques de l’Université de Yaoundé II (Cameroun). Enseignant vacataire à l’École Normale Supérieure d’Enseignement Technique et à l’Institut Universitaire des Technologies de l’Université de Douala-Département Génie logistique et transport (Cameroun), il est Membre-Chercheur et/ou Enseignant au Centre d’études en droit administratif et constitutionnel de l’Université Laval <br> (Canada), au Centre de recherche interdisciplinaire sur la diversité et la démocratie de l’Université du Québec à Montréal (Canada), au Centre de théorie et analyse du droit de l’Université Paris Nanterre (France) et au Laboratoire des Transports et Logistique Appliquée de l’École doctorale des sciences fondamentales et appliquées de l’Université de Douala (Cameroun). Ses domaines de recherche, d’enseignement et d’expertise-consultance couvrent les Droits et Contentieux Publics et Privés, les Marchés Publics ou Commande Publique, les NTIC, l’Environnement et le Développement durable, Responsabilité publique et Protection de la fortune publique, Droit-Politique-Sociologie des transports, les Contrats de prestations logistiques. Sur le plan académico-administratif, il est Chef de Département des Enseignements Généraux, Responsable du Tronc commun Gestion Logistique, Transport et Commerce, à l’École Supérieure de Transport, Logistique et Commerce (ESTLC) de l’Université d’Ebolowa à Ambam (Sud du Cameroun).  ">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. MBALLA ELOUNDOU Aimé Christel</h4>
                                <span>Chef de departement des enseignements généreaux</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/diboma.jpg') }}" class="img-fluid" alt="">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. DIBOMA Benjamin Salomon</h4>
                                <span>Chef de departement de Génie des Transports</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/kibong.jpg') }}" class="img-fluid" alt="">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. KIBONG Marius Tony</h4>
                                <span>Chef de departement de Génie Mécatronique</span>
                            </div>
                        </div>
                    </div>

                     <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/messi.jpg') }}" class="img-fluid" alt="">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. MESSI NGUELE Thomas</h4>
                                <span>Chef de departement de Génie Informatique</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up" data-aos-delay="100">
                            <div class="member-img">
                                <img src="{{ asset('sige_app/frontend/img/team/kenmoe.png') }}" class="img-fluid" alt="" data-bs-toggle="tooltip" title="Dr KENMOE SIYOU  Romuald Noel est un spécialiste en Finance Mathématique. Il est titulaire d’un Doctorat PhD de l’Université Bicocca de Milan en Italie. Il est Chargé de Cours au Département de Techniques Quantitatives à la Faculté des Sciences Economiques et de Gestion Appliquées (FSEGA) de l’université de Douala au Cameroun, enseignant à l’Ecole Supérieure Polytechnique de Yaoundé (Cameroun), professeur invité au département de Mathématiques de la faculté d’Economie de l’Université de Parme en Italie. Il occupe actuellement les fonctions de Chef de Département de  Recherche Opérationnelle  à l’Ecole Supérieures de Transport, de Logistique et de Commerce (ESTLC) de l’Université d’Ebolowa au Cameroun. Son domaine de recherche concerne la modélisation et l’optimisation des actifs financiers, l’économétrie financière, les données de haute fréquence et le E-learning.">
                                <div class="social">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>M. KENMOE SIYOU Romuald Noël</h4>
                                <span>Chef de departement de Génie de Recherche Opérationnelle</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section> End Team Section -->

        <section id="faq" class="faq">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>Questions Utiles pour étudiants et candidats</h2>
                </div>

                <div class="row faq-item d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-lg-5">
                        <i class="ri-question-line"></i>
                        <h4>Où est située la localité d'Ambam ?</h4>
                    </div>
                    <div class="col-lg-7">
                        <p>
                            Ambam est une ville et une communauté située dans la région du Sud-Cameroun, à la frontière de la Guinée Equatoriale et du Gabon. Cette ville est située à environ 245 km de Yaoundé.
                        </p>
                    </div>
                </div><!-- End F.A.Q Item-->

                <div class="row faq-item d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-lg-5">
                        <i class="ri-question-line"></i>
                        <h4>Qui peut postuler au concours d'entrée à l'ESTLC</h4>
                    </div>
                    <div class="col-lg-7">
                        <p>Les candidats au concours d'entrée à l'ESTLC doivent être titulaires d'un Baccalauréat ou d'un GCE A/L pour le premier cycle et d'une Licence pour le second cycle.
                        </p>
                    </div>
                </div><!-- End F.A.Q Item-->

                <div class="row faq-item d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
                    <div class="col-lg-5">
                        <i class="ri-question-line"></i>
                        <h4>Quelles sont les départements disponibles à l'ESTLC ?</h4>
                    </div>
                    <div class="col-lg-7">
                        <p>
                            En plus des départements des Enseignements Généraux  et des Enseignements Scientifiques de base, l'ESTLC Dispose des départements de Transport, Logistique, Recherche Opérationnelle, Génie Informatique, E-Commerce et Mécatronique
                        </p>
                    </div>
                </div><!-- End F.A.Q Item-->

                <div class="row faq-item d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="400">
                    <div class="col-lg-5">
                        <i class="ri-question-line"></i>
                        <h4>Quels diplômes obtient-on au terme de sa formation à l'ESTLC ?</h4>
                    </div>
                    <div class="col-lg-7">
                        <p>
                            Au terme des 5 années de formation, l'étudiant sort titulaire d'un diplôme d'ingénieur, lequel débouchera sur un Master Recherche en Sciences de l'Ingénieur, puis d'un Doctorat Ph D.
                        </p>
                    </div>
                </div><!-- End F.A.Q Item-->

                <div class="row faq-item d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="500">
                    <div class="col-lg-5">
                        <i class="ri-question-line"></i>
                        <h4>Comment Modifier ma fiche d'inscription au concours ?</h4>
                    </div>
                    <div class="col-lg-7">
                        <p>
                            Au terme du remplissage de votre fiche, veuillez enregistrer l'identifiant et le mot de passe qui vous sont transmis, ils vous permettront d'effectuer un éventuel retour pour modifier votre fiche.
                        </p>
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
