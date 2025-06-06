import React, {useEffect, useState} from 'react';
import {useNavigate, Outlet} from 'react-router-dom';
import {checkAuthAPI} from '../api/routes/auth';
import Loading from "./stepModal/Loading";

/**
 * GuestRoute : protège les routes accessibles uniquement aux utilisateurs non authentifiés.
 * Vérifie la validité réelle du token via checkAuthAPI avant de laisser passer l'utilisateur.
 * Redirige si le token est valide, sinon laisse passer.
 */
const GuestRoute = () => {
    const [checking, setChecking] = useState(true);
    const [isValid, setIsValid] = useState(false);
    const navigate = useNavigate();

    useEffect(() => {
        async function check() {
            const result = await checkAuthAPI();
            setIsValid(result.valid);
            setChecking(false);
        }
        check();
        // Ajout d'un listener pour détecter les changements de token dans d'autres onglets
        const onStorage = (e) => {
            if (e.key === 'token') {
                setChecking(true);
                check();
            }
        };
        window.addEventListener('storage', onStorage);
        return () => window.removeEventListener('storage', onStorage);
    }, []);

    if (checking) return <Loading/>;

    if (isValid) {
        // Si on vient d'une autre page protégée, on redirige en arriere
        return navigate(-1);
    }
    return <Outlet/>;
};

export default GuestRoute;

