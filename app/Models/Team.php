<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Player> $players
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Team extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
