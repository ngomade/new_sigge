import React, { useEffect } from 'react'
import AOS from 'aos'
import 'aos/dist/aos.css'
import mairie_portrait from '../../../assets/img/mairie/maire_portrait.png'
import premier_adjoint from '../../../assets/img/mairie/premier_adjoint.png'
import second_adjoint from '../../../assets/img/mairie/second_adjoint.png'

/* ============================================================
   DONNÉES — à remplacer par un fetch vers /api/organigramme
   ============================================================ */
const maire = {
  photo: mairie_portrait,
  nom: 'ZOMO OVONO SAMSON',
  poste: "Maire de la Commune d'Ambam",
}

const adjoints = [
  {
    photo: premier_adjoint,
    nom: 'AVOMO EKOTO MATHILDE EPSE ELLA',
    poste: '1er Adjoint au Maire',
  },
  {
    photo: second_adjoint,
    nom: 'NVOA JENNER PURCELL',
    poste: '2ème Adjoint au Maire',
  },
]

/* ============================================================
   CARTE PERSONNE — bloc réutilisé à chaque niveau de l'arbre
   ============================================================ */
function PersonCard({ photo, nom, poste, size = 'md', delay = 0 }) {
  return (
    <div className={`org-card org-card-${size}`} data-aos="fade-up" data-aos-delay={delay}>
      <div className="org-card-photo">
        <img src={photo} alt={nom} />
      </div>
      <p className="org-card-name">{nom}</p>
      <p className="org-card-role">{poste}</p>
    </div>
  )
}

export default function OrganigrameMaireContent() {
  useEffect(() => {
    AOS.init({ duration: 700, once: true, easing: 'ease-out-quart' })
  }, [])

  return (
    <div className="org-page">
      <style>{`
        .org-page {
          background: var(--app-bg);
          padding: 2.5rem 1rem 4rem;
          min-height: 100vh;
        }

        .org-wrapper {
          max-width: 960px;
          margin: 0 auto;
          background: var(--app-surface);
          border: 1px solid var(--app-border);
          border-radius: var(--app-radius-lg);
          box-shadow: 0 8px 24px var(--app-shadow);
          overflow: hidden;
        }

        .org-header {
          background: var(--app-primary-dark);
          color: var(--app-text-on-primary);
          padding: 1.75rem 1.5rem;
          text-align: center;
        }

        .org-header h1 {
          margin: 0;
          font-size: 1.4rem;
          font-weight: 600;
          color: var(--app-text-on-primary);
        }

        .org-header span {
          display: block;
          margin-top: 0.35rem;
          font-size: 0.9rem;
          color: var(--app-accent-soft);
        }

        .org-tree {
          padding: 3rem 1.5rem 2.5rem;
        }

        /* --- Carte individuelle --- */
        .org-card {
          background: var(--app-surface-alt);
          border: 1px solid var(--app-border);
          border-radius: var(--app-radius-md);
          padding: 1.25rem 1rem 1rem;
          text-align: center;
          transition: box-shadow var(--app-transition), transform var(--app-transition);
        }

        .org-card:hover {
          box-shadow: 0 10px 22px var(--app-shadow-soft);
          transform: translateY(-2px);
        }

        .org-card-photo {
          width: 96px;
          height: 96px;
          margin: 0 auto 0.9rem;
          border-radius: 50%;
          overflow: hidden;
          border: 3px solid var(--app-primary-soft);
          background: var(--app-surface);
        }

        .org-card-lg .org-card-photo {
          width: 128px;
          height: 128px;
          border-color: var(--app-accent-soft);
        }

        .org-card-photo img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          display: block;
        }

        .org-card-name {
          margin: 0;
          font-weight: 600;
          font-size: 0.95rem;
          color: var(--app-text);
        }

        .org-card-role {
          margin: 0.2rem 0 0;
          font-size: 0.82rem;
          color: var(--app-text-muted);
        }

        .org-card-lg .org-card-role {
          color: var(--app-primary);
          font-weight: 600;
        }

        /* --- Structure de l'arbre --- */
        .org-level-1 {
          display: flex;
          justify-content: center;
          position: relative;
        }

        .org-level-1 .org-card {
          width: 100%;
          max-width: 260px;
        }

        .org-level-1::after {
          content: "";
          position: absolute;
          top: 100%;
          left: 50%;
          transform: translateX(-50%);
          width: 2px;
          height: 28px;
          background: var(--app-border);
        }

        .org-level-2-rail {
          position: relative;
          height: 28px;
        }

        .org-level-2-rail::before {
          content: "";
          position: absolute;
          top: 0;
          left: 25%;
          width: 50%;
          height: 2px;
          background: var(--app-border);
        }

        .org-level-2-rail::after,
        .org-level-2-rail .org-drop-left,
        .org-level-2-rail .org-drop-right {
          content: "";
          position: absolute;
          top: 0;
          width: 2px;
          height: 28px;
          background: var(--app-border);
        }

        .org-drop-left { left: 25%; }
        .org-drop-right { left: 75%; }

        .org-level-2 {
          display: grid;
          grid-template-columns: repeat(2, minmax(0, 1fr));
          gap: 1.5rem;
          max-width: 620px;
          margin: 0 auto;
        }

        @media (max-width: 620px) {
          .org-level-2 {
            grid-template-columns: 1fr;
            gap: 2rem;
          }

          .org-level-2-rail {
            display: none;
          }

          .org-level-1::after {
            height: 20px;
          }

          .org-tree {
            padding: 2rem 1rem;
          }
        }
      `}</style>

      <div className="org-wrapper">
        <div className="org-header">
          <h1>Mairie d'Ambam</h1>
          <span>Organigramme de la Commune</span>
        </div>

        <div className="org-tree">
          <div className="org-level-1">
            <PersonCard {...maire} size="lg" delay={0} />
          </div>

          <div className="org-level-2-rail">
            <span className="org-drop-left" />
            <span className="org-drop-right" />
          </div>

          <div className="org-level-2">
            {adjoints.map((personne, i) => (
              <PersonCard key={personne.nom} {...personne} delay={i * 100} />
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}