import React, { useEffect, useState } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import "../../Styles/Header.css";
import logo_estlc from "../../assets/logo.png";
/* ============================================================
   DONNÉES TEMPORAIRES (à remplacer par un appel API plus tard)
   ============================================================ */
// TODO: remplacer par un fetch vers /api/bureaux?type=Departement
const departements = [
  { code_bureau: "gltco", label_bureau: "GLTCO" },
  { code_bureau: "ttl", label_bureau: "TTL" },
];

// TODO: remplacer par un fetch vers /api/laboratoires
const laboratoires = [
  { code_lab: "lab1", label_labo: "Laboratoire 1" },
  { code_lab: "lab2", label_labo: "Laboratoire 2" },
];

export default function Header() {
  const [mobileOpen, setMobileOpen] = useState(false);

  // TODO: brancher sur le vrai état d'authentification (AuthService / Context)
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    AOS.init({
      duration: 1000,
      once: true,
      easing: "ease-out-quart",
    });
  }, []);

  // Gère l'ouverture/fermeture des sous-menus en mode mobile
  // (en desktop, l'ouverture se fait au survol via le CSS)
  const toggleDropdown = (e) => {
    if (window.innerWidth < 992) {
      e.preventDefault();
      const parentLi = e.currentTarget.parentElement;
      parentLi.classList.toggle("dropdown-active");
    }
  };

  return (
    <>
      {/* ============================================================
          MODAL — Notification concours
          ============================================================ */}
      <div className="modal fade" id="ConcoursModal" tabIndex={-1}>
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content">
            <div className="modal-header bg-danger" style={{ color: "white" }}>
              <h5 className="modal-title">Notification concours</h5>
              <button
                type="button"
                className="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div className="modal-body">
              <p
                className="h4"
                style={{ textAlign: "center", color: "var(--app-danger)", lineHeight: "35px" }}
              >
                LES ÉPREUVES ÉCRITES DU CONCOURS D'ENTRÉE SONT REPORTÉES.
                <br />
                Pour vous inscrire, cliquez sur le bouton suivant :
              </p>
              <div style={{ display: "flex", justifyContent: "center" }} className="mt-4">
                <a className="btn btn-outline-success blink" target="_blank" rel="noreferrer" href="#">
                  Inscription
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* ============================================================
          HEADER
          ============================================================ */}
      <header id="header" className="app-header fixed-top d-flex align-items-center position-relative">
        {/* Lueur d'ambiance en fond, cohérente avec le reste de l'appli */}
        <div className="header-glow-wrapper" aria-hidden="true">
          <div className="header-glow header-glow-left"></div>
          <div className="header-glow header-glow-right"></div>
        </div>

        <div className="container d-flex align-items-center justify-content-between position-relative" style={{ zIndex: 1 }}>
          <div className="logo" data-aos="zoom-in" data-aos-delay="100">
            <h1>
              <a href="/">
                <img
                  src={logo_estlc}
                  className="img-fluid"
                  alt="ESTLC"
                  title="Ecole Supérieure de Transport, de Logistique et de Commerce"
                />
              </a>
            </h1>
          </div>

          <nav id="navbar" className={`navbar ${mobileOpen ? "navbar-mobile-active" : ""}`}>
            <ul>
              <li>
                <a className="nav-link scrollto active" href="/">
                  Accueil
                </a>
              </li>

              <li className="dropdown">
                <a href="#" onClick={toggleDropdown}>
                  <span>Nos Départements</span> <i className="bi bi-chevron-down"></i>
                </a>
                <ul>
                  {departements.map((bureau) => (
                    <li key={bureau.code_bureau}>
                      <a href="#">{bureau.label_bureau}</a>
                    </li>
                  ))}
                </ul>
              </li>

              <li className="dropdown">
                <a href="#" onClick={toggleDropdown}>
                  <span>UFD TSI</span> <i className="bi bi-chevron-down"></i>
                </a>
                <ul>
                  <li>
                    <a href="#">Présentation</a>
                  </li>
                  <li className="dropdown">
                    <a href="#" onClick={toggleDropdown}>
                      <span>Espace étudiant</span> <i className="bi bi-chevron-right"></i>
                    </a>
                    <ul>
                      <li>
                        <a href="#">Mon emploi de temps</a>
                      </li>
                      <li>
                        <a href="#">Mes quitus</a>
                      </li>
                    </ul>
                  </li>
                  {laboratoires.map((labo) => (
                    <li key={labo.code_lab}>
                      <a href="#" target="_blank" rel="noreferrer">
                        {labo.label_labo} ({labo.code_lab})
                      </a>
                    </li>
                  ))}
                </ul>
              </li>

              <li className="dropdown">
                <a href="#" onClick={toggleDropdown}>
                  <span>Espace étudiants</span> <i className="bi bi-chevron-down"></i>
                </a>
                <ul>
                  {isLoggedIn && (
                    <>
                      <li>
                        <a href="#" target="_blank" rel="noreferrer">
                          Mes Fiches et Quitus
                        </a>
                      </li>
                      <li>
                        <a href="#">Inscription Académique</a>
                      </li>
                      <li>
                        <a href="#">Télécharger Mes Cours</a>
                      </li>
                      <li>
                        <a href="#">Mes Notes</a>
                      </li>
                      <li>
                        <a href="#">Rédiger une requête</a>
                      </li>
                    </>
                  )}
                  <li>
                    <a href="#">Mon Règlement intérieur</a>
                  </li>
                  <li>
                    <a href="#">Mon Livret</a>
                  </li>
                  <li className="dropdown">
                    <a href="#" onClick={toggleDropdown}>
                      <span>Vie des Clubs</span> <i className="bi bi-chevron-right"></i>
                    </a>
                    <ul>
                      <li>
                        <a href="#">Association des étudiants</a>
                      </li>
                      <li>
                        <a href="#">Chorale</a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>

              <li className="dropdown">
                <a href="#" onClick={toggleDropdown}>
                  <span>Emploi de Temps</span> <i className="bi bi-chevron-down"></i>
                </a>
                <ul>
                  <li className="dropdown">
                    <a href="#" onClick={toggleDropdown}>
                      <span>Niveau 1</span> <i className="bi bi-chevron-right"></i>
                    </a>
                    <ul>
                      <li>
                        <a href="#" target="_blank" rel="noreferrer">
                          GLTCO
                        </a>
                      </li>
                      <li>
                        <a href="#" target="_blank" rel="noreferrer">
                          TTL
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li className="dropdown">
                    <a href="#" onClick={toggleDropdown}>
                      <span>Niveau 2</span> <i className="bi bi-chevron-right"></i>
                    </a>
                    <ul>
                      <li>
                        <a href="#" target="_blank" rel="noreferrer">
                          GLTCO
                        </a>
                      </li>
                      <li>
                        <a href="#" target="_blank" rel="noreferrer">
                          TTL
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li className="dropdown">
                    <a href="#" onClick={toggleDropdown}>
                      <span>ISLAPE</span> <i className="bi bi-chevron-right"></i>
                    </a>
                    <ul>
                      <li>
                        <a href="#" target="_blank" rel="noreferrer">
                          GLTCO 1
                        </a>
                      </li>
                      <li>
                        <a href="#" target="_blank" rel="noreferrer">
                          TTL 1
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <a href="#" target="_blank" rel="noreferrer">
                      Permanence
                    </a>
                  </li>
                </ul>
              </li>

              <li>
                <a href="#" className="nav-link scrollto" target="_blank" rel="noreferrer">
                  Ma Messagerie
                </a>
              </li>

              <li className="dropdown">
                <a href="#" onClick={toggleDropdown}>
                  <span>A Propos</span> <i className="bi bi-chevron-down"></i>
                </a>
                <ul>
                  <li>
                    <a href="#">Organigramme</a>
                  </li>
                  <li>
                    <a href="#">Staff Administratif</a>
                  </li>
                </ul>
              </li>

              <li className="dropdown">
                <a href="#" onClick={toggleDropdown}>
                  <span>Ville</span> <i className="bi bi-chevron-down"></i>
                </a>
                <ul>
                  <li className="dropdown">
                    <a href="#" onClick={toggleDropdown}>
                      <span>Mairie</span> <i className="bi bi-chevron-right"></i>
                    </a>
                    <ul>
                      <li>
                        <a href="#">Présentation</a>
                      </li>
                      <li>
                        <a href="#">Organigramme</a>
                      </li>
                      <li>
                        <a href="#">Actualités</a>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <a href="#">Hébergement</a>
                  </li>
                  <li>
                    <a href="#">Activités</a>
                  </li>
                  <li>
                    <a href="#">Restauration</a>
                  </li>
                </ul>
              </li>

              {isLoggedIn ? (
                <li>
                  <a
                    className="getstarted scrollto btn-logout-app"
                    href="#"
                    onClick={() => setIsLoggedIn(false)}
                  >
                    Déconnexion
                  </a>
                </li>
              ) : (
                <li>
                  {/* TODO: brancher sur la modale de connexion réelle (#connexionModal) */}
                  <a
                    className="getstarted scrollto btn-login-app"
                    data-bs-toggle="modal"
                    data-bs-target="#connexionModal"
                    href="#"
                  >
                    Connexion
                  </a>
                </li>
              )}
            </ul>
          </nav>

          <i
            className={`bi ${mobileOpen ? "bi-x" : "bi-list"} mobile-nav-toggle`}
            onClick={() => setMobileOpen(!mobileOpen)}
          ></i>
        </div>
      </header>
    </>
  );
}