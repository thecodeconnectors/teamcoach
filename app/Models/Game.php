<?php

namespace App\Models;

use App\Enums\EventType;
use App\Enums\GamePlayerType;
use App\Enums\Position;
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
 * @property null|string $url_secret
 * @property int $team_points
 * @property int $opponent_points
 * @property Team $team
 * @property Team $opponent
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Player> $players
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Event> $events
 * @property Carbon $start_at
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property ?string $public_url
 * @property int $seconds_elapsed
 * @property string $time_elapsed
 */
class Game extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::saving(static function (Game $game) {
            $game->team_id = $game->team_id ?: Team::query()->first()->id;
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
        return $this->belongsToMany(Player::class)->withPivot(['game_id', 'type', 'position']);
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
        return $this->hasMany(Event::class);
    }

    public function getSecondsElapsedAttribute(): int
    {
        if ($this->started_at) {
            return $this->started_at?->diffInSeconds($this->finished_at ?: now())
                - $this->breaks()->sum('seconds');
        }

        return 0;
    }

    public function getTimeElapsedAttribute(): string
    {
        $hours = str_pad(intdiv($this->seconds_elapsed, 3600), 2, '0', STR_PAD_LEFT);
        $minutes = str_pad(intdiv($this->seconds_elapsed % 3600, 60), 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($this->seconds_elapsed % 60, 2, '0', STR_PAD_LEFT);

        return $hours !== '00' ? "{$hours}:{$minutes}:{$seconds}" : "{$minutes}:{$seconds}";
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

    public function isPublic(): bool
    {
        return $this->url_secret !== null;
    }

    /**
     * Returns the playtime in seconds.
     *
     * @return int
     */
    public function playTime(): int
    {
        $playTime = (int)$this->started_at?->diffInSeconds($this->finished_at);
        $breakTime = $this->breaks()->sum('seconds');

        return $playTime - $breakTime;
    }

    public function addPlayer(Player $player, Carbon|string|null $dateTime = null, ?Position $position = null, ?GamePlayerType $type = GamePlayerType::Playing): static
    {
        if ($this->isPlaying()) {
            $this->startPlayTimer($player, $dateTime ?: now());
        }

        return $this->updatePlayer($player, $position, $type);
    }

    public function addSubstitute(Player $player, Carbon|string|null $dateTime = null, ?Position $position = null): static
    {
        if ($this->isPlaying()) {
            $dateTime = $dateTime ?: now();
            $this->finishPlayTimer($player, $dateTime);
            $this->saveSubstituteEvent($player, $dateTime);
        }

        return $this->updatePlayer($player, $position, GamePlayerType::Substitute);
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
            $this->addSubstitute($playerB, $dateTime);
        } elseif ($this->playerIsSubstitute($playerB) && $this->playerIsPlaying($playerA)) {
            $this->addPlayer($playerB, $dateTime);
            $this->addSubstitute($playerA, $dateTime);
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

    private function saveSubstituteEvent(Player $player, Carbon|string $startedAt): void
    {
        $this->events()
            ->create([
                'type' => EventType::Substituted->value,
                'player_id' => $player->id,
                'team_id' => $player->team_id,
                'started_at' => $startedAt,
            ]);
    }

    public function makePublic(): static
    {
        $this->update(['url_secret' => Str::uuid() . '-' . Str::uuid()]);

        return $this;
    }

    public function makePrivate(): static
    {
        $this->update(['url_secret' => null]);

        return $this;
    }
}
