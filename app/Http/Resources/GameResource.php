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

            'is_away_game' => $this->is_away_game,
            'parts' => $this->parts,
            'part_duration' => $this->part_duration,
            'break_duration' => $this->break_duration,
            
            'public_url' => $this->public_url,
            'start_at' => $this->start_at?->format('Y-m-d H:i:s'),
            'started_at' => $this->started_at?->format('Y-m-d H:i:s'),
            'finished_at' => $this->finished_at?->format('Y-m-d H:i:s'),
            'players' => GamePlayerResource::collection($this->players),
        ];
    }
}
