const BASE_URL = process.env.NODE_ENV === 'production' ? '/api/concours' : 'http://localhost:8000/api/concours';

const getToken = () => localStorage.getItem('token');

export const LOGIN_API = {
    url: `${BASE_URL}/auth/login`,
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}
export const LOGOUT_API = {
    url: `${BASE_URL}/logout`,
    method: "GET",
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
    }
}
export const GET_CANDIDATE_API = {
    url: `${BASE_URL}/candidat`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}
export const STORE_CANDIDATE_API = {
    url: `${BASE_URL}/candidat`,
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }

}
export const STORE_COMPTE_API = {
    url: `${BASE_URL}/comptes`,
    method: "POST",
    headers: {
        "Accept": "application/json",
    }
}
export const GET_COMPTE_API = {
    url: `${BASE_URL}/compte`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}


export const AUTH_CHECK_API = {
    url: `${BASE_URL}/check-token`,
    method: "GET",
    headers: {
        "Content-type":"application/json",
        "Accept": "application/json",
    }
}
export const GET_ECOLE_API = {
    url: `${BASE_URL}/ecole`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`
    }
}
export const STORE_ECOLE_API = {
    url: `${BASE_URL}/ecole_api`,
    method: "POST",
    headers: {
       "Content-Type": "application/json"
    }
}
export const STORE_DOSSIER_API = {
    url: `${BASE_URL}/dossier`,
    method: "POST",
    headers: {
       "Content-Type": "application/json"
    }
}
export const GET_DOSSIER_API = {
    url: `${BASE_URL}/dossier`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
       "Cookie": document.cookie,
        "Accept": "application/json",
    }
}
export const GET_DIPLOME_API = {
    url: `${BASE_URL}/diplomes`,
    method: "GET",
    headers: {
       "Content-Type": "application/json",
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}
export const GET_FILLIERE_API = {
    url: `${BASE_URL}/filiere`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}

export const GET_SERIES_API = {
    url: `${BASE_URL}/series`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${getToken()}`,
       "Content-Type": "application/json",
        "Accept": "application/json",
    }
}
export const GET_CENTRE_DEPOT = {
    url: `${BASE_URL}/centre_depot`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}
export const GET_CENTRE_EXAMEN = {
    url: `${BASE_URL}/centre_examen`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",

    }
}
export const GET_SITE_COMPO = {
    url: `${BASE_URL}/site_composition`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`,
        "Accept": "application/json",

    }
}
export const GET_SITE_ETUDE_API = {
    url: `${BASE_URL}/sites-etude`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}
export const GET_SESSION_CONCOURS_API = {
    url: `${BASE_URL}/sessions/active`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`,
        "Accept": "application/json",

    }
}

export const GET_SITE_COMPO_API = {
    url: `${BASE_URL}/site_composition`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`,
        "Accept": "application/json",
    }
}
export const GET_CANDIDATE_DATA_API = {
    url: `${BASE_URL}/candidat`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",

    }
}

export const GET_CANDIDATE_STAT_API = {
    url: `${BASE_URL}/candidats/stats`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}

export const GET_COMPTE_STAT_API = {
    url: `${BASE_URL}/comptes/stats`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}
export const GET_CANDIDATE_BY_CENTRE_API = {
    url: `${BASE_URL}/candidats/get-candidats-by-centre`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}
export const RESET_PASSWAORD = {
    url: `${BASE_URL}/auth/forgot-password`,
    method: "POST",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Content-Type": "application/json",
        "Accept": "application/json",

    }
}
export const RESET_PASSWORD_CONFIRM = {
    url: `${BASE_URL}/auth/reset-password`,
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",

    }
}

export const GET_COMPTE_SHOW_RECU = {
    url: `${BASE_URL}/comptes/download-recu`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",

    }
}

export const DEL_COMPTE_API = {
    url: `${BASE_URL}/comptes`,
    method: "DELETE",
    headers: {
        "Authorization": `Bearer ${getToken()}`,
        "Accept": "application/json",
    }
}
