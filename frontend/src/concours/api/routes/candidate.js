import { GET_CANDIDATE_API, GET_CANDIDATE_BY_CENTRE_API, GET_CANDIDATE_DATA_API, GET_CANDIDATE_STAT_API, GET_DIPLOME_API, STORE_CANDIDATE_API } from "../index";

export async function createCandidate(body) {
    const { url, ...meta } = STORE_CANDIDATE_API;
    return await fetch(url, {...meta,body:JSON.stringify(body),credentials:'include'})
}

export async function getCandidate() {
    const { url, ...meta } = GET_CANDIDATE_API;
    return await fetch(url, meta)
}

export async function getDiplome(params) {
    const { url, ...meta } = GET_DIPLOME_API;
    return await fetch(`${url}/${params.filiere_code}/filiere`, {...meta,credentials:'include'})
}

export async function getCandidateInfo(params) {
    const { url, ...meta } = GET_CANDIDATE_DATA_API;
    return await fetch(url+`/${params}`, {...meta,credentials:'include'})
}
export async function getCandidateStat() {
    const { url, ...meta } = GET_CANDIDATE_STAT_API;
    return await fetch(url, meta)
}
export async function getCandidatesByCentre(params) {
    const { url, ...meta } = GET_CANDIDATE_BY_CENTRE_API;
    return await fetch(url+`/${params}`, {...meta,credentials:'include'})
}