const BASE_URL = process.env.NODE_ENV === 'production' ? 'https://backend.estlc-unv-ebolowa.com' : 'http://localhost:8000';

const token = sessionStorage.getItem("token")
export const LOGIN_API = {
    url: `${BASE_URL}/login`,
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    }
}
export const GET_CANDIDATE_API = {
    url: `${BASE_URL}/candidat`,
    method: "GET",
    headers: {
        "Authorization": `Bearer ${token}`
    }
}
export const STORE_CANDIDATE_API = {
    url: `${BASE_URL}/candidat`,
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "Cookie":document.cookie
    }

}
export const STORE_COMPTE_API = {
    url: `${BASE_URL}/compte`,
    method: "POST",
    headers: {
        
    }
}
export const GET_COMPTE_API = {
    url: `${BASE_URL}/compte`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${token}`
    }
}


export const AUTH_CHECK_API = {
    url: `${BASE_URL}/check_token`,
    method: "POST",
    headers: {
        "Cookie":document.cookie,
        "Content-type":"application/json",
        "Authorization": `Bearer ${token}`
    }
}
export const GET_ECOLE_API = {
    url: `${BASE_URL}/ecole`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${token}`
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
       "Cookie": document.cookie
    }
}
export const GET_DIPLOME_API = {
    url: `${BASE_URL}/get-diplomes`,
    method: "POST",
    headers: {
       "Cookie": document.cookie,
       "Content-Type": "application/json"
    }
}
export const GET_FILLIERE_API = {
    url: `${BASE_URL}/filiere`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`
    }
}

export const GET_SERIES_API = {
    url: `${BASE_URL}/get-series`,
    method: "POST",
    headers: {
       //"Authorization": `Bearer ${token}`
       "Cookie": document.cookie,
       "Content-Type": "application/json"
    }
}
export const GET_CENTRE_DEPOT = {
    url: `${BASE_URL}/centre_depot`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`
    }
}
export const GET_CENTRE_EXAMEN = {
    url: `${BASE_URL}/centre_examen`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`
    }
}
export const GET_SITE_COMPO = {
    url: `${BASE_URL}/site_composition`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`
    }
}
export const GET_SITE_ETUDE_API = {
    url: `${BASE_URL}/site_etude`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`
    }
}
export const GET_SESSION_CONCOURS_API = {
    url: `${BASE_URL}/get-session`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`
    }
}

export const GET_SITE_COMPO_API = {
    url: `${BASE_URL}/site_composition`,
    method: "GET",
    headers: {
       //"Authorization": `Bearer ${token}`
    }
}
export const GET_CANDIDATE_DATA_API = {
    url: `${BASE_URL}/get-candidat-all-info`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${token}`
    }
}

export const GET_CANDIDATE_STAT_API = {
    url: `${BASE_URL}/get-candidat-stat`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${token}`
    }
}

export const GET_COMPTE_STAT_API = {
    url: `${BASE_URL}/get-compte-stat`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${token}`
    }
}
export const GET_CANDIDATE_BY_CENTRE_API = {
    url: `${BASE_URL}/get-candidat-by-centre`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${token}`
    }
}
export const RESET_PASSWAORD = {
    url: `${BASE_URL}/reset-password`,
    method: "POST",
    headers: {
        "Authorization": `Bearer ${token}`,
        "Content-Type": "application/json"
    }
}

export const GET_COMPTE_SHOW_RECU = {
    url: `${BASE_URL}/compte-show-recu`,
    method: "GET",
    headers: {
       "Authorization": `Bearer ${token}`
    }
}

export const DEL_COMPTE_API = {
    url: `${BASE_URL}/compte`,
    method: "DELETE",
    headers: {
       "Authorization": `Bearer ${token}`
    }
}
