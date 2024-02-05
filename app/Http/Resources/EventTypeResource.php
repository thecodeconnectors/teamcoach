<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return collect($this->resource)
            ->map(fn ($item, $index) => [
                'id' => $index,
                'name' => ucwords(str_replace('-', ' ', $index)),
            ])
            ->values()
            ->toArray();
    }
}
