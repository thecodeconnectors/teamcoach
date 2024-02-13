<?php

namespace App\Traits;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Attendable
{
    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }
}
