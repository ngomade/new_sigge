import React, { useEffect } from 'react';
import AOS from 'aos';
import 'aos/dist/aos.css';
import logo_mairie from '../../../assets/img/mairie/logo_mairie.jpg';
import maire from '../../../assets/img/mairie/maire.jpg';
import { MdRecordVoiceOver, MdFormatQuote, MdFlag } from 'react-icons/md';

/* ============================================================
   DONNÉES — chantiers prioritaires (repris du Mot du Maire)
   ============================================================ */
const CHANTIERS = [
  "Le premier challenge est de faire d'Ambam une ville propre et belle : l'hygiène, la salubrité et l'assainissement sont des activités à mettre en œuvre par tous et chacun, la commune apportant sa contribution dans l'enlèvement des ordures et l'entretien des espaces verts.",
  "La réalisation des projets socio-économiques, pour donner un visage digne d'une capitale départementale et ville carrefour de trois frontières : Cameroun – Gabon – Guinée Équatoriale.",
  "Cultiver et maintenir un climat de paix, de sécurité et de prospérité pour l'ensemble des populations de cette municipalité, dans un vivre-ensemble propre aux coutumes africaines.",
  "La bonne gouvernance et le respect des normes administratives sont un autre chantier tout aussi important : il est bon que la municipalité ne reste pas en marge des prescriptions gouvernementales.",
  "L'appui et l'accompagnement des structures déconcentrées de l'État, pour une meilleure appropriation des compétences transférées et une implémentation harmonieuse et efficiente. L'autre objectif est la participation effective des populations et la construction d'une commune nouvelle.",
  "Les chantiers sont nombreux et aussi importants les uns que les autres. Dans le cadre de l'accélération de la décentralisation, les populations ont un rôle d'acteur : il est plus qu'urgent que la commune d'Ambam prenne un nouvel envol pour le bien de tous et de chacun. Chaque citoyen devrait jouer sa partition et apporter ainsi sa contribution dans la construction d'une commune qui nous ressemble et que nous portons tous fièrement dans nos cœurs."
];


/* ============================================================
   COMPOSANT PRINCIPAL
   ============================================================ */
export default function PresentationMairie() {
  const ACCENT = 'var(--app-primary)';
  const ACCENT_DARK = 'var(--app-primary-dark)';
  const ACCENT_GOLD = 'var(--app-accent)';

  useEffect(() => {
    AOS.init({ duration: 900, once: true, easing: 'ease-out-quart' });
  }, []);

  return (
    <div className="mairie-page">
      <style>{`
        .chantier-item { display: flex; align-items: stretch; gap: 1rem; }
        .chantier-rail { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
        .chantier-connector { width: 2px; flex: 1; background: var(--app-border); margin-top: 6px; min-height: 12px; }
      `}</style>

      {/* En-tête */}
      <div className="text-center py-4 py-md-5 px-3" data-aos="fade-up" style={{ backgroundColor: 'var(--app-bg)' }}>
        <img
          src={logo_mairie}
          alt="Logo de la Mairie d'Ambam"
          style={{
            width: 84,
            height: 84,
            objectFit: 'cover',
            borderRadius: '50%',
            border: '3px solid var(--app-primary-soft)',
            boxShadow: '0 8px 20px var(--app-shadow)',
            margin: '0 auto 1rem'
          }}
        />
        <h1 className="fw-bold mb-1" style={{ color: ACCENT_DARK, fontSize: '1.9rem' }}>
          Bienvenue à la Mairie d'Ambam
        </h1>
        <p className="mb-0" style={{ color: 'var(--app-text-muted)' }}>
          Commune d'Ambam — Région du Sud, Cameroun
        </p>
      </div>

      <div className="container py-4 py-md-5" style={{ maxWidth: '1100px' }}>
        {/* Titre "Mot du Maire" */}
        <div className="text-center mb-4" data-aos="fade-up">
          <span
            className="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3"
            style={{
              backgroundColor: 'var(--app-primary-soft)',
              color: ACCENT_DARK,
              fontSize: '0.8rem',
              fontWeight: 600
            }}
          >
            <MdRecordVoiceOver size={16} />
            Message officiel
          </span>
          <h2 className="fw-bold mb-0" style={{ color: ACCENT_DARK, fontSize: '2.1rem', letterSpacing: '0.5px' }}>
            Mot du Maire
          </h2>
          <div
            className="mx-auto mt-3"
            style={{ width: 64, height: 4, borderRadius: 999, backgroundColor: ACCENT_GOLD }}
          ></div>
        </div>

        {/* Photo + introduction */}
        <div className="row align-items-start g-4 g-lg-5 mb-5">
          <div className="col-12 col-lg-5" data-aos="fade-right">
            <div
              className="rounded-4 overflow-hidden"
              style={{ border: '1px solid var(--app-border)', boxShadow: '0 16px 36px var(--app-shadow)' }}
            >
              <img
                src={maire}
                alt="Le Maire de la Commune d'Ambam"
                style={{ width: '100%', height: '440px', objectFit: 'cover', display: 'block' }}
              />
              <div className="p-3 text-center" style={{ backgroundColor: 'var(--app-surface)' }}>
                <div className="fw-bold" style={{ color: ACCENT_DARK }}>
                  M. ZOMO OVONO Samson
                </div>
                <div className="small" style={{ color: 'var(--app-text-muted)' }}>
                  Maire de la Commune d'Ambam
                </div>
              </div>
            </div>
          </div>

          <div className="col-12 col-lg-7" data-aos="fade-left">
            <p
              className="fw-bold text-uppercase mb-3"
              style={{ color: ACCENT, letterSpacing: '1px', fontSize: '1rem' }}
            >
              Ambam : un nouvel envol
            </p>
            <div style={{ color: 'var(--app-text)', lineHeight: 1.9, textAlign: 'justify' }}>
              <p>
                La commune d'Ambam est, depuis le 26 février 2020, tournée vers de nouveaux objectifs, avec
                notamment l'entrée en matière d'un nouvel exécutif municipal conduit par Monsieur{' '}
                <strong style={{ color: ACCENT_DARK }}>ZOMO OVONO Samson</strong>.
              </p>
              <p>
                Après une année 2020 fortement marquée par la pandémie du COVID-19, l'année nouvelle 2021 se
                présente avec un nouveau visage et un espoir d'un meilleur lendemain. C'est l'année attendue
                pour la finalisation du processus de décentralisation.
              </p>
              <p className="mb-0">
                Faire d'Ambam une commune digne de ce nom, une véritable vitrine dans la sous-région, est un
                des objectifs fixés par la nouvelle équipe dirigeante. Plusieurs chantiers sont nécessaires
                pour atteindre ce noble et bel objectif :
              </p>
            </div>
          </div>
        </div>

        {/* Chantiers prioritaires */}
        <div className="mb-4" data-aos="fade-up">
          <div className="d-flex align-items-center gap-2 mb-4">
            <MdFlag size={20} style={{ color: ACCENT }} />
            <h3 className="fw-bold mb-0" style={{ color: ACCENT_DARK, fontSize: '1.3rem' }}>
              Les chantiers prioritaires
            </h3>
          </div>

          <div>
            {CHANTIERS.map((text, i) => (
              <div
                key={i}
                className="chantier-item"
                data-aos="fade-up"
                data-aos-delay={Math.min(i, 4) * 80}
              >
                <div className="chantier-rail">
                  <div
                    className="d-flex align-items-center justify-content-center fw-bold"
                    style={{
                      width: 36,
                      height: 36,
                      borderRadius: '50%',
                      backgroundColor: 'var(--app-primary-soft)',
                      color: ACCENT_DARK,
                      fontSize: '0.9rem',
                      flexShrink: 0
                    }}
                  >
                    {i + 1}
                  </div>
                  {i < CHANTIERS.length - 1 && <div className="chantier-connector"></div>}
                </div>

                <div className="pb-4">
                  <p style={{ color: 'var(--app-text)', lineHeight: 1.8, textAlign: 'justify', marginBottom: 0 }}>
                    {text}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>

     
      </div>
    </div>
  );
}