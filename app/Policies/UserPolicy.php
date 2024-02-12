<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Users\Enums\RoleType;

class UserPolicy
{
    public function before(User $user): bool|null
    {
        return $user->hasRole(RoleType::Admin->value) ?: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('user.viewAny');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.view') && $user->owns($model);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('user.create') && $user->account->plan()->canCreateUsers();
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.update') && $user->owns($model);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.delete') && $user->owns($model);
    }
}
