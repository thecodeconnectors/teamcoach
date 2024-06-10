<?php

namespace App\Models;

use App\Modules\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $plan
 * @property Collection<User> $users
 * @property Collection<Team> $teams
 * @property Collection<Player> $players
 * @property Collection<Game> $games
 * @property Collection<Event> $events
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Account extends Model
{
    protected $table = 'users_accounts';

    protected $guarded = [];

    protected static function booted(): void
    {
        parent::booted();

        static::deleting(function (Account $account) {
            $account->users()->each(fn (User $user) => $user->delete());
            $account->players()->each(fn (Player $player) => $player->delete());
            $account->games()->each(fn (Game $game) => $game->delete());
            $account->teams()->each(fn (Team $team) => $team->delete());
            $account->events()->each(fn (Event $event) => $event->delete());
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function plan(): Plan
    {
        return new Plan($this->plan);
    }
}
