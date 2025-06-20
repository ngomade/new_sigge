<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/login" class="app-brand-link">
            <span class="app-brand-text fw-bolder ms-2 h3 mt-3">Administration</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item active">
            <a href="/login" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Tableau de Bord</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-user-circle"></i>
                <div data-i18n="Layouts">Etudiants</div>
            </a>

            <ul class="menu-sub">
                @if ($personnel->hasRole('ADMIN'))
                    <li class="menu-item">
                        <a href="/show_candidat_list" class="menu-link">
                            <div data-i18n="Without menu">Inscription</div>
                        </a>
                    </li>
                @endif
                @if ($personnel && ($personnel->hasRole('ADMIN') || $personnel->hasRole('PERSONNEL_APPUI')))
                    <li class="menu-item">
                        <a href="/liste_etudiant/0" class="menu-link">
                            <div data-i18n="Without menu">Production de liste</div>
                        </a>
                    </li>
                @endif
                @if ($personnel && $personnel->hasRole('ADMIN'))
                    <li class="menu-item">
                        <a href="/liste_etudiant/0" class="menu-link">
                            <div data-i18n="Without navbar">Changement de filière</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/liste_site_formation" class="menu-link">
                            <div data-i18n="Without navbar">Changement de Site</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/valider_paiement_index" class="menu-link">
                            <div data-i18n="Container">Valider Paiement</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/certificat_index" class="menu-link">
                            <div data-i18n="Fluid">Certificat de scolarité</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/carte_index" class="menu-link">
                            <div data-i18n="Fluid">Carte scolaire</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Blank">Statistiques</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-user-circle"></i>
                <div data-i18n="Layouts">Personnels</div>
            </a>

            <ul class="menu-sub">
                @if ($personnel->hasRole('ADMIN'))
                    <li class="menu-item">
                        <a href="/insription_personnel" class="menu-link">
                            <div data-i18n="Without menu">Inscription</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/liste_etudiant/0" class="menu-link">
                            <div data-i18n="Without navbar">Listing</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="" class="menu-link">
                            <div data-i18n="Blank">Statistiques</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Académie</span>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Account Settings">Académie</div>
            </a>
            <ul class="menu-sub">
                @if ($personnel->hasRole('ADMIN'))
                    <li class="menu-item">
                        <a href="/gestion_semestre" class="menu-link">
                            <div data-i18n="Account">Gestion des semestres</div>
                        </a>
                    </li>
                    {{-- <li class="menu-item">
            <a href="/gestion_grille" class="menu-link">
              <div data-i18n="Account">Gestion des grilles UE</div>
            </a>
          </li> --}}
                    <li class="menu-item">
                        <a href="/gestion_ue" class="menu-link">
                            <div data-i18n="Notifications">Gestion des UEs</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/gestion_ec" class="menu-link">
                            <div data-i18n="Connections">Gestion des ECs</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                <div data-i18n="Authentications">Authentications</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="auth-login-basic.html" class="menu-link" target="_blank">
                        <div data-i18n="Basic">Login</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="auth-register-basic.html" class="menu-link" target="_blank">
                        <div data-i18n="Basic">Register</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="auth-forgot-password-basic.html" class="menu-link" target="_blank">
                        <div data-i18n="Basic">Forgot Password</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cube-alt"></i>
                <div data-i18n="Misc">Misc</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="pages-misc-error.html" class="menu-link">
                        <div data-i18n="Error">Error</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="pages-misc-under-maintenance.html" class="menu-link">
                        <div data-i18n="Under Maintenance">Under Maintenance</div>
                    </a>
                </li>
            </ul>
        </li>
        @if ($personnel->hasRole('ENSEIGNANT'))
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Account Settings">Académie</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/gestion_semestre" class="menu-link">
                            <div data-i18n="Account">Gestion des semestres</div>
                        </a>
                    </li>
                    {{-- <li class="menu-item">
                  <a href="/gestion_grille" class="menu-link">
                    <div data-i18n="Account">Gestion des grilles UE</div>
                  </a>
                </li> --}}
                    <li class="menu-item">
                        <a href="/gestion_ue" class="menu-link">
                            <div data-i18n="Notifications">Gestion des UEs</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/gestion_ec" class="menu-link">
                            <div data-i18n="Connections">Gestion des ECs</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if ($personnel->hasRole('CHEF_SERV') || $personnel->hasRole('ADMIN'))
            <!-- Components -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Gestions des Actualités</span>
            </li>
            <!-- Cards -->
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-news"></i>
                    <div data-i18n="User interface">Actualités</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/index_actualite" class="menu-link">
                            <div data-i18n="PubActu">Publier un article</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/list_actu" class="menu-link">
                            <div data-i18n="listActu">Listing et Modification</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="StatActu">Statisques</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div data-i18n="User interface">Caroussel</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/index_caroussel" class="menu-link">
                            <div data-i18n="Accordion">Ajouter</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/list_slide" class="menu-link">
                            <div data-i18n="Alerts">Listing et Modification</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        {{-- @if ($personnel->hasRole('CHEF_SERV') || $personnel->hasRole('ADMIN')) --}}
        <!-- Components -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Gestions des Requetes</span></li>
        <!-- Cards -->
        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-news"></i>
                <div data-i18n="User interface">Requetes</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.requetes.index') }}" class="menu-link">
                        <div data-i18n="PubActu">Gerer les requetes</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ route('admin.requetes.statistiques') }}" class="menu-link">
                        <div data-i18n="StatActu">Statistiques</div>
                    </a>
                </li>
                <li class="menu-item">
<a href="{{ route('admin.requetes.categories.index') }}" class="menu-link">
    <div data-i18n="StatActu">categories</div>
</a>
                </li>
            </ul>
        </li>

        {{-- @endif --}}
        @if ($personnel->hasRole('ADMIN'))
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Gestions des Notes</span></li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-detail"></i>
                    <div data-i18n="Form Elements">Form Elements</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="forms-basic-inputs.html" class="menu-link">
                            <div data-i18n="Basic Inputs">Basic Inputs</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="forms-input-groups.html" class="menu-link">
                            <div data-i18n="Input groups">Input groups</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-detail"></i>
                    <div data-i18n="Form Layouts">Form Layouts</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="form-layouts-vertical.html" class="menu-link">
                            <div data-i18n="Vertical Form">Vertical Form</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="form-layouts-horizontal.html" class="menu-link">
                            <div data-i18n="Horizontal Form">Horizontal Form</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Tables -->
            <li class="menu-item">
                <a href="tables-basic.html" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-table"></i>
                    <div data-i18n="Tables">Tables</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Administration</span></li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-detail"></i>
                    <div data-i18n="Admin">ESTLC</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Ecole">Ecole</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/basculement_index" class="menu-link">
                            <div data-i18n="Ecole">Basculement</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/bureau/Cellule" class="menu-link">
                            <div data-i18n="Cellule">Cellule</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/bureau/Division" class="menu-link">
                            <div data-i18n="Division">Division</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/bureau/Departement" class="menu-link">
                            <div data-i18n="Departement">Département</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/bureau/Service" class="menu-link">
                            <div data-i18n="Service">Service</div>
                        </a>
                    </li>
                    {{-- <li class="menu-item">
            <a href="{{ route('admin.requetes.index') }}" class="menu-link"><div data-i18n="Requete">Requete</div></a>
          </li> --}}
                </ul>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-detail"></i>
                    <div data-i18n="Admin">Rôles &amp; Permissions</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/gestion_role_perm" class="menu-link">
                            <div data-i18n="roles">Rôles &amp; Permissions </div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/assignation_index" class="menu-link">
                            <div data-i18n="assignations">Assignations</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

    </ul>
</aside>
