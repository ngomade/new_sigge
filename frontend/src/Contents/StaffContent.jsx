import React, { useEffect } from 'react';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { MdEmail, MdPhone, MdBadge, MdGroups } from 'react-icons/md';

import directeur from '../assets/img/team/directeur.jpg';
import da from '../assets/img/team/da.jpg';
import crep from '../assets/img/team/crep.jpg';
import lankoul from '../assets/img/team/lankoul.jpg';
import cisi from '../assets/img/team/cisi.jpg';
import socas from '../assets/img/team/socas.jpg';
import daarc from '../assets/img/team/daarc.jpg';
import mbiam from '../assets/img/team/mbiam.jpg';
import nana from '../assets/img/team/nana.jpg';
import azong from '../assets/img/team/azong.jpg';
import mvogo from '../assets/img/team/mvogo.png';
import abena from '../assets/img/team/abena.jpg';
import dsse from '../assets/img/team/dsse.jpg';
import djomo from '../assets/img/team/djomo.jpg';
import ebolo from '../assets/img/team/ebolo.jpg';
import nanga from '../assets/img/team/nanga.jpg';
import assoumou from '../assets/img/team/assoumou.jpg';
import manga from '../assets/img/team/manga.jpg';
import paki from '../assets/img/team/paki.jpg';
import dfcd from '../assets/img/team/dfcd.jpg';
import sfc from '../assets/img/team/sfc.jpg';
import sfoad from '../assets/img/team/sfoad.jpg';
import nballa from '../assets/img/team/nballa.png';
import mboussi from '../assets/img/team/mboussi.jpg';
import dgtp from '../assets/img/team/dgtp.jpg';
import sapi from '../assets/img/team/sapi.jpg';
import dgmc from '../assets/img/team/dgmc.jpg';
import messi from '../assets/img/team/messi.jpg';
import belinga from '../assets/img/team/belinga.jpg';
import dro from '../assets/img/team/dro.png';
import estlc_sans_fond from '../assets/share/img/estlc_sans_fond.png';

/* ============================================================
   DONNÉES — sections & personnel (reprises de la vue Blade)
   ============================================================== */
const SECTIONS = [
  {
    title: 'La Direction',
    members: [
      { img: directeur, noms: 'TAMBA', prenoms: 'Jean Gaston', grade: 'Professeur Titulaire', fonction: 'Directeur', email: 'directeur@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: da, noms: 'KOUMI NGOH', prenoms: 'Simon', grade: 'Maitre de Conférences', fonction: 'Directeur Adjoint', email: 'da@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: crep, noms: 'MOUZONG PEMI', prenoms: 'Marcelin', grade: 'Maitre de Conférences', fonction: "Chef de Centre de Recherche, d'Expérimentation et de Production", email: 'crep@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: lankoul, noms: 'LANGOUL', prenoms: 'FRANCIS', grade: "Professeur des Lycées D'enseignement Général", fonction: 'Chef de Centre de Documentation et des Archives', email: 'cda@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: cisi, noms: 'KEUDEM ZONING', prenoms: 'Steve', grade: "Professeur des Lycées D'enseignement Général", fonction: "Chef Cellule Informatique et Des Systèmes D'information", email: 'cisi@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: socas, noms: 'DANADAM', prenoms: 'Flavien', grade: "Conseiller Principal d'Orientation Scolaire", fonction: "Chef service de L'Orientation-Conseil et de L'Action Sociale", email: 'socas@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: estlc_sans_fond, noms: 'MFOM', prenoms: 'Guy Derosier', grade: "Professeur des Collèges d'Enseignements Secondaire Général", fonction: 'Chef service du Courrier et des Relations Publiques', email: 'scrp@estlc.unv-ebolowa.cm', tel: '(+237)' }
    ]
  },
  {
    title: 'Division des Affaires Académiques, de la Recherche et de la Coopération',
    members: [
      { img: daarc, noms: 'ONANA ESSAMA', prenoms: 'Bedel Giscard', grade: 'Chargé de Cours', fonction: 'Chef de Division des Affaires Académiques, de la Recherche et de la Coopération', email: 'daarc@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: mbiam, noms: 'MBIAM', prenoms: 'Salomon Parfait', grade: "Professeur des lycées d'Enseignement Technique et Professionnel", fonction: "Chef de Service des enseignements et de l'évaluation", email: 'see@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: nana, noms: 'NGAPOUT NANA', prenoms: 'Fadimatou', grade: "CPOSUP (Conseiller Principal d'Orientation Scolaire Universitaire et Professionnel)", fonction: 'Chef Service des Diplômes et de la Certification', email: 'sdc@estlc.unv-ebolowa.cm', tel: '(+237) 696918207' },
      { img: azong, noms: 'AZONG TCHITILE', prenoms: 'Emmanuel Wilfried', grade: 'Assistant', fonction: 'Chef Service du Personnel Enseignant', email: 'spe@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: mvogo, noms: 'MVOGO AHANDA', prenoms: 'Joseph Jean Baptiste', grade: 'Chargé de Cours', fonction: 'Chef service de la Recherche et de la Coopération', email: 'src@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: abena, noms: 'ABENA', prenoms: 'Michel Arnaud', grade: "Professeur des Lycées D'Enseignement Général", fonction: 'Chef Service de la Qualité et des Normes', email: 'abenamcjoss2@gmail.com', tel: '(+237) 655537927' }
    ]
  },
  {
    title: 'Division de la Scolarité et du Suivi des Étudiants',
    members: [
      { img: dsse, noms: 'EDOU ESSEKO', prenoms: 'Martin Brice', grade: 'Assistant', fonction: 'Chef de Division de la Scolarité et du Suivi des Étudiants', email: 'dsse@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: djomo, noms: 'DJOMO ONDO', prenoms: 'Edmond Aimé', grade: 'Cadre Contractuel', fonction: 'Chef Service de la Scolarité et des Statistiques', email: 'sss@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: assoumou, noms: 'ASSOUMOU EMVO', prenoms: 'Jackson', grade: "Conseiller Principal d'Orientation", fonction: "Chef Service des Stages et de L'Insertion Professionnelle", email: 'ssip@estlc.unv-ebolowa.cm', tel: '(+237)' }
    ]
  },
  {
    title: 'Division des Affaires Administratives et Financières',
    members: [
      { img: estlc_sans_fond, noms: 'NTYAM ASSE', prenoms: 'Georges', grade: "Professeur des Lycées D'Enseignement Général", fonction: 'Chef de Division des Affaires Administratives et Financières', email: 'daaf@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: ebolo, noms: 'EBOLO', prenoms: 'Pierre Arnold', grade: '', fonction: 'Chef Service des Affaires Financières', email: 'saf@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: nanga, noms: 'NANGA ETOA', prenoms: 'Mireille Lorine', grade: "Professeur des Lycées D'Enseignement Technique et Professionnel", fonction: "Chef Service de l'Administration Générale et du Personnel non Enseignant", email: 'sagpne@estlc.unv-ebolowa.cm', tel: '(+237) 691289082' },
      { img: manga, noms: 'ETEME MANGA', prenoms: 'Cédric Wilfried', grade: "Professeur des Lycées D'Enseignement Général", fonction: 'Chef Service de la Maintenance et du Matériel', email: 'smm@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: paki, noms: 'PAKI', prenoms: 'Hervé', grade: "Professeur des Lycées d'Enseignement Général", fonction: "Chef Service de l'Animation Sportive et Culturelle", email: 'sasc@estlc.unv-ebolowa.cm', tel: '(+237)' }
    ]
  },
  {
    title: 'Division de la Formation Continue et à Distance',
    members: [
      { img: dfcd, noms: 'MVONDO', prenoms: 'Didier Serge', grade: "Professeur des Lycées D'Enseignement Secondaire Général", fonction: 'Chef de Division de la Formation Continue et à Distance', email: 'dfcd@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: sfc, noms: 'DJIEME EWOLE', prenoms: 'Omer Legrand', grade: "Professeur Adjoint des Écoles Normale d'Instituteurs", fonction: 'Chef Service de la Formation Continue', email: 'sfc@estlc.unv-ebolowa.cm', tel: '(+237) 699219769' },
      { img: sfoad, noms: 'NKONJOH NGOMADE', prenoms: 'Armel', grade: "Professeur des Lycées D'Enseignement Technique et Professionnelle", fonction: 'Chef Service de la Formation à Distance', email: 'sfd@estlc.unv-ebolowa.cm', tel: '(+237)' }
    ]
  },
  {
    title: 'Nos Départements',
    members: [
      { img: nballa, noms: 'MBALLA ELOUNDOU', prenoms: 'Aimé Christel', grade: 'Chargé de Cours', fonction: 'Chef de Département des Enseignements Généraux', email: 'depteg@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: mboussi, noms: 'MBOUSSI', prenoms: 'Serge Bertrand', grade: 'Chargé de Cours', fonction: 'Chef de Département des Enseignements Scientifiques de Base', email: 'deptesb@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: dgtp, noms: 'DIBOMA', prenoms: 'Benjamin Salomon', grade: 'Chargé de Cours', fonction: 'Chef de Département Génie des Transports', email: 'deptgt@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: sapi, noms: 'SAPNKEN', prenoms: 'Flavian Emmanuel', grade: 'Chargé de Cours', fonction: 'Chef de Département Génie Logistique', email: 'deptgl@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: dgmc, noms: 'KIBONG', prenoms: 'Marius Tony', grade: 'Chargé de Cours', fonction: 'Chef de Département Génie Mécatronique', email: 'deptgm@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: messi, noms: 'MESSI NGUELE', prenoms: 'Thomas', grade: 'Chargé de Cours', fonction: 'Chef de Département Génie Informatique', email: 'deptgi@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: belinga, noms: 'BELINGA BESSALA', prenoms: 'Jacob Patrick', grade: 'Chargé de Cours', fonction: 'Chef de Département E-Commerce', email: 'deptec@estlc.unv-ebolowa.cm', tel: '(+237)' },
      { img: dro, noms: 'KENMOE SIYOU', prenoms: 'Romuald Noel', grade: 'Chargé de Cours', fonction: 'Chef de Département de Recherche Opérationnelle', email: 'deptro@estlc.unv-ebolowa.cm', tel: '(+237)' }
    ]
  }
];

/* ============================================================
   CARTE PERSONNEL
   ============================================================ */
function PersonnelCard({ member, delay }) {
  const ACCENT = 'var(--app-primary)';
  const ACCENT_DARK = 'var(--app-primary-dark)';

  return (
    <div
      className="personnel-card rounded-4 p-3 text-center h-100"
      data-aos="fade-up"
      data-aos-delay={delay}
      style={{
        backgroundColor: 'var(--app-surface)',
        border: '1px solid var(--app-border)',
        boxShadow: '0 6px 16px var(--app-shadow-soft)',
        transition: 'transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out'
      }}
      onMouseEnter={(e) => {
        e.currentTarget.style.transform = 'translateY(-4px)';
        e.currentTarget.style.boxShadow = '0 14px 28px var(--app-shadow)';
      }}
      onMouseLeave={(e) => {
        e.currentTarget.style.transform = 'translateY(0)';
        e.currentTarget.style.boxShadow = '0 6px 16px var(--app-shadow-soft)';
      }}
    >
      <img
        src={member.img}
        alt={`${member.prenoms} ${member.noms}`}
        style={{
          width: 84,
          height: 84,
          objectFit: 'cover',
          borderRadius: '50%',
          border: '3px solid var(--app-primary-soft)',
          margin: '0 auto 0.75rem'
        }}
      />

      <div className="fw-bold small mb-1" style={{ color: ACCENT_DARK, lineHeight: 1.25 }}>
        {member.prenoms} {member.noms}
      </div>

      <div className="fw-semibold mb-1" style={{ color: ACCENT, fontSize: '0.78rem', lineHeight: 1.3 }}>
        {member.fonction}
      </div>

      {member.grade && (
        <div
          className="d-inline-flex align-items-center gap-1 mb-2"
          style={{ color: 'var(--app-text-muted)', fontSize: '0.7rem' }}
        >
          <MdBadge size={12} /> {member.grade}
        </div>
      )}

      <div className="d-flex flex-column gap-1" style={{ fontSize: '0.7rem' }}>
        {member.email && (
          <a
            href={`mailto:${member.email}`}
            className="d-flex align-items-center justify-content-center gap-1 text-truncate"
            style={{ color: 'var(--app-primary)' }}
          >
            <MdEmail size={12} style={{ flexShrink: 0 }} />
            <span className="text-truncate">{member.email}</span>
          </a>
        )}
        {member.tel && (
          <span
            className="d-flex align-items-center justify-content-center gap-1"
            style={{ color: 'var(--app-text-muted)' }}
          >
            <MdPhone size={12} /> {member.tel}
          </span>
        )}
      </div>
    </div>
  );
}

/* ============================================================
   SECTION
   ============================================================ */
function StaffSection({ title, members, sectionIndex }) {
  const ACCENT = 'var(--app-primary)';
  const ACCENT_DARK = 'var(--app-primary-dark)';

  return (
    <div className="mb-5">
      <div
        className="d-flex align-items-center gap-2 mb-3"
        data-aos="fade-right"
        data-aos-delay={sectionIndex * 40}
      >
        <span
          style={{
            width: 5,
            height: 26,
            borderRadius: 3,
            backgroundColor: ACCENT,
            display: 'inline-block',
            flexShrink: 0
          }}
        ></span>
        <h2 className="fw-bold mb-0" style={{ color: ACCENT_DARK, fontSize: '1.15rem' }}>
          {title}
        </h2>
        <span
          className="ms-1 px-2 py-1 rounded-pill small fw-semibold"
          style={{ backgroundColor: 'var(--app-primary-soft)', color: ACCENT_DARK, fontSize: '0.7rem' }}
        >
          {members.length}
        </span>
      </div>

      <div
        className="row g-3"
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fill, minmax(190px, 1fr))',
          gap: '1rem'
        }}
      >
        {members.map((member, i) => (
          <PersonnelCard key={`${title}-${i}`} member={member} delay={(i % 6) * 60} />
        ))}
      </div>
    </div>
  );
}

/* ============================================================
   COMPOSANT PRINCIPAL
   ============================================================ */
export default function StaffContent() {
  useEffect(() => {
    AOS.init({ duration: 800, once: true, easing: 'ease-out-quart' });
  }, []);

  const totalStaff = SECTIONS.reduce((sum, s) => sum + s.members.length, 0);

  return (
    <div className="staff-page container-fluid py-4 py-md-5" style={{ maxWidth: '1200px' }}>
      {/* En-tête */}
      <div className="text-center mb-5" data-aos="fade-up">
        <span
          className="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3"
          style={{
            backgroundColor: 'var(--app-primary-soft)',
            color: 'var(--app-primary-dark)',
            fontSize: '0.8rem',
            fontWeight: 600
          }}
        >
          <MdGroups size={16} />
          {totalStaff} membres
        </span>

        <h1 className="fw-bold mb-2" style={{ color: 'var(--app-primary-dark)', fontSize: '1.9rem' }}>
          Staff Administratif de l'ESTLC
        </h1>

        <p
          className="mb-0 mx-auto"
          style={{ color: 'var(--app-text-muted)', maxWidth: '620px', lineHeight: 1.6 }}
        >
          Découvrez l'équipe qui anime la direction, les divisions et les départements de l'établissement,
          avec leurs fonctions et leurs contacts respectifs.
        </p>
      </div>

      {/* Sections */}
      {SECTIONS.map((section, index) => (
        <StaffSection key={section.title} title={section.title} members={section.members} sectionIndex={index} />
      ))}
    </div>
  );
}