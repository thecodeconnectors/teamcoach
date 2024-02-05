<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Player
 */
class GamePlayerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'type' => $this->pivot->type,
            'position' => $this->pivot->position,
            'playtime' => round($this->playTimeForGame($this->pivot->game_id) / 60),
            'events' => EventResource::collection($this->eventsForGame($this->pivot->game_id)),
        ];
    }
}
