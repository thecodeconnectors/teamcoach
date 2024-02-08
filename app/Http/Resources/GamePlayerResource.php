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
            'profile_picture' => $this->profile_picture,
            'type' => $this->pivot->type,
            'position' => $this->pivot->position,
            'playtime' => $this->playTimeForGame($this->pivot->game_id),
            'events' => EventResource::collection($this->eventsForGame($this->pivot->game_id)),
            'goals' => $this->goalsForGame($this->pivot->game_id),
            'cards' => $this->cardsForGame($this->pivot->game_id),
        ];
    }
}
