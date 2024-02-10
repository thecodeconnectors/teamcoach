import api from '@/framework/api';

export function getSettings() {
    return api.get('settings');
}

export function storeSetting(key, payload) {
    return api.post(`settings/${key}`, payload);
}
