<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('team.viewAny');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('team.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('team.create');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('team.update');
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('team.delete');
    }
}
