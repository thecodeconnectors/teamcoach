<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Modules\Attendance\Models\Attendance
 */
class AttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'attendee_type' => $this->attendee_type,
            'attendee_id' => $this->attendee_id,
            'name' => $this->attendee?->name,
            'profile_picture' => $this->attendee?->profile_picture,
            'state' => $this->state,
            'state_changed_at' => $this->state_changed_at,
        ];
    }
}
