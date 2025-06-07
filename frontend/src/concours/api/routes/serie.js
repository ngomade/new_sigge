import { GET_SERIES_API } from "../index";

export async function getSeries(data) {
    const { url, ...meta } = GET_SERIES_API;
    return await fetch(`${url}/${data.filiere_code}/${data.code_dip}`, {...meta})
}
