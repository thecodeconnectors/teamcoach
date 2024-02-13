import api from '@/framework/api/index.js';

export function register(payload) {
    return api.post('register', payload);
}

export function login(payload) {
    return api.post('login', payload);
}

export function logout() {
    return api.post('logout');
}

export function startSession() {
    return api.get('cookie');
}

export function getUser() {
    return api.get('user');
}

export function sendPasswordResetLink(payload) {
    return api.post('forgot-password', payload);
}

export function resetPassword(payload) {
    return api.post('reset-password', payload);
}
