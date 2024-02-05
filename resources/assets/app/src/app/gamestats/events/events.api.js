import api from '@/framework/api';

export function storeEvent(payload) {
    return api.post('events', payload);
}
