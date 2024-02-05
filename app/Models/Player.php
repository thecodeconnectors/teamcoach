<?php

namespace App\Models;

use App\Enums\Position;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property int $team_id
 * @property string $name
 * @property Position $position
 * @property Team $team
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property $pivot
 */
class Player extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'position' => Position::class,
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::saving(static function (Player $player) {
            $player->team_id = $player->team_id ?: Team::query()->first()->id;
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function playTimeForGame(int|Game $game): int
    {
        return $this
            ->eventsForGame($game)
            ->sum('seconds');
    }

    public function eventsForGame(int|Game $game): Collection
    {
        $gameId = $game instanceof Game ? $game->id : $game;

        return Cache::store('array')->rememberForever($this->id . $gameId . 'eventsForGame', function () use ($gameId) {
            return Event::query()
                ->with('game')
                ->where('game_id', $gameId)
                ->where('player_id', $this->id)
                ->get();
        });
    }
}
