<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Game
 */
class GamePlayResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'team_name' => $this->team->name,
            'opponent_id' => $this->opponent_id,
            'opponent_name' => $this->opponent->name,
            'team_points' => $this->team_points,
            'opponent_points' => $this->opponent_points,
            'url_secret' => $this->url_secret,
            'public_url' => $this->public_url,
            'start_at' => $this->start_at?->format('Y-m-d H:i:s'),
            'started_at' => $this->started_at?->format('Y-m-d H:i:s'),
            'finished_at' => $this->finished_at?->format('Y-m-d H:i:s'),
            'seconds_elapsed' => $this->seconds_elapsed,
            'time_elapsed' => $this->time_elapsed,
            'is_playing' => $this->isPlaying(),
            'is_started' => $this->isStarted(),
            'is_finished' => $this->isFinished(),
            'is_paused' => $this->isPaused(),
            'is_public' => $this->is_public,
            'playing' => GamePlayerResource::collection($this->playing()),
            'substitutes' => GamePlayerResource::collection($this->substitutes()),
            'events' => EventResource::collection($this->eventsWithoutPlayTimes()),
            'breaks' => EventResource::collection($this->breaks()),
        ];
    }
}
