import React, {useEffect} from 'react';
import {useNavigate} from 'react-router-dom';

const BACKEND_URL = process.env.NODE_ENV === 'production'
    ? 'https://backend.estlc-unv-ebolowa.com'
    : 'http://localhost:8000';

const AfficheDonnee = () => {
    const navigate = useNavigate();
    const ca_store = JSON.parse(localStorage.getItem("candidat"));

    useEffect(() => {
        if (!ca_store?.ca_code) {
            navigate('/candidate');
            return;
        }
        window.location.href = `${BACKEND_URL}/impression/${ca_store.ca_code}`;
        const timer = setTimeout(() => navigate('/success'), 2000);
        return () => clearTimeout(timer);
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    return (
        <div style={{display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100vh'}}>
            <p>Téléchargement de votre fiche en cours...</p>
        </div>
    );
};

export default AfficheDonnee;
