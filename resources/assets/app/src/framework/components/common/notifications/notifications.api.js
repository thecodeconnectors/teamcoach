import api from '@/framework/api';

export function getNotifications() {
    return api.get('notifications');
}

export function markNotificationAsRead(id) {
    return api.patch(`notifications/${id}/markasread`);
}
