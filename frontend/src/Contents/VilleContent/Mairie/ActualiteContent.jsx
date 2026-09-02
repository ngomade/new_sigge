import React, { useEffect, useMemo, useState } from 'react'
import AOS from 'aos'
import 'aos/dist/aos.css'

import photo_12 from '../../../assets/img/mairie/photo_12.jpg'
import photo_11 from '../../../assets/img/mairie/photo_11.jpg'
import photo_13 from '../../../assets/img/mairie/photo_13.jpg'
import photo_2 from '../../../assets/img/mairie/photo_2.jpg'
import photo_6 from '../../../assets/img/mairie/photo_6.jpg'
import photo_7 from '../../../assets/img/mairie/photo_7.jpg'
import photo_8 from '../../../assets/img/mairie/photo_8.jpg'
import photo_9 from '../../../assets/img/mairie/photo_9.jpg'
import photo_10 from '../../../assets/img/mairie/photo_10.jpg'
import photo_3 from '../../../assets/img/mairie/photo_3.jpg'

/* ============================================================
   DONNÉES — à remplacer par un fetch vers /api/presentation
   et /api/phototheque plus tard
   ============================================================ */
const historique = [
  { annee: '2004', texte: "Devient Commune d'Ambam avec la loi N° 2004/018 du 22 juillet 2004." },
  { annee: '1974', texte: "Devient Commune Rurale d'Ambam à la faveur de la loi N° 74/23 du 05 décembre 1974." },
  { annee: '1952', texte: "Création de la Commune Mixte Rurale d'Ambam par arrêté N° 523 du 21 août 1952." },
]

const FILTERS = [
  { key: 'all', label: 'Tout' },
  { key: 'esplanade', label: 'Esplanade' },
  { key: 'bureaux', label: 'Nos Bureaux' },
  { key: 'divers', label: 'Divers' },
]

const photos = [
  { src: photo_12, category: 'esplanade', title: 'Notre Esplanade' },
  { src: photo_11, category: 'esplanade', title: 'Notre Esplanade' },
  { src: photo_13, category: 'esplanade', title: 'Notre Esplanade' },
  { src: photo_2, category: 'bureaux', title: 'Nos Bureaux' },
  { src: photo_6, category: 'bureaux', title: 'Nos Bureaux' },
  { src: photo_7, category: 'bureaux', title: 'Nos Bureaux' },
  { src: photo_8, category: 'bureaux', title: 'Nos Bureaux' },
  { src: photo_9, category: 'bureaux', title: 'Nos Bureaux' },
  { src: photo_3, category: 'divers', title: 'Divers' },
  { src: photo_10, category: 'divers', title: 'Divers' },
]

export default function ActualiteContent() {
  const [activeFilter, setActiveFilter] = useState('all')
  const [lightbox, setLightbox] = useState(null)

  useEffect(() => {
    AOS.init({ duration: 700, once: true, easing: 'ease-out-quart' })
  }, [])

  useEffect(() => {
    if (!lightbox) return
    const onKey = (e) => e.key === 'Escape' && setLightbox(null)
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [lightbox])

  const visiblePhotos = useMemo(
    () => (activeFilter === 'all' ? photos : photos.filter((p) => p.category === activeFilter)),
    [activeFilter]
  )

  return (
    <div className="mairie-page">
      <style>{`
        .mairie-page {
          background: var(--app-bg);
          padding: 2.5rem 1rem 4rem;
          width: 100%;
        }

        .mairie-inner {
          width: 100%;
          max-width: none;
          margin: 0;
        }

        .mairie-title-card {
          background: var(--app-primary-dark);
          color: var(--app-text-on-primary);
          border-radius: var(--app-radius-lg);
          padding: 1.5rem;
          text-align: center;
          margin-bottom: 1.5rem;
        }

        .mairie-title-card h1 {
          margin: 0;
          font-size: 1.4rem;
          font-weight: 600;
          color: var(--app-text-on-primary);
        }

        .mairie-grid {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 1.5rem;
          margin-bottom: 3rem;
        }

        @media (max-width: 800px) {
          .mairie-grid { grid-template-columns: 1fr; }
        }

        .mairie-card {
          background: var(--app-surface);
          border: 1px solid var(--app-border);
          border-radius: var(--app-radius-lg);
          box-shadow: 0 8px 24px var(--app-shadow);
          padding: 1.5rem;
        }

        .mairie-card p {
          line-height: 1.7;
          color: var(--app-text);
        }

        .mairie-card-label {
          display: inline-block;
          font-size: 0.78rem;
          font-weight: 600;
          color: var(--app-primary);
          background: var(--app-primary-soft);
          padding: 0.2rem 0.6rem;
          border-radius: 999px;
          margin-bottom: 0.6rem;
        }

        .mairie-divider {
          border: none;
          border-top: 1px solid var(--app-border);
          margin: 1rem 0;
        }

        .mairie-timeline {
          list-style: none;
          margin: 0;
          padding: 0;
        }

        .mairie-timeline li {
          position: relative;
          padding-left: 1.4rem;
          margin-bottom: 0.9rem;
          color: var(--app-text);
          line-height: 1.6;
        }

        .mairie-timeline li::before {
          content: "";
          position: absolute;
          left: 0;
          top: 0.4rem;
          width: 8px;
          height: 8px;
          border-radius: 50%;
          background: var(--app-accent);
        }

        .mairie-timeline strong {
          color: var(--app-primary-dark);
        }

        .mairie-esplanade-title {
          text-align: center;
          margin-bottom: 1rem;
        }

        .mairie-esplanade-img {
          width: 100%;
          border-radius: var(--app-radius-md);
          display: block;
        }

        /* --- Photothèque --- */
        .mairie-section-title {
          text-align: center;
          margin-bottom: 1.5rem;
        }

        .mairie-section-title p {
          color: var(--app-text-muted);
          margin-top: 0.3rem;
        }

        .mairie-filters {
          display: flex;
          justify-content: center;
          flex-wrap: wrap;
          gap: 0.6rem;
          margin-bottom: 1.75rem;
        }

        .mairie-filter-btn {
          border: 1px solid var(--app-border);
          background: var(--app-surface);
          color: var(--app-text-muted);
          padding: 0.45rem 1.1rem;
          border-radius: 999px;
          font-size: 0.85rem;
          font-weight: 600;
          cursor: pointer;
          transition: background var(--app-transition), color var(--app-transition), border-color var(--app-transition);
        }

        .mairie-filter-btn:hover {
          border-color: var(--app-primary);
          color: var(--app-primary);
        }

        .mairie-filter-btn.active {
          background: var(--app-primary);
          border-color: var(--app-primary);
          color: var(--app-text-on-primary);
        }

        .mairie-gallery {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 1.25rem;
        }

        @media (max-width: 900px) {
          .mairie-gallery { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 560px) {
          .mairie-gallery { grid-template-columns: 1fr; }
        }

        .mairie-photo {
          position: relative;
          border-radius: var(--app-radius-md);
          overflow: hidden;
          border: 1px solid var(--app-border);
          cursor: pointer;
          background: var(--app-surface-alt);
        }

        .mairie-photo img {
          width: 100%;
          height: 210px;
          object-fit: cover;
          display: block;
          transition: transform var(--app-transition);
        }

        .mairie-photo:hover img {
          transform: scale(1.04);
        }

        .mairie-photo-caption {
          position: absolute;
          left: 0;
          right: 0;
          bottom: 0;
          padding: 0.6rem 0.8rem;
          background: linear-gradient(to top, rgba(17, 61, 53, 0.75), transparent);
          color: var(--app-text-on-primary);
          font-size: 0.85rem;
          font-weight: 600;
        }

        /* --- Lightbox --- */
        .mairie-lightbox {
          position: fixed;
          inset: 0;
          background: rgba(17, 61, 53, 0.85);
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 2rem;
          z-index: 1000;
        }

        .mairie-lightbox img {
          max-width: 100%;
          max-height: 85vh;
          border-radius: var(--app-radius-md);
          box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .mairie-lightbox-close {
          position: absolute;
          top: 1.5rem;
          right: 1.5rem;
          width: 40px;
          height: 40px;
          border-radius: 50%;
          border: none;
          background: var(--app-surface);
          color: var(--app-text);
          font-size: 1.2rem;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: center;
        }
      `}</style>

      <div className="mairie-inner">
        <div className="mairie-title-card" data-aos="fade-up">
          <h1>Mairie d'Ambam - Présentation</h1>
        </div>

        <div className="mairie-grid">
          <div className="mairie-card" data-aos="fade-up" data-aos-delay="100">
            <span className="mairie-card-label">Depuis 1952</span>
            <p>
              <strong>Couverture géographique.</strong> La Commune d'Ambam partage l'espace
              territorial de l'Arrondissement du même nom, créé comme subdivision en 1921.
              Elle est composée de 86 villages pour une superficie de 2 798 km².
            </p>

            <hr className="mairie-divider" />

            <p><strong>Bref historique</strong></p>
            <ul className="mairie-timeline">
              {historique.map((h) => (
                <li key={h.annee}>
                  <strong>{h.annee}</strong> - {h.texte}
                </li>
              ))}
            </ul>
          </div>

          <div className="mairie-card" data-aos="fade-up" data-aos-delay="200">
            <h4 className="mairie-esplanade-title">Esplanade de la Mairie</h4>
            <img src={photo_12} alt="Esplanade de la Mairie d'Ambam" className="mairie-esplanade-img" />
          </div>
        </div>

        <section id="portfolio">
          <div className="mairie-section-title" data-aos="fade-up">
            <h2>Photothèque</h2>
            <p>Quelques clichés de nos locaux</p>
          </div>

          <div className="mairie-filters" data-aos="fade-up" data-aos-delay="100">
            {FILTERS.map((f) => (
              <button
                key={f.key}
                type="button"
                className={`mairie-filter-btn ${activeFilter === f.key ? 'active' : ''}`}
                onClick={() => setActiveFilter(f.key)}
              >
                {f.label}
              </button>
            ))}
          </div>

          <div className="mairie-gallery">
            {visiblePhotos.map((photo, i) => (
              <div
                key={`${photo.src}-${i}`}
                className="mairie-photo"
                data-aos="fade-up"
                data-aos-delay={(i % 3) * 100}
                onClick={() => setLightbox(photo)}
              >
                <img src={photo.src} alt={photo.title} />
                <div className="mairie-photo-caption">{photo.title}</div>
              </div>
            ))}
          </div>
        </section>
      </div>

      {lightbox && (
        <div className="mairie-lightbox" onClick={() => setLightbox(null)}>
          <button className="mairie-lightbox-close" onClick={() => setLightbox(null)} aria-label="Fermer">
            ✕
          </button>
          <img src={lightbox.src} alt={lightbox.title} onClick={(e) => e.stopPropagation()} />
        </div>
      )}
    </div>
  )
}