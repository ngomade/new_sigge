import React, { useEffect, useMemo, useState } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import "../Styles/HomeContent.css";
import university_ebolowa from '../assets/logo_ebolowa.png';
import university_perle from '../assets/logo_perle1.png';

import logo_estlc from "../assets/logo.png";


/* ============================================================
   DONNÉES TEMPORAIRES (à remplacer par des appels API plus tard)
   ============================================================ */

// TODO: remplacer par un fetch vers /api/slides (10 derniers, triés par id desc)
const slides = [
  {
    id: 1,
    icon: "bi-mortarboard-fill",
    title: "Concours d'entrée ESTLC",
    subtitle: "Rejoignez une formation d'ingénieur reconnue en transport et logistique",
  },
  {
    id: 2,
    icon: "bi-truck",
    title: "Gestion Logistique, Transport et Commerce",
    subtitle: "Un parcours tourné vers les métiers de la chaîne logistique",
  },
  {
    id: 3,
    icon: "bi-signpost-split-fill",
    title: "Technologie de Transport et de Logistique",
    subtitle: "Des compétences techniques au service de la mobilité",
  },
];

// TODO: remplacer par un fetch vers /api/actualites?take=9
const actualites = [
  {
    actu_code: "a1",
    actu_title: "Réunion d'information Master Recherche UFD-TSI",
    created_at: "2025-03-01T09:00:00",
  },
  {
    actu_code: "a2",
    actu_title: "Rentrée académique 2024-2025",
    created_at: "2025-02-20T09:00:00",
  },
  {
    actu_code: "a3",
    actu_title: "Recrutement de 150 enseignants dans les universités d'État",
    created_at: "2025-02-10T09:00:00",
  },
];

// TODO: remplacer par un fetch vers /api/partenaires
// TODO: remplacer par un fetch vers /api/partenaires
const partenaires = [
  { label: "Institut Supérieur La Perle", image: university_perle },
  { label: "Université d'Ebolowa", image: university_ebolowa },
  { label: "Institut Supérieur La Perle", image: university_perle },
  { label: "Université d'Ebolowa", image: university_ebolowa },
  { label: "Institut Supérieur La Perle", image: university_perle },
  { label: "Université d'Ebolowa", image: university_ebolowa },
  
];

export default function HomeContent() {
  // TODO: brancher sur le vrai état d'authentification / première connexion (AuthService)
  const [needsPasswordChange, setNeedsPasswordChange] = useState(false);

  const [oldPwd, setOldPwd] = useState("");
  const [newPwd, setNewPwd] = useState("");
  const [confirmPwd, setConfirmPwd] = useState("");
  const [pwdError, setPwdError] = useState("");

  useEffect(() => {
    AOS.init({
      duration: 1000,
      once: true,
      easing: "ease-out-quart",
    });
  }, []);

  useEffect(() => {
    if (newPwd !== confirmPwd && confirmPwd.length > 0) {
      setPwdError("Les mots de passe ne correspondent pas.");
    } else {
      setPwdError("");
    }
  }, [newPwd, confirmPwd]);

  const handlePasswordSubmit = (e) => {
    e.preventDefault();
    if (newPwd !== confirmPwd) {
      setPwdError("Les mots de passe ne correspondent pas.");
      return;
    }
    // TODO: appeler l'API /changer_pwd_first avec { oldPwd, newPwd }
    setNeedsPasswordChange(false);
  };

  // Positions des formes flottantes du fond animé (mémorisées pour ne pas recalculer à chaque rendu)
  const floatingShapes = useMemo(
    () => [
      { icon: "bi-mortarboard", size: 46, top: "12%", left: "8%", duration: "14s", delay: "0s" },
      { icon: "bi-book", size: 34, top: "70%", left: "6%", duration: "18s", delay: "2s" },
      { icon: "bi-truck", size: 40, top: "20%", left: "88%", duration: "16s", delay: "1s" },
      { icon: "bi-signpost-split", size: 30, top: "78%", left: "90%", duration: "20s", delay: "3s" },
      { icon: "bi-lightbulb", size: 28, top: "45%", left: "50%", duration: "22s", delay: "4s" },
    ],
    []
  );

  return (
    <>
      {/* ============================================================
          MODAL — Changement de mot de passe (première connexion)
          ============================================================ */}
      {needsPasswordChange && (
        <div className="modal fade show d-block" id="NewPasswordModal" tabIndex={-1} style={{ backgroundColor: "rgba(17,61,53,0.4)" }}>
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header" style={{ backgroundColor: "var(--app-success)", color: "white" }}>
                <h5 className="modal-title">Changement de mot de passe</h5>
                <button
                  type="button"
                  className="btn-close"
                  aria-label="Close"
                  onClick={() => setNeedsPasswordChange(false)}
                ></button>
              </div>
              <form onSubmit={handlePasswordSubmit}>
                <div className="modal-body p-3">
                  <div className="row">
                    <div className="col-sm-11 m-auto mb-4">
                      <input
                        type="text"
                        className="form-control"
                        placeholder="votre ancien mot de passe"
                        value={oldPwd}
                        onChange={(e) => setOldPwd(e.target.value)}
                      />
                    </div>
                  </div>
                  <div className="row">
                    <div className="col-sm-11 m-auto mb-4">
                      <input
                        type="password"
                        className="form-control"
                        required
                        placeholder="votre nouveau mot de passe"
                        value={newPwd}
                        onChange={(e) => setNewPwd(e.target.value)}
                      />
                    </div>
                  </div>
                  <div className="row">
                    <div className="col-sm-11 m-auto">
                      <input
                        type="password"
                        className="form-control"
                        required
                        placeholder="confirmez votre nouveau mot de passe"
                        value={confirmPwd}
                        onChange={(e) => setConfirmPwd(e.target.value)}
                      />
                      {pwdError && (
                        <span style={{ color: "var(--app-danger)" }} className="d-block mt-2 small">
                          {pwdError}
                        </span>
                      )}
                    </div>
                  </div>
                </div>
                <div className="modal-footer mt-0">
                  <button type="button" className="btn btn-danger" onClick={() => setNeedsPasswordChange(false)}>
                    Annuler
                  </button>
                  <button type="submit" className="btn btn-success">
                    Valider
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}

      {/* ============================================================
          HERO — Bandeau d'accueil + fond animé
          ============================================================ */}
      <section id="hero" className="app-hero position-relative">
        {/* Fond animé : formes flottantes en icônes bootstrap, contenu à cette section uniquement */}
        <div className="hero-animated-bg" aria-hidden="true">
          <div className="hero-glow hero-glow-left"></div>
          <div className="hero-glow hero-glow-right"></div>
          {floatingShapes.map((shape, i) => (
            <i
              key={i}
              className={`bi ${shape.icon} floating-icon`}
              style={{
                fontSize: shape.size,
                top: shape.top,
                left: shape.left,
                animationDuration: shape.duration,
                animationDelay: shape.delay,
              }}
            ></i>
          ))}
        </div>

        <div className="container position-relative" style={{ zIndex: 1 }}>
          <div className="row align-items-center mb-4">
            
            <div className="col-auto" data-aos="zoom-in" data-aos-delay="150">
              <img src={logo_estlc} alt="Logo ESTLC" className="school-logo-icon" style={{ height:'100px' }} />
            </div>
            <div className="col">
              <h2 data-aos="fade-up" className="mb-0" style={{ fontSize: "25px" }}>
                Ecole Supérieure de Transport, de Logistique et de Commerce — ESTLC
              </h2>
            </div>
          </div>

          <div className="row g-4">
            <div className="col-lg-6 order-2 order-lg-1">
              <div data-aos="fade-up" data-aos-delay="300" className="mb-3 text-center text-lg-start">
                <a href="#" className="btn btn-primary rounded-pill px-4 py-2">
                  <i className="bi bi-download me-2"></i>
                  Télécharger l'appel à candidature
                </a>
              </div>

              <div
                className="announcement-card"
                data-aos="fade-up"
                data-aos-delay="450"
                style={{ height:'322px' }}
              >
                <ul className="announcement-list">
                  <li>
                    <i className="bi bi-megaphone-fill"></i>
                    <div>
                      <strong>Avis aux étudiants – Master Recherche à l'UFD-TSI</strong>
                      <p>
                        Le Coordonnateur de l'UFD-TSI informe les étudiants nouvellement
                       sélectionnés en Master Recherche pour l'année académique 2024-2025 
                       qu'une réunion importante se tiendra le lundi 03 mars 2025 à 14h précises, au campus de Nkoumekeke, salle C1. Présence obligatoire.
                      </p>
                    </div>
                  </li>
                  <li>
                    <i className="bi bi-calendar-event-fill"></i>
                    <div>
                      <strong>Rentrée académique – Master Recherche à l'UFD-TSI</strong>
                      <p>
                       Lundi 03 mars 2025 ℹ️ Pour les modalités d'inscription académique et administrative, veuillez vous rapprocher du secrétariat de l'UFD-TSI.
                      </p>
                    </div>
                  </li>
                  <li>
                    <i className="bi bi-mortarboard-fill"></i>
                    <div>
                      <strong>Recrutement de 150 Enseignants dans les Universités d'État !</strong>
                      <p>
                        La troisième phase de recrutement de 150 enseignants est lancée pour l'exercice 2025 dans les Universités d'État de Bertoua, Ebolowa et Garoua.
                         👨‍🏫 Les postes sont ouverts aux Camerounais titulaires du Doctorat ou du PhD! 📌 Ne manquez pas cette opportunité !.
                      </p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <div className="col-lg-6 order-1 order-lg-2" data-aos="zoom-in" data-aos-delay="200" style={{ marginTop:'5.3%' }}>
              <div
                id="homeCarousel"
                className="carousel slide rounded-4 overflow-hidden shadow"
                data-bs-ride="carousel"
                // style={{ height:'355px' }}
              >
                <div className="carousel-indicators">
                  {slides.map((slide, i) => (
                    <button
                      key={slide.id}
                      type="button"
                      data-bs-target="#homeCarousel"
                      data-bs-slide-to={i}
                      className={i === 0 ? "active" : ""}
                      aria-label={`Slide ${i + 1}`}
                    ></button>
                  ))}
                </div>

                <div className="carousel-inner">
                  {slides.map((slide, i) => (
                    <div key={slide.id} className={`carousel-item ${i === 0 ? "active" : ""}`}>
                      <div className="carousel-slide-visual">
                        <i className={`bi ${slide.icon}`}></i>
                      </div>
                      <div className="carousel-caption">
                        <h5>{slide.title}</h5>
                        <p>{slide.subtitle}</p>
                      </div>
                    </div>
                  ))}
                </div>

                <button className="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
                  <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span className="visually-hidden">Précédent</span>
                </button>
                <button className="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
                  <span className="carousel-control-next-icon" aria-hidden="true"></span>
                  <span className="visually-hidden">Suivant</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <main id="main">
        {/* ============================================================
            PARTENAIRES
            ============================================================ */}
      <section id="clients" className="app-section py-5">
  <div className="container">
    <div className="row justify-content-center g-4 align-items-center">
      {partenaires.map((p, i) => (
        <div key={i} className="col-lg-2 col-md-4 col-6 text-center" data-aos="zoom-in" data-aos-delay={i * 80}>
          {p.image ? (
            <img 
              src={p.image} 
              alt={p.label} 
              title={p.label} 
              className="img-fluid partner-icon" 
              style={{ maxHeight: "130px", width: "auto", objectFit: "contain" }}
            />
          ) : (
            <i className={`bi ${p.icon} partner-icon`} title={p.label} style={{ fontSize: "2.5rem" }}></i>
          )}
        </div>
      ))}
    </div>
  </div>
</section>

        {/* ============================================================
            NOS PARCOURS
            ============================================================ */}
        <section id="services" className="app-section py-5">
          <div className="container">
            <div className="section-title text-center mb-5" data-aos="fade-up">
              <h2>Nos Parcours</h2>
              <p>
                Actuellement, nous possédons différents parcours permettant aux apprenants de se
                spécialiser dans leurs formations
              </p>
            </div>

            <div className="row justify-content-center g-4">
              <div className="col-md-6 col-lg-3">
                <div className="icon-box" data-aos="fade-up" data-aos-delay="100">
                  <div className="icon">
                    <i className="bi bi-truck"></i>
                  </div>
                  <h4 className="title">
                    <a href="#">GLTCO</a>
                  </h4>
                  <p className="description">Gestion Logistique Transport et Commerce</p>
                </div>
              </div>

              <div className="col-md-6 col-lg-3">
                <div className="icon-box" data-aos="fade-up" data-aos-delay="200">
                  <div className="icon">
                    <i className="bi bi-signpost-split-fill"></i>
                  </div>
                  <h4 className="title">
                    <a href="#">TTL</a>
                  </h4>
                  <p className="description">Technologie de Transport et de Logistique</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* ============================================================
            ACTUALITÉS
            ============================================================ */}
        <section id="more-services" className="app-section py-5">
          <div className="container">
            <div className="section-title text-center mb-5" data-aos="fade-up">
              <h2>Activités récentes</h2>
              <p>Découvrez la vie de l'école à travers nos articles d'actualités</p>
            </div>

            <div className="row g-4">
              {actualites.map((actu, i) => (
                <div key={actu.actu_code} className="col-md-4">
                  <div className="actu-card" data-aos="fade-up" data-aos-delay={i * 100}>
                    <div className="actu-card-icon">
                      <i className="bi bi-newspaper"></i>
                    </div>
                    <div className="actu-card-body">
                      <p className="actu-card-title">{actu.actu_title}</p>
                    </div>
                    <div className="actu-card-footer">
                      <span className="small text-muted">
                        Publié le {new Date(actu.created_at).toLocaleDateString("fr-FR")}
                      </span>
                      <a href="#" className="btn btn-sm btn-outline">
                        Lire plus <i className="bi bi-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            <div className="mt-4 text-end">
              <a href="#" className="btn btn-primary">
                <i className="bi bi-list-ul me-1"></i> Toutes nos actualités
              </a>
            </div>
          </div>
        </section>

        {/* ============================================================
            FAQ
            ============================================================ */}
        <section id="faq" className="app-section app-section-alt py-5">
          <div className="container">
            <div className="section-title text-center mb-5" data-aos="fade-up">
              <h2>Questions Utiles pour étudiants et candidats</h2>
            </div>

            {[
              {
                q: "Où est située la localité d'Ambam ?",
                a: "Ambam est une ville et une communauté située dans la région du Sud-Cameroun, à la frontière de la Guinée Equatoriale et du Gabon. Cette ville est située à environ 245 km de Yaoundé.",
              },
              {
                q: "Qui peut postuler au concours d'entrée à l'ESTLC ?",
                a: "Les candidats doivent être titulaires d'un Baccalauréat ou d'un GCE A/L pour le premier cycle, et d'une Licence pour le second cycle.",
              },
              {
                q: "Quels sont les départements disponibles à l'ESTLC ?",
                a: "En plus des enseignements généraux et scientifiques de base, l'ESTLC dispose des départements Transport, Logistique, Recherche Opérationnelle, Génie Informatique, E-Commerce et Mécatronique.",
              },
              {
                q: "Quels diplômes obtient-on au terme de sa formation à l'ESTLC ?",
                a: "Au terme des 5 années de formation, l'étudiant obtient un diplôme d'ingénieur, pouvant déboucher sur un Master Recherche puis un Doctorat PhD.",
              },
              {
                q: "Comment modifier ma fiche d'inscription au concours ?",
                a: "Conservez l'identifiant et le mot de passe transmis après le remplissage de votre fiche : ils vous permettront d'y revenir pour la modifier.",
              },
            ].map((item, i) => (
              <div className="row faq-item g-3" key={i} data-aos="fade-up" data-aos-delay={i * 100}>
                <div className="col-lg-5 d-flex align-items-start gap-2">
                  <i className="bi bi-question-circle-fill faq-icon"></i>
                  <h4 className="mb-0">{item.q}</h4>
                </div>
                <div className="col-lg-7">
                  <p>{item.a}</p>
                </div>
              </div>
            ))}
          </div>
        </section>
      </main>
    </>
  );
}