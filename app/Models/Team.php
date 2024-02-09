<?php

namespace App\Models;

use App\Contract\BelongsToAccount;
use App\Repositories\Filters\Traits\FiltersRecords;
use App\Traits\HasAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property boolean $is_opponent
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Player> $players
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Team extends Model implements BelongsToAccount
{
    use SoftDeletes;
    use FiltersRecords;
    use HasAccount;

    protected $guarded = [];

    protected $casts = [
        'is_opponent' => 'bool',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function createPlayer(array $attributes): Player|false
    {
        $player = new Player($attributes);
        $player->account_id = $this->account_id;

        return $this->players()->save($player);
    }
}
