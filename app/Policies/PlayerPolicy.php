<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\User;
use App\Modules\Users\Enums\RoleType;

class PlayerPolicy
{
    public function before(User $user): bool|null
    {
        return $user->hasRole(RoleType::Admin->value) ?: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('player.viewAny');
    }

    public function view(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('player.view') && $user->owns($player);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('player.create');
    }

    public function update(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('player.update') && $user->owns($player);
    }

    public function delete(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('player.delete') && $user->owns($player);
    }
}
