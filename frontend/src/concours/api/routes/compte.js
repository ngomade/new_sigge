import { DEL_COMPTE_API, GET_COMPTE_API, GET_COMPTE_SHOW_RECU, GET_COMPTE_STAT_API, STORE_COMPTE_API } from "../index";
/**
 * Envoie des données de compte avec une image.
 * 
 * @param {Object} data - Les données à envoyer.
 * @param {File} image - Le fichier d'image à envoyer.
 * @returns {Promise<Response>} La réponse de la requête fetch.
 */
export async function createCompte(data, image) {
    const { url, ...meta } = STORE_COMPTE_API;

    // Utilisation de FormData pour envoyer des données et un fichier
    const formData = new FormData();
    
    // Ajouter les données au FormData
    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            formData.append(key, data[key]);
        }
    }

    // Ajouter le fichier d'image au FormData
    if (image) {
        formData.append('ca_recu', image);
    }

    // Mise à jour de la méthode et des headers pour l'utilisation de FormData
    const fetchOptions = {
        ...meta,
        body: formData,
    };

    return await fetch(url, fetchOptions);
}

/**
 * 
 * @returns 
 */
export async function getCompte() {
    const { url, ...meta } = GET_COMPTE_API;
    return await fetch(url, meta)
}
export async function getCompteStat() {
    const { url, ...meta } = GET_COMPTE_STAT_API;
    return await fetch(url, meta)
}
export async function showRecu(params) {
    const { url, ...meta } = GET_COMPTE_SHOW_RECU;
    return await fetch(url+`/${params}`, {...meta,credentials:'include'})
}
export async function deleteCompte(params) {
    const { url, ...meta } = DEL_COMPTE_API;
    return await fetch(url+`/${params}`, {...meta,credentials:'include'})
}