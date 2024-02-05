import api from '@/framework/api';

export function getPositions() {
    return api.get('positions');
}
