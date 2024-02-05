import api from '@/framework/api';

export function getPlayers(params) {
    return api.get('players', {
        params: {
            page: 1,
            per_page: 15,
            sort: 'name:asc',
            ...params,
        },
    });
}

export function storePlayer(payload) {
    return api.post('players', payload);
}

export function getPlayer(id) {
    return api.get(`players/${id}`);
}

export function updatePlayer(id, payload) {
    return api.patch(`players/${id}`, payload);
}

export function deletePlayer(id) {
    return api.delete(`players/${id}`);
}
