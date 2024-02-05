<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('game.viewAny');
    }

    public function view(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('game.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('game.create');
    }

    public function update(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('game.update');
    }

    public function delete(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('game.delete');
    }
}
