import {LOGIN_API, LOGOUT_API, RESET_PASSWAORD, RESET_PASSWORD_CONFIRM, AUTH_CHECK_API} from "../index";

export async function loginAPI(data) {
    const {url, ...meta} = LOGIN_API;
    return await fetch(url, {...meta, body: JSON.stringify(data), credentials: 'include'})
}

export async function resetPwdAPI(data) {
    const {url, ...meta} = RESET_PASSWAORD;
    return await fetch(url, {...meta, body: JSON.stringify(data), credentials: 'include'})
}

export async function resetPwdAPIWithToken(data) {
    const {url, ...meta} = RESET_PASSWORD_CONFIRM;
    return await fetch(url, {
        ...meta,
        body: JSON.stringify(data),
        credentials: 'include'
    });
}

export async function logoutAPI() {
    const {url, ...meta} = LOGOUT_API;
    return await fetch(url, {
        ...meta, headers: {
            ...meta.headers,
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        }
    });
}

export async function checkAuthAPI() {
    const token = localStorage.getItem('token');
    if (!token) return { valid: false };
    const headers = {
        ...AUTH_CHECK_API.headers,
        Authorization: `Bearer ${token}`,
    };
    try {
        const res = await fetch(AUTH_CHECK_API.url, {
            method: AUTH_CHECK_API.method,
            headers,
        });
        if (res.ok) {
            return { valid: true };
        } else {
            // Si la réponse n'est pas OK, on considère que l'authentification n'est pas valide
            localStorage.removeItem('token');
            localStorage.removeItem('type_token');
            localStorage.removeItem('user');
            localStorage.removeItem('user_type');
            localStorage.removeItem('candidat');
            return { valid: false };
        }
    } catch (e) {
        return { valid: false };
    }
}

