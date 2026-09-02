import React, { useState, useEffect } from 'react';
import AOS from 'aos';
import 'aos/dist/aos.css';
import organigram from '../assets/organigramme.png';
import {
  MdAccountTree,
  MdZoomOutMap,
  MdDownload,
  MdClose,
  MdInfoOutline
} from 'react-icons/md';

export default function OrganigramContent() {
  const [lightboxOpen, setLightboxOpen] = useState(false);

  const ACCENT = 'var(--app-primary)';
  const ACCENT_DARK = 'var(--app-primary-dark)';
  const ACCENT_GOLD = 'var(--app-accent)';

  useEffect(() => {
    AOS.init({ duration: 900, once: true, easing: 'ease-out-quart' });
  }, []);

  useEffect(() => {
    if (!lightboxOpen) return;
    const onKeyDown = (e) => {
      if (e.key === 'Escape') setLightboxOpen(false);
    };
    document.addEventListener('keydown', onKeyDown);
    document.body.style.overflow = 'hidden';
    return () => {
      document.removeEventListener('keydown', onKeyDown);
      document.body.style.overflow = '';
    };
  }, [lightboxOpen]);

  return (
    <div className="organigram-page container py-4 py-md-5" style={{ maxWidth: '1000px' }}>
      {/* En-tête de présentation */}
      <div className="text-center mb-4 mb-md-5" data-aos="fade-up">
        

        <h1 className="fw-bold mb-2" style={{ color: ACCENT_DARK, fontSize: '1.9rem' }}>
          Organigramme institutionnel
        </h1>

        <p
          className="mb-0 mx-auto"
          style={{ color: 'var(--app-text-muted)', maxWidth: '640px', lineHeight: 1.6 }}
        >
          L'organigramme ci-dessous présente la structure organisationnelle de l'ESTLC : la répartition
          des directions, services et départements, ainsi que les liens hiérarchiques et fonctionnels
          qui organisent le fonctionnement de l'établissement.
        </p>
      </div>

      {/* Carte contenant l'image */}
      <div
        className="rounded-4 overflow-hidden mb-4"
        data-aos="zoom-in-up"
        data-aos-delay="100"
        style={{
          backgroundColor: 'var(--app-surface)',
          border: '1px solid var(--app-border)',
          boxShadow: '0 16px 36px var(--app-shadow)'
        }}
      >
        <div
          className="d-flex align-items-center justify-content-between px-3 px-md-4 py-3"
          style={{ borderBottom: '1px solid var(--app-border)', backgroundColor: 'var(--app-surface-alt)' }}
        >
          <div className="d-flex align-items-center gap-2">
            <span
              className="rounded-circle"
              style={{ width: 8, height: 8, backgroundColor: ACCENT, display: 'inline-block' }}
            ></span>
            <span className="fw-semibold small" style={{ color: ACCENT_DARK }}>
              Structure organisationnelle
            </span>
          </div>

          <div className="d-flex align-items-center gap-2">
            <button
              type="button"
              onClick={() => setLightboxOpen(true)}
              className="btn btn-sm rounded-pill d-inline-flex align-items-center gap-1"
              style={{
                backgroundColor: 'transparent',
                border: '1px solid var(--app-border)',
                color: 'var(--app-text)',
                fontSize: '0.78rem',
                padding: '0.35rem 0.85rem'
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.backgroundColor = 'var(--app-primary-soft)';
                e.currentTarget.style.borderColor = 'var(--app-primary)';
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.backgroundColor = 'transparent';
                e.currentTarget.style.borderColor = 'var(--app-border)';
              }}
            >
              <MdZoomOutMap size={15} /> Agrandir
            </button>

            <a
              href={organigram}
              download="organigramme-estlc.png"
              className="btn btn-sm rounded-pill d-inline-flex align-items-center gap-1"
              style={{
                backgroundColor: ACCENT,
                border: `1px solid ${ACCENT}`,
                color: 'var(--app-text-on-primary)',
                fontSize: '0.78rem',
                padding: '0.35rem 0.85rem',
                textDecoration: 'none'
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.backgroundColor = ACCENT_DARK;
                e.currentTarget.style.borderColor = ACCENT_DARK;
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.backgroundColor = ACCENT;
                e.currentTarget.style.borderColor = ACCENT;
              }}
            >
              <MdDownload size={15} /> Télécharger
            </a>
          </div>
        </div>

        <div
          className="p-3 p-md-4 d-flex align-items-center justify-content-center"
          style={{ backgroundColor: 'var(--app-surface)' }}
        >
          <img
            src={organigram}
            alt="Organigramme de l'ESTLC"
            onClick={() => setLightboxOpen(true)}
            style={{
              width: '100%',
              height: 'auto',
              borderRadius: 'var(--app-radius-md)',
              cursor: 'zoom-in',
              display: 'block'
            }}
          />
        </div>
      </div>


      {/* Lightbox plein écran */}
      {lightboxOpen && (
        <div
          role="dialog"
          aria-modal="true"
          onClick={() => setLightboxOpen(false)}
          style={{
            position: 'fixed',
            inset: 0,
            zIndex: 1050,
            backgroundColor: 'rgba(17, 61, 53, 0.75)',
            backdropFilter: 'blur(4px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '2rem 1rem',
            cursor: 'zoom-out'
          }}
        >
          <button
            type="button"
            onClick={() => setLightboxOpen(false)}
            aria-label="Fermer"
            className="d-inline-flex align-items-center justify-content-center rounded-circle"
            style={{
              position: 'absolute',
              top: 20,
              right: 20,
              width: 40,
              height: 40,
              backgroundColor: 'var(--app-surface)',
              border: 'none',
              color: ACCENT_DARK,
              fontSize: '1.3rem',
              cursor: 'pointer'
            }}
          >
            <MdClose />
          </button>

          <img
            src={organigram}
            alt="Organigramme de l'ESTLC — vue agrandie"
            onClick={(e) => e.stopPropagation()}
            style={{
              maxWidth: '100%',
              maxHeight: '90vh',
              borderRadius: 'var(--app-radius-md)',
              boxShadow: '0 20px 50px rgba(0,0,0,0.35)',
              cursor: 'default'
            }}
          />
        </div>
      )}
    </div>
  );
}