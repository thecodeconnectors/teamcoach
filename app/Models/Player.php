<?php

namespace App\Models;

use App\Contract\BelongsToAccount;
use App\Enums\EventType;
use App\Enums\Position;
use App\Repositories\Filters\Traits\FiltersRecords;
use App\Traits\HasAccount;
use App\Traits\HasProfilePicture;
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
class Player extends Model implements BelongsToAccount
{
    use SoftDeletes;
    use HasProfilePicture;
    use FiltersRecords;
    use HasAccount;

    protected $guarded = [];

    protected $casts = [
        'position' => Position::class,
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function playtimeForGame(int|Game $game): int
    {
        return $this->playtimeEventsForGame($game)->sum('seconds');
    }

    public function playtimeEventsForGame(int|Game $game): Collection
    {
        return $this->playerEventsForGame($game)->filter(fn (Event $event) => $event->type === EventType::PlayTime);
    }

    public function breakEventsForGame(int|Game $game): Collection
    {
        return $this->eventsForGame($game)->filter(fn (Event $event) => $event->type === EventType::GameBreak);
    }

    public function playerEventsForGame(int|Game $game): Collection
    {
        return $this->eventsForGame($game)->filter(fn (Event $event) => $event->player_id === $this->id);
    }

    private function eventsForGame(int|Game $game): Collection
    {
        $gameId = $game instanceof Game ? $game->id : $game;

        return Cache::store('array')->rememberForever($this->id . $gameId . 'eventsForGame', function () use ($gameId) {
            return Event::query()
                ->with('game')
                ->where('game_id', $gameId)
                ->get();
        });
    }
}
