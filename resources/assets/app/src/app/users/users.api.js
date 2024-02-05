import api from '@/framework/api';

export function getUsers(params) {
    return api.get('users', {
        params: {
            page: 1,
            per_page: 15,
            sort: 'name:asc',
            ...params,
        },
    });
}

export function storeUser(payload) {
    return api.post('users', payload);
}

export function getUser(id) {
    return api.get(`users/${id}`);
}

export function updateUser(id, payload) {
    return api.patch(`users/${id}`, payload);
}

export function deleteUser(id) {
    return api.delete(`users/${id}`);
}
