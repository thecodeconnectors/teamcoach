<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Training
 */
class TrainingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'start_at' => $this->start_at->format('Y-m-d H:i:s'),
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
            'attendees' => AttendanceResource::collection($this->attendees),
            'players' => $this->team ? PlayerResource::collection($this->team->players) : [],
        ];
    }
}
