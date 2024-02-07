<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Game
 */
class GameResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'opponent_id' => $this->opponent_id,
            'opponent_name' => $this->opponent->name,
            'public_url' => $this->public_url,
            'start_at' => $this->start_at?->format('Y-m-d H:i:s'),
            'started_at' => $this->started_at?->format('Y-m-d H:i:s'),
            'finished_at' => $this->finished_at?->format('Y-m-d H:i:s'),
            'players' => GamePlayerResource::collection($this->players),
        ];
    }
}
