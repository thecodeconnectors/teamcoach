<?php

namespace App\Models;

use App\Contract\BelongsToAccount;
use App\Enums\EventType;
use App\Enums\GamePlayerType;
use App\Enums\Position;
use App\Modules\Attendance\Traits\Attendable;
use App\Repositories\Filters\Traits\FiltersRecords;
use App\Traits\HasAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $team_id
 * @property int $opponent_id
 * @property bool $is_public
 * @property bool $is_away_game
 * @property bool $is_home_game
 * @property null|string $url_secret
 * @property int $parts
 * @property int $part_duration
 * @property int $break_duration
 * @property int $team_points
 * @property int $opponent_points
 * @property Team $team
 * @property Team $opponent
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Player> $players
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Event> $events
 * @property Carbon $start_at
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 * @property Carbon $end_time
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property ?string $public_url
 *
 * @property int $total_seconds
 * @property int $break_seconds
 * @property int $played_seconds
 *
 * @property string $total_time
 * @property string $played_time
 * @property string $break_time
 */
class Game extends Model implements BelongsToAccount
{
    use SoftDeletes;
    use HasAccount;
    use FiltersRecords;
    use Attendable;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'is_public' => 'bool',
        'is_away_game' => 'bool',
        'is_home_game' => 'bool',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::saving(static function (Game $game) {
            $game->is_public = $game->is_public ?? false;
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function opponent(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'opponent_id');
    }

    public function players(): BelongsToMany
    {
        return $this
            ->belongsToMany(Player::class)
            ->withPivot(['game_id', 'type', 'position'])
            ->orderBy('name');
    }

    public function substitutes(): Collection
    {
        return $this->players->where('pivot.type', GamePlayerType::Substitute->value);
    }

    public function playing(): Collection
    {
        return $this->players->where('pivot.type', GamePlayerType::Playing->value);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->latest('started_at');
    }

    public function getIsHomeGameAttribute(): bool
    {
        return !$this->is_away_game;
    }

    public function getEndTimeAttribute(): Carbon
    {
        return $this->finished_at ?: now();
    }

    public function getTotalSecondsAttribute(): int
    {
        return $this->started_at ? $this->end_time->diffInSeconds($this->started_at) : 0;
    }

    public function getBreakSecondsAttribute(): int
    {
        return $this->breaks()->sum('seconds');
    }

    public function getPlayedSecondsAttribute(): int
    {
        return $this->total_seconds - $this->break_seconds;
    }

    public function getTotalTimeAttribute(): string
    {
        return secondsToTime($this->total_seconds);
    }

    public function getBreakTimeAttribute(): string
    {
        return secondsToTime($this->break_seconds);
    }

    public function getPlayedTimeAttribute(): string
    {
        return secondsToTime($this->played_seconds);
    }

    public function getPublicUrlAttribute(): ?string
    {
        return $this->url_secret ? frontendUrl("games/$this->url_secret") : null;
    }

    public function pausedEvent(): null|Event|Model
    {
        return $this->events
            ->where('type', EventType::GameBreak)
            ->whereNotNull('started_at')
            ->whereNull('finished_at')
            ->first();
    }

    public function breaks(): Collection
    {
        return $this->events
            ->where('type', EventType::GameBreak)
            ->whereNotNull('started_at');
    }

    public function eventsWithoutPlayTimes(): Collection
    {
        return $this->events
            ->where('type', '!=', EventType::PlayTime)
            ->whereNotNull('started_at');
    }

    public function start(Carbon|string $dateTime): static
    {
        $this->update(['started_at' => $dateTime]);

        $this->startPlayerTimers($dateTime);

        $this->load('events');

        return $this;
    }

    public function finish(Carbon|string $dateTime): static
    {
        $this->update(['finished_at' => $dateTime]);

        if ($pausedEvent = $this->pausedEvent()) {
            $pausedEvent->update(['finished_at' => $dateTime]);
        }

        $this->finishPlayerTimers($dateTime);

        $this->load('events');

        return $this;
    }

    public function pause(Carbon|string $dateTime): static
    {
        $this->events()->create([
            'type' => EventType::GameBreak->value,
            'account_id' => $this->account_id,
            'started_at' => $dateTime,
        ]);

        $this->finishPlayerTimers($dateTime);

        $this->load('events');

        return $this;
    }

    public function resume(Carbon|string $dateTime): static
    {
        if ($pausedEvent = $this->pausedEvent()) {
            $pausedEvent->update(['finished_at' => $dateTime]);
        }

        $this->startPlayerTimers($dateTime);

        $this->load('events');

        return $this;
    }

    public function isPlaying(): bool
    {
        return $this->isStarted() && !$this->isFinished();
    }

    public function isStarted(): bool
    {
        return (bool)$this->started_at;
    }

    public function isFinished(): bool
    {
        return (bool)$this->finished_at;
    }

    public function isPaused(): bool
    {
        return !is_null($this->pausedEvent());
    }

    /**
     * Returns the playtime in seconds, with break time deducted
     *
     * @return int
     */
    public function playTime(): int
    {
        return (int)$this->started_at?->diffInSeconds($this->finished_at) - $this->break_seconds;
    }

    public function addTeamPlayers(): static
    {
        foreach ($this->team->players()->get() as $player) {
            $this->addPlayer($player);
        }

        return $this;
    }

    public function addPlayer(Player $player, Carbon|string|null $dateTime = null, ?Position $position = null, ?GamePlayerType $type = GamePlayerType::Playing): static
    {
        if ($this->isPlaying() && !$this->isPaused()) {
            // Don't start a timer when the game is paused, or before the game is started,
            // as this will result in duplicate time registrations:
            // When we start or resume a game, we will start new timers for every player.
            $this->startPlayTimer($player, $dateTime ?: now());
        }

        return $this->updatePlayer($player, $position, $type);
    }

    public function addSubstitute(Player $playerA, Carbon|string|null $dateTime = null, ?Player $playerB = null): static
    {
        if ($this->isPlaying()) {
            // We can always finish player timers even during paused games,
            // as during breaks there are no running timers.
            // We do always want to register the substitute event,
            // as that is merely a registration of the event without end time.
            $dateTime = $dateTime ?: now();
            $this->finishPlayTimer($playerA, $dateTime);
            $this->saveSubstituteEvent($playerA, $dateTime, $playerB);
        }

        return $this->updatePlayer($playerA, null, GamePlayerType::Substitute);
    }

    public function updatePlayer(Player|int $player, ?Position $position = null, ?GamePlayerType $type = GamePlayerType::Playing): static
    {
        $playerId = $player instanceof Player ? $player->id : $player;

        if (!$position && $player instanceof Player) {
            $position = $player->position;
        }

        $this->players()->syncWithoutDetaching([
            $playerId => [
                'type' => $type ?: GamePlayerType::Playing,
                'position' => $position ?: $player->position,
            ],
        ]);

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        $this->players()->detach($player);

        $this->events()->where('player_id', $player->id)->delete();

        return $this;
    }

    public function substitutePlayer(Player $playerA, Player $playerB, Carbon|string $dateTime): static
    {
        if ($this->playerIsSubstitute($playerA) && $this->playerIsPlaying($playerB)) {
            $this->addPlayer($playerA, $dateTime);
            $this->addSubstitute($playerB, $dateTime, $playerA);
        } elseif ($this->playerIsSubstitute($playerB) && $this->playerIsPlaying($playerA)) {
            $this->addPlayer($playerB, $dateTime);
            $this->addSubstitute($playerA, $dateTime, $playerB);
        }

        $this->load(['players', 'events']);

        return $this;
    }

    public function playerIsSubstitute(Player $player): bool
    {
        return $this->substitutes()->contains($player);
    }

    public function playerIsPlaying(Player $player): bool
    {
        return $this->playing()->contains($player);
    }

    private function startPlayerTimers(Carbon|string $dateTime): static
    {
        foreach ($this->playing() as $player) {
            $this->startPlayTimer($player, $dateTime);
        }

        return $this;
    }

    private function finishPlayerTimers(Carbon|string $dateTime): static
    {
        foreach ($this->playing() as $player) {
            $this->finishPlayTimer($player, $dateTime);
        }

        return $this;
    }

    private function startPlayTimer(Player $player, Carbon|string $startedAt, Carbon|string|null $finishedAt = null): void
    {
        $this->events()
            ->create([
                'type' => EventType::PlayTime->value,
                'account_id' => $this->account_id,
                'player_id' => $player->id,
                'team_id' => $player->team_id,
                'started_at' => $startedAt,
                'finished_at' => $finishedAt,
            ]);
    }

    private function finishPlayTimer(Player $player, Carbon|string $dateTime): void
    {
        $this->events()
            ->where('type', EventType::PlayTime)
            ->where('player_id', $player->id)
            ->whereNotNull('started_at')
            ->whereNull('finished_at')
            ->update([
                'finished_at' => $dateTime,
            ]);
    }

    private function saveSubstituteEvent(Player $playerA, Carbon|string $startedAt, ?Player $playerB = null): void
    {
        $this->events()
            ->create([
                'type' => EventType::Substituted->value,
                'account_id' => $this->account_id,
                'player_id' => $playerA->id,
                'other_player_id' => $playerB?->id,
                'team_id' => $playerA->team_id,
                'started_at' => $startedAt,
            ]);
    }

    public function makePublic(): static
    {
        $this->update([
            'is_public' => true,
            'url_secret' => Str::uuid() . '-' . Str::uuid(),
        ]);

        return $this;
    }

    public function makePrivate(): static
    {
        $this->update([
            'is_public' => false,
        ]);

        return $this;
    }
}
