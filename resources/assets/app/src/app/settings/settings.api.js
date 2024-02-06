import api from '@/framework/api';

export function getSettings() {
    return api.get('settings');
}

export function updateSettings(key, payload) {
    return api.patch(`settings/${key}`, payload);
}
