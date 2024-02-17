<?php

namespace App\Policies;

use App\Models\Training;
use App\Models\User;
use App\Modules\Users\Enums\RoleType;

class TrainingPolicy
{
    public function before(User $user): bool|null
    {
        // todo: make god permission
        return $user->hasRole(RoleType::Admin->value) ?: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('training.viewAny');
    }

    public function view(User $user, Training $training): bool
    {
        return $user->hasPermissionTo('training.view') && $user->owns($training);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('training.create');
    }

    public function update(User $user, Training $training): bool
    {
        return $user->hasPermissionTo('training.update') && $user->owns($training);
    }

    public function delete(User $user, Training $training): bool
    {
        return $user->hasPermissionTo('training.delete') && $user->owns($training);
    }
}
