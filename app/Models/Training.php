<?php

namespace App\Models;

use App\Contract\BelongsToAccount;
use App\Modules\Attendance\Traits\Attendable;
use App\Repositories\Filters\Traits\FiltersRecords;
use App\Traits\HasAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $team_id
 * @property Team $team
 * @property Carbon $start_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Training extends Model implements BelongsToAccount
{
    use SoftDeletes;
    use HasAccount;
    use FiltersRecords;
    use Attendable;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
