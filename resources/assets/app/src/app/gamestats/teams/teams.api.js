import api from '@/framework/api';

export function getTeams(params) {
    return api.get('teams', {
        params: {
            page: 1,
            per_page: 15,
            sort: 'name:asc',
            ...params,
        },
    });
}

export function storeTeam(payload) {
    return api.post('teams', payload);
}

export function getTeam(id) {
    return api.get(`teams/${id}`);
}

export function updateTeam(id, payload) {
    return api.patch(`teams/${id}`, payload);
}

export function deleteTeam(id) {
    return api.delete(`teams/${id}`);
}
