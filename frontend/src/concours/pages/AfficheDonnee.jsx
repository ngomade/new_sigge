<<<<<<< Updated upstream
import React, { useEffect, useRef, useState } from 'react';
import { useSelector } from 'react-redux';
import headerImg from '../img/header.png';
import "./pdf.css"
import html2pdf from 'html2pdf.js';
import { getCandidateInfo } from '../api/routes/candidate';
import { useNavigate } from 'react-router-dom';

const AfficheDonnee = () => {
  const { finish, ...candidate } = useSelector((state) => state.candidate.candidate_state);
  const [ca, setCandidate] = useState(candidate??{});
  const docRef = useRef();
  const ca_store = JSON.parse(localStorage.getItem("candidat"));
  const navigate = useNavigate()
  const [boot,setBoot] = useState(false)
  
  function getCandidate() {
    try {
      getCandidateInfo(ca_store?.ca_code).then(res => {
        if (res.status === 200) {
          res.json().then(data => setCandidate(data));
          setBoot(true)
        }else{
          setBoot(false)
        }
      });
    } catch (error) {
      console.log(error);
    }
  }

  useEffect(() => {
    getCandidate();
  }, []);

  useEffect(() => {
      if (boot && Object.keys(ca).length !== 0) {
        if (downloadPdf()) {
          navigate('/success')
        } else {
          navigate('/candidate')
        }
      }
    
  }, [ca]);

  function downloadPdf() {
    const element = docRef.current;
    const opt = {
      margin: 0.2,
      filename: 'Fiche_Inscription_'+ca.ca_nom+'.pdf',
      image: {
        type: 'jpeg',
        quality: 0.98,
      },
      html2canvas: {
        scale: 2,
      },
      jsPDF: {
        unit: 'mm',
        format: 'a4',
        orientation: 'portrait',
      },
    };
    return !!html2pdf().set(opt).from(element).save();

  }

  return (
    <div>
      <div id='pdf-content' ref={docRef}>
        <header>
          <img src={headerImg} alt="entete de la fiche" />
        </header>
        <div>
          <table className="entete">
            <tbody>
              <tr>
                <td rowSpan="2" style={{ width: '15%' }}>
                  <img src={`/storage/app/public/cartes/${new Date().getFullYear()}/${ca.ca_photo}`} alt="Photo du Candidat" />
                </td>
                <td colSpan="2" className="titre" style={{ paddingBottom: 0 }}>
                  FICHE D'INSCRIPTION AU CONCOURS D'ENTREE à L'ESTLC SESSION {new Date().getFullYear()}
                  <hr style={{ margin: 0 }} />
                  <span style={{ color: 'rgb(19, 115, 224)' }}>CURSUS INGENIEUR</span>
                </td>
              </tr>
              <tr>
                <td style={{ width: '70%', textAlign: 'center' }}>
                  INSCRIPTION N° <span style={{ color: 'red' }}>{ca.ca_code}</span>
                </td>
                <td style={{ width: '40%', textAlign: 'center', fontFamily: 'Arial Narrow', fontStyle: 'italic' }}>
                  Timbre Fiscal ici / Stamp here
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div className="info">
          <h3 style={{ marginTop: '5px', marginBottom: '8px' }}><span>Informations Personnelles / Personal Informations</span></h3>
          <div className="item-info-2">Nom: <span style={{ textTransform: 'uppercase', marginTop: '10px' }}>{ca.ca_nom}</span></div>
          <div className="item-info-2">Prénom: <span style={{ textTransform: 'uppercase', marginRight: '30px' }}>{ca.ca_prenom}</span></div>
          <div className="item-info-3">Date naissance: <span>{ca.ca_date_naiss?.slice(0, 10)}</span></div>
          <div className="item-info-2" style={{ marginLeft: '10px' }}>Lieu de naissance : <span>{ca.ca_lieu_naiss}</span></div>
          <div className="item-info-4" style={{ marginLeft: '-30px' }}>Sexe : <span>{ca.ca_sexe}</span></div>
          <div className="item-info-4" style={{ marginLeft: '15px', width: '150px' }}>Nationalité: <span style={{ fontSize: '0.9em' }}>{ca.ca_nationalite}</span></div>
          <div className="item-info-3" style={{ width: '200px' }}>Région d'origine: <span style={{ textAlign:'center' }}>{ca.ca_region_origine}</span></div>
          <div className="item-info-2" style={{ marginLeft: '10px', textAlign: 'center' }}>Département d'origine: <span>{ca.ca_depart_origine}</span></div>
          <div className="item-info-3" >CNI: <span style={{ fontSize:'0.85em' }}>{ca.ca_num_cni}</span></div>
          <div className="item-info-3" style={{ marginLeft: '-20px' }}>Téléphone: <span> {ca.ca_telephone}</span></div>
          <div className="item-info-2" style={{ marginLeft: '-10px' }}>Adresse: <span style={{textTransform:'uppercase'}}>{ca.ca_adresse}</span></div>
          <div className="item-info-2">1<sup>ère</sup> Langue: <span>{ca.ca_premiere_lang}</span></div>
          <div className="item-info-2">Email: <span>{ca.ca_email}</span></div>
        </div>
        <div className="info">
          <h3 style={{ marginTop: '-8px' }}><span>Informations Académique / Academic Informations</span></h3>
          <div className="item-info-3">Filière: <span style={{ fontSize: '0.8em', marginTop: '5px' }}>TRONC COMMUN {ca.filiere_code?.filiere_code}</span></div>
          <div className="item-info-2">Diplôme d'admission: <span>{ca.ca_diplome_admission?.label_dip} {ca.ca_serie_diplome?.label_serie}</span></div>
          <div className="item-info-4" style={{ marginLeft: '-25px' }}>Mention: <span>{ca.ca_mention_diplome}</span></div>
          <div className="item-info-3">Année diplôme: <span>{ca.ca_annee_diplome}</span></div>
          <div className="item-info-3" style={{ marginLeft: '-50px'}}>Centre d'examen: <span style={{ fontSize: '0.85em'}}>{ca.ca_centre_examen?.centre_exam_label}</span></div>
          <div className="item-info-2" style={{ marginLeft: '-5px'}}>Centre de dépôt: <span style={{fontSize: '0.85em'}}>{ca.ca_centre_depot?.centre_depot_label}</span></div>
        </div>
        <div className="info">
          <h3 style={{ marginTop: '-9px' }}><span>Autres Informations / Others Informations</span></h3>
          <div className="item-info-2" style={{ marginTop: '5px' }}>Nom du père: <span style={{textTransform:'uppercase'}}>{ca.ca_nom_pere}</span></div>
          <div className="item-info-2">Téléphone du père: <span>{ca.ca_telephone_pere}</span></div>
          <div className="item-info-2">Nom de la mère: <span style={{textTransform:'uppercase'}}>{ca.ca_nom_mere}</span></div>
          <div className="item-info-2">Téléphone de la mère: <span>{ca.ca_telephone_mere}</span></div>
        </div>
        <div className="consigne">
          <h3 style={{ marginTop: '-18px' }}>Documents Nécessaires / Necessary Documents</h3>
          <ol style={{ marginTop: '5px', listStyleType: 'circle' }}>
            <li>Une photocopie certifiée d'acte de naissance datant de moins de trois (3) mois;
              <br />
              <span className="english">A certified true photocopy of the birth certificate issued within the last three (03) months;</span>
            </li>
            <li>Une photocopie certifiée conforme du diplôme/attestation de réussite du Baccalauréat ou du GCE A/L ou du diplôme équivalent;
              <br />
              <span className="english">A certified true copy of the required diploma;</span>
            </li>
            <li>Un certificat médical délivré par un médecin fonctionnaire, datant de moins de trois (03) mois et certifiant que le candidat est apte à poursuivre des études supérieures;
              <br />
              <span className="english">A medical certificate issued within the last three (03) months by a state medical practitioner, and testifying that the candidate is fit for higher education;</span>
            </li>
            <li>Quatre (04) photos d'identité 4x4 du candidat;
              <br />
              <span className="english">Four (04) 4x4 identity photos of the candidate;</span>
            </li>
            <li>Un reçu de versement bancaire d'un montant de vingt mille (20 000) francs CFA représentant les droits d'inscription au concours de l'ESTLC de l'Université d'Ebolowa, à verser au compte SCB Cameroun, N° 10002-00059-90001487491-26 dans toutes les agences du Cameroun (Aucun autre mode de paiement ne sera accepté);
              <br />
              <span className="english">A twenty thousand (20,000) CFA francs banking receipt representing examination fees of the competitive entrance at HITLC of the University of Ebolowa to be paid at SCB Cameroon account, N° 10002-00059-90001487491-26 in all agencies in Cameroon (No other method of payment will be accepted);</span>
            </li>
            <li>Une enveloppe A4 timbrée au tarif réglementaire et portant l'adresse exacte du candidat;
              <br />
              <span className="english">A 21 x 29.7 size self-addressed envelope bearing a 400 CFA francs postal stamp</span>
            </li>
          </ol>
        </div>
        <footer>
          <div className="connexion">Code Candidat: <span style={{ color: 'red' }}>{ca.ca_num_recu}</span></div>
          <div className="imp">Imprimée le {new Date().toLocaleDateString()}</div>
        </footer>
      </div>
=======
import React from 'react';

const AfficheDonnee = () => {
  return (
    <div className="container">
      <h1>Affiche Donnée</h1>
      {/* Contenu à implémenter */}
>>>>>>> Stashed changes
    </div>
  );
};

<<<<<<< Updated upstream
export default AfficheDonnee;
=======
export default AfficheDonnee; 
>>>>>>> Stashed changes
