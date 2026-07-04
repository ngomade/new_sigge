import React, {useEffect, useRef, useState} from 'react';
import headerImg from '../img/header.png';
import "./pdf.css"
import html2pdf from 'html2pdf.js';
import {useNavigate} from 'react-router-dom';
import Loading from '../components/stepModal/Loading';

const BASE_URL = process.env.NODE_ENV === 'production'
    ? '/api/concours'
    : 'http://localhost:8000/api/concours';

const AfficheDonnee = () => {
    const [ca, setCa] = useState(null);
    const [error, setError] = useState(null);
    const docRef = useRef();
    const navigate = useNavigate();

    useEffect(() => {
        const candidat = JSON.parse(localStorage.getItem("candidat"));
        const code = candidat?.ca_code;

        if (!code) {
            setError("Aucun code candidat trouvé");
            return;
        }

        fetch(`${BASE_URL}/candidat/${code}`, {
            method: "GET",
            headers: {
                "Authorization": `Bearer ${localStorage.getItem('token')}`,
                "Accept": "application/json",
            },
            credentials: "include"
        })
            .then(res => {
                console.log("API status:", res.status);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then(data => {
                console.log("API data:", data);
                setCa(data);
            })
            .catch(err => {
                console.error("API error:", err);
                setError(err.message);
            });
    }, []);

    useEffect(() => {
        if (!ca) return;
        if (!docRef.current) return;

        const timer = setTimeout(() => {
            html2pdf()
                .set({
                    margin: 0.2,
                    filename: 'Fiche_Inscription_' + ca.ca_nom + '.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                })
                .from(docRef.current)
                .save()
                .then(() => navigate('/success'));
        }, 500);

        return () => clearTimeout(timer);
    }, [ca, navigate]);

    if (error) return <div style={{padding: 40, textAlign: 'center', color: 'red'}}><p>Erreur: {error}</p></div>;
    if (!ca) return <Loading />;

    return (
        <div>
            <div id='pdf-content' ref={docRef}>
                <header>
                    <img src={headerImg} alt="entete de la fiche"/>
                </header>
                <div>
                    <table className="entete">
                        <tbody>
                        <tr>
                            <td rowSpan="2" style={{width: '15%'}}>
                                <img src={`/storage/app/public/cartes/${new Date().getFullYear()}/${ca.ca_photo}`}
                                     alt="description du Candidat"/>
                            </td>
                            <td colSpan="2" className="titre" style={{paddingBottom: 0}}>
                                FICHE D'INSCRIPTION AU CONCOURS D'ENTREE à L'ESTLC SESSION {new Date().getFullYear()}
                                <hr style={{margin: 0}}/>
                                <span style={{color: 'rgb(19, 115, 224)'}}>CURSUS INGENIEUR</span>
                            </td>
                        </tr>
                        <tr>
                            <td style={{width: '70%', textAlign: 'center'}}>
                                INSCRIPTION N° <span style={{color: 'red'}}>{ca.ca_code}</span>
                            </td>
                            <td style={{
                                width: '40%',
                                textAlign: 'center',
                                fontFamily: 'Arial Narrow',
                                fontStyle: 'italic'
                            }}>
                                Timbre Fiscal ici / Stamp here
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div className="info">
                    <h3 style={{marginTop: '5px', marginBottom: '8px'}}><span>Informations Personnelles / Personal Informations</span></h3>
                    <div className="item-info-2">Nom: <span style={{textTransform: 'uppercase'}}>{ca.ca_nom}</span></div>
                    <div className="item-info-2">Prénom: <span style={{textTransform: 'uppercase'}}>{ca.ca_prenom}</span></div>
                    <div className="item-info-3">Date naissance: <span>{ca.ca_date_naiss?.slice(0, 10)}</span></div>
                    <div className="item-info-2">Lieu de naissance: <span>{ca.ca_lieu_naiss}</span></div>
                    <div className="item-info-4">Sexe: <span>{ca.ca_sexe}</span></div>
                    <div className="item-info-4">Nationalité: <span>{ca.ca_nationalite}</span></div>
                    <div className="item-info-3">Région d'origine: <span>{ca.ca_region_origine}</span></div>
                    <div className="item-info-2">Département d'origine: <span>{ca.ca_depart_origine}</span></div>
                    <div className="item-info-3">CNI: <span>{ca.ca_num_cni}</span></div>
                    <div className="item-info-3">Téléphone: <span>{ca.ca_telephone}</span></div>
                    <div className="item-info-2">Adresse: <span style={{textTransform: 'uppercase'}}>{ca.ca_adresse}</span></div>
                    <div className="item-info-2">1ère Langue: <span>{ca.ca_premiere_lang}</span></div>
                    <div className="item-info-2">Email: <span>{ca.ca_email}</span></div>
                </div>
                <div className="info">
                    <h3 style={{marginTop: '-8px'}}><span>Informations Académique / Academic Informations</span></h3>
                    <div className="item-info-3">Filière: <span style={{fontSize: '0.8em'}}>{ca.filiere_code?.label_filiere || ca.filiere_code?.code_filiere || ''}</span></div>
                    <div className="item-info-2">Diplôme d'admission: <span>{ca.ca_diplome_admission?.label_dip || ''} {ca.ca_serie_diplome?.label_serie || ''}</span></div>
                    <div className="item-info-4">Mention: <span>{ca.ca_mention_diplome}</span></div>
                    <div className="item-info-3">Année diplôme: <span>{ca.ca_annee_diplome}</span></div>
                    <div className="item-info-3">Centre d'examen: <span>{ca.ca_centre_examen?.centre_exam_label || ''}</span></div>
                    <div className="item-info-2">Centre de dépôt: <span>{ca.ca_centre_depot?.centre_depot_label || ''}</span></div>
                </div>
                <div className="info">
                    <h3 style={{marginTop: '-5px'}}><span>Autres Informations / Others Informations</span></h3>
                    <div className="item-info-2">Nom du père: <span style={{textTransform: 'uppercase'}}>{ca.ca_nom_pere}</span></div>
                    <div className="item-info-2">Téléphone du père: <span>{ca.ca_telephone_pere}</span></div>
                    <div className="item-info-2">Nom de la mère: <span style={{textTransform: 'uppercase'}}>{ca.ca_nom_mere}</span></div>
                    <div className="item-info-2">Téléphone de la mère: <span>{ca.ca_telephone_mere}</span></div>
                </div>
                <div className="consigne">
                    <h3 style={{marginTop: '-15px'}}>Documents Nécessaires / Necessary Documents</h3>
                    <ol style={{marginTop: '5px', listStyleType: 'circle'}}>
                        <br />
                        <li>Une photocopie certifiée d'acte de naissance datant de moins de trois (3) mois;
                            <span className="english">A certified true photocopy of the birth certificate issued within the last three (03) months;</span>
                        </li>
                        <li>Une photocopie certifiée conforme du diplôme/attestation requis; <span className="english">A certified true copy of the required diploma;</span>
                        </li>
                        <li>Un certificat médical délivré par un médecin fonctionnaire, datant de moins de trois (03)
                            mois et certifiant que le candidat est apte à poursuivre des études supérieures;
                            <span className="english">A medical certificate issued within the last three (03) months by a state medical practitioner, and testifying that the candidate is fit for higher education;</span>
                        </li>
                        <li>Quatre (04) photos d'identité 4x4 du candidat;
                            <span className="english">Four (04) 4x4 identity photos of the candidate;</span>
                        </li>
                        <li>Un reçu de versement bancaire d'un montant de 20 000F pour les 1ières années et de 25 000F pour les 3ièmes années;
                            <span className="english">A bank deposit receipt of 20,000F for first-year students and 25,000F for third-year students.</span>
                        </li>
                        <li>Une enveloppe A4 timbrée au tarif réglementaire et portant l'adresse exacte du candidat;
                            <span className="english">A 21 x 29.7 size self-addressed envelope bearing a 400 CFA francs postal stamp</span>
                        </li>
                    </ol>
                </div>
                <footer>
                    <div className="connexion">Code Candidat: <span style={{color: 'red'}}>{ca.ca_num_recu}</span></div>
                    <div className="imp">Imprimée le {new Date().toLocaleDateString()}</div>
                </footer>
            </div>
        </div>
    )
}

export default AfficheDonnee;
