<?php

namespace App\Modules\Attendance\Traits;

use App\Modules\Attendance\Models\Attendance;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property \Illuminate\Database\Eloquent\Collection<Attendance> $attendances
 */
trait Attendee
{
    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendee');
    }
}
