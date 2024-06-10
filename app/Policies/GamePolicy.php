<?php

namespace App\Policies;

use App\Models\Game;
use App\Modules\Users\Enums\RoleType;
use App\Modules\Users\Models\User;

class GamePolicy
{
    public function before(User $user): bool|null
    {
        // todo: make god permission
        return $user->hasRole(RoleType::Admin->value) ?: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('game.viewAny');
    }

    public function view(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('game.view') && $user->owns($game);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('game.create');
    }

    public function update(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('game.update') && $user->owns($game);
    }

    public function delete(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('game.delete') && $user->owns($game);
    }
}
