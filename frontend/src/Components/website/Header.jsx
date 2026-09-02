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
  { code_bureau: "e-commerce", label_bureau: "E-Commerce", path: "/under-development" },
  { code_bureau: "genie_informatique", label_bureau: "Génie Informatique", path: "/under-development" },
  { code_bureau: "genie_logistique", label_bureau: "Génie Logistique", path: "/under-development" },
  { code_bureau: "enseignement_generaux", label_bureau: "Enseignements Généraux", path: "/under-development" },
  { code_bureau: "genie_mecatronique", label_bureau: "Génie Mécatronique", path: "/under-development" },
  { code_bureau: "genie_transport", label_bureau: "Génie des Transport", path: "/under-development" },
  { code_bureau: "recherche_operationnelle", label_bureau: "Recherche Opérationnelle", path: "/under-development" },
  { code_bureau: "enseignement_scientifique", label_bureau: "Enseignement Scientifique de Base", path: "/under-development" },
  { code_bureau: "ufd_tsi", label_bureau: "Unité de Formation Doctorale en Technologies et Sciences de L'Innovation", path: "/under-development" },
];

const ufdTsiSubMenus = [
  { label: "Présentation", path: "#" },
  { label: "Laboratoire d'Économie et Géographie des Transports (LAEGT)", path: "/under-development" },
  { label: "Laboratoire de Logistique et de Transport Appliqué à l'Agriculture (LALOTA)", path: "/under-development" },
  { label: "Laboratoire des Systèmes de Transport Intégrés en Énergie (LASTIE)", path: "/under-development" },
  { label: "Laboratoire d'Informatique et Application (LIA)", path: "/under-development" },
  { label: "Laboratoire d'Innovation Commerciale Appliquée (LICA)", path: "/under-development" },
  { label: "Laboratoire d'Innovation pour les Technologies et Applications Électrogènes (LITAE)", path: "/under-development" },
  { label: "Laboratoire de Modélisation et Simulation Appliquée à l'Industrie (LMSAI)", path: "/under-development" },
  { label: "Laboratoire des Systèmes Logistiques et Applications Innovantes (LSLAI)", path: "/under-development" },
  { label: "Laboratoire des Systèmes Logistiques et Miniers (LSLM)", path: "/under-development" },
  { label: "Laboratoire des Sciences de la Mobilité et de la Réglementation Logistique (LSMRL)", path: "/under-development" },
  { label: "Laboratoire de Technologie et Systèmes Mécatroniques Intelligents (LTSMI)", path: "/under-development" },
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
                      <a href={bureau.path}>{bureau.label_bureau}</a>
                    </li>
                  ))}
                </ul>
              </li>

              <li className="dropdown">
                <a href="#" onClick={toggleDropdown}>
                  <span>UFD TSI</span> <i className="bi bi-chevron-down"></i>
                </a>
                <ul>
                  {ufdTsiSubMenus.map((item, index) => (
                    <li key={index}>
                      <a href={item.path}>{item.label}</a>
                    </li>
                  ))}
                  <li className="dropdown">
                    <a href="#" onClick={toggleDropdown}>
                      <span>Espace étudiant</span> <i className="bi bi-chevron-right"></i>
                    </a>
                    <ul>
                      <li>
                        <a href="/under-development">Mon emploi de temps</a>
                      </li>
                      <li>
                        <a href="/under-development">Mes quitus</a>
                      </li>
                    </ul>
                  </li>
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
                    <a href="/under-development">Mon Livret</a>
                  </li>
                  <li className="dropdown">
                    <a href="#" onClick={toggleDropdown}>
                      <span>Vie des Clubs</span> <i className="bi bi-chevron-right"></i>
                    </a>
                    <ul>
                      <li>
                        <a href="/under-development">Association des étudiants</a>
                      </li>
                      <li>
                        <a href="/under-development">Chorale</a>
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
                    <a href="/organigram">Organigramme</a>
                  </li>
                  <li>
                    <a href="/staff">Staff Administratif</a>
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
                        <a href="/presentation-mairie">Présentation</a>
                      </li>
                      <li>
                        <a href="/organigram-mairie">Organigramme</a>
                      </li>
                      <li>
                        <a href="/actualite">Actualités</a>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <a href="/under-development">Hébergement</a>
                  </li>
                  <li>
                    <a href="/under-development">Activités</a>
                  </li>
                  <li>
                    <a href="/under-development">Restauration</a>
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
                  <a
                    className="getstarted scrollto btn-login-app"
                    href="/login"
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