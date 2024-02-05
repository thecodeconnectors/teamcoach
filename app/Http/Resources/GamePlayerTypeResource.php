<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GamePlayerTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return collect($this->resource)
            ->map(fn ($item, $index) => [
                'id' => $index,
                'name' => $item,
            ])
            ->values()
            ->toArray();
    }
}
