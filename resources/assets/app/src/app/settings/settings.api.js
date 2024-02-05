import api from '@/framework/api';

export function updateSettings(key, payload) {
    return api.patch(`settings/${key}`, payload);
}
