<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Modules\Users\Enums\RoleType;

class EventPolicy
{
    public function before(User $user): bool|null
    {
        // todo: make god permission
        return $user->hasRole(RoleType::Admin->value) ?: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('event.viewAny');
    }

    public function view(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.view') && $user->owns($event);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('event.create');
    }

    public function update(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.update') && $user->owns($event);
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.delete') && $user->owns($event);
    }
}
