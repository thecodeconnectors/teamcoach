import api from '@/framework/api';

export function getAttendanceStates() {
    return api.get('attendance-states');
}

export function updateAttendanceState(endpoint, state) {
    return api.patch(endpoint, {state});
}
