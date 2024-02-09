<?php

namespace App\Models;

use App\Contract\BelongsToAccount;
use App\Enums\EventType;
use App\Repositories\Filters\Traits\FiltersRecords;
use App\Traits\HasAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $game_id
 * @property int|null $player_id
 * @property int|null $team_id
 * @property EventType $type
 * @property Game $game
 * @property Player|null $player
 * @property Team|null $team
 * @property Carbon $started_at
 * @property Carbon|null $finished_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property int $seconds
 * @property int $second_in_game
 * @property string $time_elapsed
 *
 */
class Event extends Model implements BelongsToAccount
{
    use HasAccount;
    use FiltersRecords;

    protected $guarded = [];

    protected $casts = [
        'type' => EventType::class,
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::saving(static function (Event $event) {
            $event->started_at = $event->started_at ?: now();
        });

        static::saved(static function (Event $event) {
            if ($event->type === EventType::Goal) {
                $event->game->increment('team_points');
            }
            if ($event->type === EventType::GoalLossed) {
                $event->game->increment('opponent_points');
            }
        });
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function getSecondsAttribute(): int
    {
        return $this->isDurationEventType()
            ? (int)$this->started_at?->diffInSeconds($this->finished_at ?: now())
            : 0;
    }

    public function getSecondInGameAttribute(): int
    {
        // deduct breaks
        return $this->game->started_at ? $this->started_at->diffInSeconds($this->game->started_at) : 0;
    }

    public function getTimeElapsedAttribute(): string
    {
        return sprintf('%d:%d', $this->second_in_game / 3600, round($this->second_in_game / 60) % 60);
    }

    public function isDurationEventType(): bool
    {
        return EventType::isDurationEventType($this->type);
    }
}
