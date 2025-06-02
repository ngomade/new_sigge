import { GET_FILLIERE_API } from "..";

export async function getFiliere() {
    const { url, ...meta } = GET_FILLIERE_API;
    return await fetch(url, {...meta,credentials:'include'})
}
