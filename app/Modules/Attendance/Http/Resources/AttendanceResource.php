<?php

namespace App\Modules\Attendance\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Modules\Attendance\Models\Attendance
 */
class AttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'state' => $this->state,
            'state_changed_at' => $this->state_changed_at,
        ];
    }
}
