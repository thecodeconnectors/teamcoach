import api from '@/framework/api';

export function getTrainings(params) {
    return api.get('training', {
        params: {
            page: 1,
            per_page: 15,
            sort: 'start_at:asc',
            ...params,
        },
    });
}

export function storeTraining(payload) {
    return api.post('training', payload);
}

export function getTraining(id) {
    return api.get(`training/${id}`);
}

export function updateTraining(id, payload) {
    return api.patch(`training/${id}`, payload);
}

export function deleteTraining(id) {
    return api.delete(`training/${id}`);
}

export function storeTrainingAttendance(trainingId, ids) {
    return api.post(`training/${trainingId}/players`, {ids});
}
