<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Event
 */
class EventResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'player_id' => $this->player_id,
            'team_id' => $this->team_id,
            'type' => $this->type->value,
            'name' => ucwords(str_replace('-', ' ', $this->type->value)),
            'player_name' => $this->player?->name,
            'seconds' => $this->seconds,
            'second_in_game' => $this->second_in_game,
            'time_elapsed' => $this->time_elapsed,
            'started_at' => $this->started_at->format('Y-m-d H:i:s'),
            'finished_at' => $this->finished_at?->format('Y-m-d H:i:s'),
        ];
    }
}
