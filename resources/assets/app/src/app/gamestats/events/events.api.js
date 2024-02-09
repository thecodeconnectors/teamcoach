import api from '@/framework/api';

export function storeEvent(payload) {
    return api.post('events', payload);
}

export function patchEvent(id, payload) {
    return api.patch(`events/${id}`, payload);
}

export function destroyEvent(id) {
    return api.delete(`events/${id}`);
}
