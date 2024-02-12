<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use App\Modules\Users\Enums\RoleType;

class TeamPolicy
{
    public function before(User $user): bool|null
    {
        return $user->hasRole(RoleType::Admin->value) ?: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('team.viewAny');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('team.view') && $user->owns($team);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('team.create') && $user->account->plan()->canCreateTeams();
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('team.update') && $user->owns($team);
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('team.delete') && $user->owns($team);
    }
}
