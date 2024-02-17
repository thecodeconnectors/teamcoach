<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'profile_picture' => $this->profile_picture,
            'email_verified_at' => $this->email_verified_at,
            'permissions' => PermissionResource::collection($this->getPlanCorrectedPermissions()),
            'roles' => RoleResource::collection($this->roles),
        ];
    }
}
