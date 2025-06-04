import { GET_CENTRE_DEPOT, GET_CENTRE_EXAMEN, GET_SESSION_CONCOURS_API, GET_SITE_ETUDE_API, GET_SITE_COMPO_API, GET_DOSSIER_API } from "../index";

export async function getCentreDepot() {
    const { url, ...meta } = GET_CENTRE_DEPOT;
    return await fetch(url, {...meta,credentials:'include'})
}

export async function getCentreExamen() {
    const { url, ...meta } = GET_CENTRE_EXAMEN;
    return await fetch(url, {...meta,credentials:'include'})
}

export async function getSiteFormation() {
    const { url, ...meta } = GET_SITE_ETUDE_API;
    return await fetch(url, {...meta,credentials:'include'})
}

export async function getSessionConcours() {
    const { url, ...meta } = GET_SESSION_CONCOURS_API;
    return await fetch(url, {...meta,credentials:'include'})
}

export async function getSiteComposition() {
    const { url, ...meta } = GET_SITE_COMPO_API;
    return await fetch(url, {...meta,credentials:'include'})
}

export async function getDossier(){
    const { url, ...meta } = GET_DOSSIER_API
    return await fetch(url, {...meta,credentials:'include'})
}
