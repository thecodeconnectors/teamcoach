<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\User;

class PlayerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('player.viewAny');
    }

    public function view(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('player.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('player.create');
    }

    public function update(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('player.update');
    }

    public function delete(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('player.delete');
    }
}
