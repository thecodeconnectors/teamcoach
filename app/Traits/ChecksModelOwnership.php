<?php

namespace App\Traits;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use App\Models\Team;

trait ChecksModelOwnership
{
    public function gameBelongsToAccount(string|int $accountId, string|int|null $gameId = null): bool
    {
        if ($gameId) {
            return (bool)Game::query()->where('account_id', $accountId)->where('id', $gameId)->count();
        }

        return true;
    }

    public function teamBelongsToAccount(string|int $accountId, string|int|null $teamId = null): bool
    {
        if ($teamId) {
            return (bool)Team::query()->where('account_id', $accountId)->where('id', $teamId)->count();
        }

        return true;
    }

    public function playerBelongsToAccount(string|int $accountId, string|int|null $playerId = null): bool
    {
        if ($playerId) {
            return (bool)Player::query()->where('account_id', $accountId)->where('id', $playerId)->count();
        }

        return true;
    }

    public function playerBelongsToGame(string|int|null $gameId = null, string|int|null $playerId = null): bool
    {
        if ($gameId && $playerId) {
            return (bool)GamePlayer::query()->where('game_id', $gameId)->where('player_id', $playerId)->count();
        }

        return true;
    }

}
