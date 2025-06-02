import { GET_SERIES_API } from "..";

export async function getSeries(data) {
    const { url, ...meta } = GET_SERIES_API;
    return await fetch(url, {...meta,body:JSON.stringify(data),credentials:'include'})
}
