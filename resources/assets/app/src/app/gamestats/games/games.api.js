import api from '@/framework/api';

export function getGames(params) {
    return api.get('games', {
        params: {
            page: 1,
            per_page: 15,
            sort: 'name:asc',
            ...params,
        },
    });
}

export function storeGame(payload) {
    return api.post('games', payload);
}

export function getGame(id) {
    return api.get(`games/${id}`);
}

export function updateGame(id, payload) {
    return api.patch(`games/${id}`, payload);
}

export function deleteGame(id) {
    return api.delete(`games/${id}`);
}

export function getGamePlayerTypes() {
    return api.get('game-player-types');
}

export function getGamePlay(id) {
    return api.get(`games/${id}/play`);
}

export function startGame(id) {
    return api.post(`games/${id}/start`);
}

export function pauseGame(id) {
    return api.post(`games/${id}/pause`);
}

export function resumeGame(id) {
    return api.post(`games/${id}/resume`);
}

export function finishGame(id) {
    return api.post(`games/${id}/finish`);
}

export function swtichPlayers(gameId, playerId, substituteId) {
    return api.post(`games/${gameId}/switch-player`, {
        player_id: playerId,
        substitute_id: substituteId,
    });
}

export function storePlayerEvent(gameId, playerId, event) {
    return api.post(`games/${gameId}/event`, {
        player_id: playerId,
        event: event,
    });
}

export function storeTeamEvent(gameId, teamId, event) {
    return api.post(`games/${gameId}/event`, {
        team_id: teamId,
        event: event,
    });
}
