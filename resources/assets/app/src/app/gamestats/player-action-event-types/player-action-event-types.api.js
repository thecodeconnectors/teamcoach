import api from '@/framework/api';

export function getPlayerActionEventTypes() {
    return api.get('player-action-event-types');
}
