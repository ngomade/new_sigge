<div class="modal fade" id="ConcoursModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger" style="color: white">
                <h5 class="modal-title">Notification concours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class=" h4" style="text-align: center; color: red; line-height: 35px;">LES ÉPREUVES ÉCRITES DU CONCOURS D'ENTREE À L'ESTLC INITIALEMENT PREVUES LE 26 SEPTEMBRE 2024 SONT REPORTÉES AU JEUDI, 17 OCTOBRE 2024.
                    LA DATE LIMITE DE DEPÔT DE DOSSIERS QUANT À ELLE EST PROROGÉE AU MERCREDI 16 OCTOBRE À 15H30.
                    <br>Pour vous inscrire, Cliquez sur le bouton suivant:</p>
                    <div style="display: flex; justify-content: center;" class="mt-4">
                        <a class="btn btn-outline-success blink"  target="blank" href="https://inscription-univ-ebolowa.vercel.app">Inscription</a>
                    </div>
            </div>
        </div>
    </div>
</div>

    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">

            <div class="logo">
                <h1>
                    <a href="/">
                        <img src="{{ asset('share/img/logo_estlc.png') }}" class="img-fluid" alt=""
                            data-aos="zoom-in" data-aos-delay="100" title="Ecole Supérieure de Transport, de Logistique et de Commerce">
                    </a>
                </h1>
            </div>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="/">Accueil</a></li>
                    <li class="dropdown"><a href="#"><span>Nos Départements</span> <i
                                class="bi bi-chevron-down"></i></a>
                        <ul>
                            @foreach (\App\Models\Bureau::where("type_bureau", "Departement")->get() as $bureau)
                            <li><a href="/presentation_departement/{{$bureau->code_bureau}}"> {{$bureau->label_bureau}} </a></li>
                            @endforeach

                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"><span>UFD TSI</span> <i
                        class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="/presentation_ufd_tsi">Présentation </a></li>
                            <li class="dropdown"><a href="#"><span>Espace étudiant</span> <i
                                class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="/maintenance">Mon emploi de temps</a></li>
                                    <li><a href="/maintenance">Mes quitus</a></li>
                                </ul>
                            </li>
                            @foreach (\App\Models\Laboratoire::all() as $laboratoire)
                                <li><a href="/presentation_labo/{{$laboratoire->code_lab}}">{{$laboratoire->label_labo}} ({{$laboratoire->code_lab}})</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"><span>Espace étudiants</span> <i
                                class="bi bi-chevron-down"></i></a>
                        <ul>
                            <!--<li><a href="">Inscription au concours</a></li>-->
                            @if (Session::has("user"))
                                <li><a href="/retelecharger_fiche/{{\Session::get("user")->code_user}}" target="blank">Mes Fiches et Quitus</a></li>
                                <li><a href="/academique_index">Inscription Académique</a></li>
                            <li><a href="/telecharger_cours_index">Télécharger Mes Cours</a></li>
                                <li><a href="/maintenance">Mes Notes</a></li>
                                <li><a href="{{ route('requetes.create') }}">Rédiger une requête</a></li>
                             {{-- @else
                                <li><a href="/inscription_administrative">Inscription Administrative</a></li> --}}
                            @endif
                            <li><a href="/download/Reglement_Interieur_ESTLC">Mon Règlement interieur</a></li>
                            <li><a href="/maintenance">Mon Livrêt</a></li>
                            <li class="dropdown"><a href="#"><span>Vie des Clubs</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="/maintenance">Association des étudiants</a></li>
                                    <li><a href="/maintenance">Chorale</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"><span>Emploi de Temps</span> <i
                        class="bi bi-chevron-down"></i></a>
                        <ul>
                            {{-- <li><a href="https://calendar.google.com/calendar/embed?src=c480d3f04d760778ffcefdd8832c82f13201b875db002a7c4a9630e7dc870b49%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">Organigramme Planning</a></li> --}}
                            {{-- <li><a href="https://calendar.google.com/calendar/embed?src=4b2bd45d36efd8ea7c52f04d0a2940b6fe79c68021e692e6aff979d93258985d%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">GLTCO 2</a></li> --}}
                            <li class="dropdown"><a href="#"><span>Niveau 1</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="https://calendar.google.com/calendar/embed?src=9b0aa6f6117fd0833e73c3faf605624a9e9162e35d6a52a3a88f1c5bf6cc7a1f%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">GLTCO</a></li>
                                    <li><a href="https://calendar.google.com/calendar/embed?src=e39494864ee8a8eedfd81a50f538a6690c92384837b8056afc15552a0c817244%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">TTL</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Niveau 2</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="https://calendar.google.com/calendar/embed?src=83faabc5941900a4dae8d5ee93906cf3ad763e2e53d6882df7f248bb9e34489d%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">GLTCO</a></li>
                                    <li><a href="https://calendar.google.com/calendar/embed?src=4b2bd45d36efd8ea7c52f04d0a2940b6fe79c68021e692e6aff979d93258985d%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">TTL</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>ISLAPE</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="https://calendar.google.com/calendar/embed?src=99f9a29f1c8eedca770b1be252067a0090c8e5bc4918f2a2688ae1fe90365290%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">GLTCO 1</a></li>
                                    <li><a href="https://calendar.google.com/calendar/embed?src=e2e18a2eb9b57eb143093703a9160b58d39ebb0e33178802fd2ed670b86c519f%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">TTL 1</a></li>
                                </ul>
                            </li>
                            <li><a href="https://calendar.google.com/calendar/embed?src=24441c7f4123f4e4329dc47884ac56b92aa540d31f647897a5dd429551ec0f45%40group.calendar.google.com&ctz=Africa%2FLagos" target="blank">Permanence</a></li>
                        </ul>
                    </li>
                    <li><a href="https://web44.lws-hosting.com:2096/cpsess3484228458/3rdparty/roundcube/index.php" class="nav-link scrollto" target="blank" >Ma Messagerie</a></li>
                    <!--<li><a class="nav-link scrollto" href="#">Sites de Compositions</a></li>
                    <li class="dropdown"><a href="#"><span>Nos Programmes</span> <i
                                class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="/planche">Consulter la planche de nos formations</a></li>
                            <li><a href="/programme_EBTTL">Eléments de Base de Technologie de Transport et de Logistique
                                    (EBTTL)</a></li>
                            <li><a href="/programme_GLTCO">Gestion Logistique, Transport et Commerce (GLTCO)</a></li>
                        </ul>
                    </li>-->
                    {{-- <li><a class="nav-link scrollto" href="/maintenance">E-Learning</a></li> --}}
                    <li class="dropdown"><a href="#"><span>A Propos</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="/organigramme">Organigramme</a></li>
                            <li><a href="/staff_admin">Staff Administratif</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"><span>Ville</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li class="dropdown"><a href="#"><span>Mairie</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="/pres_marie">Présentation</a></li>
                                    <li><a href="/organigramme_mairie">Organingramme</a></li>
                                    <li><a href="/projet_mairie">Actualités</a></li>
                                </ul>
                            </li>
                            <li><a href="/maintenance">Hébergement</a></li>
                            <li><a href="/maintenance">Activités</a></li>
                            <li><a href="/maintenance">restauration</a></li>
                        </ul>
                    </li>
                    @if (Session::has("user"))
                    <li><a class="getstarted scrollto bg-danger" href="/logout">Déconnexion</a></li>
                    @else
                    <li><a class="getstarted scrollto bg-success" data-bs-toggle="modal" data-bs-target="#connexionModal" href="">Connexion</a></li>
                    @endif
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
            <div class="logo">
                <h1>
                    <a href="/">
                        <img src="{{ asset('share/img/logo_ueb.png') }}"  alt=""
                            data-aos="zoom-in" data-aos-delay="100" title="Université d'Ebolowa">
                    </a>
                </h1>
            </div>
        </div>
    </header>
