<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Setting
 */
class SettingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
