  <header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <h1><a href="/">ESTLC-CONCOURS</a></h1>
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="/concours-estlc">Acceuil</a></li>
          <li><a class="nav-link scrollto" href="/site_compo">Nos Sites de compositions</a></li>
          @if (Session::has("user"))
            <li><a class="nav-link scrollto" href="/impression/{{Session::get("user")->ca_code}}" target="_blank">Télécharger ma Fiche</a></li>
            <li><a class="nav-link scrollto" href="/update">Modifier mes infos</a></li>
          @endif
          @if (!Session::has("user"))
            <li><a class="nav-link scrollto" href="#" data-bs-toggle="modal" data-bs-target="#requestModal">Requêtes</a></li>
          @endif
          <li><a class="nav-link scrollto" href="#">FAQ</a></li>
          @if (Session::has("admin") || Session::has("user"))
          <li><a class="getstarted scrollto bg-danger" href="/logout">Déconnexion</a></li>
          @else
          <li><a class="getstarted scrollto bg-success" data-bs-toggle="modal" data-bs-target="#connexionModal" href="">Connexion</a></li>
          @endif
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
    @if (\Session::has("success"))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-notif">
            <i class="bi bi-check-circle me-1"></i>
                {{\Session::get("success")}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (\Session::has("errors"))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="danger-notif">
            <i class="bi bi-exclamation-octagon me-1"></i>
                    {{\Session::get("errors")}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
  </header>
