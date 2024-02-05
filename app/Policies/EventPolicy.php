<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('event.viewAny');
    }

    public function view(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('event.create');
    }

    public function update(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.update');
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.delete');
    }
}
