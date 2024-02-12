<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Permission;

/**
 * @mixin Permission
 */
class PermissionResource extends JsonResource
{
    public function toArray($request): string
    {
        return $this->name;
    }
}
