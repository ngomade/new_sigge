import {LOGIN_API, LOGOUT_API, RESET_PASSWAORD, RESET_PASSWORD_CONFIRM} from "../index";

export async function loginAPI(data) {
    const { url, ...meta } = LOGIN_API;
    return await fetch(url, {...meta,body:JSON.stringify(data),credentials:'include'})
}
export async function resetPwdAPI(data) {
    const { url, ...meta } = RESET_PASSWAORD;
    return await fetch(url, {...meta,body:JSON.stringify(data),credentials:'include'})
}
export async function resetPwdAPIWithToken(data) {
    const { url, ...meta } = RESET_PASSWORD_CONFIRM;
    return await fetch(url, {
        ...meta,
        body: JSON.stringify(data),
        credentials: 'include'
    });
}
export async function logoutAPI(){
    const {url, ...meta} = LOGOUT_API;
    return await fetch(url, {...meta, credentials: 'include'});
}