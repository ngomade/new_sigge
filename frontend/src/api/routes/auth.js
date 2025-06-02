import { LOGIN_API, RESET_PASSWAORD } from "../index";

export async function loginAPI(data) {
    const { url, ...meta } = LOGIN_API;
    return await fetch(url, {...meta,body:JSON.stringify(data),credentials:'include'})
}
export async function resetPwdAPI(data) {
    const { url, ...meta } = RESET_PASSWAORD;
    return await fetch(url, {...meta,body:JSON.stringify(data),credentials:'include'})
}