import api from '@/framework/api';

export function storeEvent(payload) {
    return api.post('events', payload);
}

export function patchEvent(id, payload) {
    return api.patch(`events/${id}`, payload);
}
