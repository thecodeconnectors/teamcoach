<?php

namespace App\Modules\Attendance\Models;

use App\Modules\Attendance\Enums\AttendanceState;
use App\Repositories\Filters\Traits\FiltersRecords;
use App\Traits\HasAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $attendable_type
 * @property int $attendable_id
 * @property string $attendee_type
 * @property int $attendee_id
 * @property AttendanceState $state
 * @property Carbon $state_changed_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection<Model> $attendees
 * @property \Illuminate\Database\Eloquent\Collection<Model> $attendable
 */
class Attendance extends Model
{
    use HasAccount;
    use FiltersRecords;

    protected $guarded = [];

    protected $casts = [
        'state' => AttendanceState::class,
        'state_changed_at' => 'datetime',
    ];

    public function attendable(): MorphTo
    {
        return $this->morphTo();
    }

    public function attendee(): MorphTo
    {
        return $this->morphTo();
    }
}
