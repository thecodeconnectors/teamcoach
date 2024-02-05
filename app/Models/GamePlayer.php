<?php

namespace App\Models;

use App\Enums\GamePlayerType;
use App\Enums\Position;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property int $team_id
 * @property GamePlayerType $type
 * @property Position $position
 * @property Player $player
 * @property Game $game
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class GamePlayer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'type' => GamePlayerType::class,
        'position' => Position::class,
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
