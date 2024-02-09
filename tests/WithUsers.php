<?php

namespace Tests;

use App\Models\User;
use App\Modules\Users\Enums\RoleType;
use Database\Factories\UserFactory;
use Database\Seeders\RoleAndPermissionSeeder;

trait WithUsers
{
    public function seedRoles(): void
    {
        (new RoleAndPermissionSeeder())->run();
    }

    public function admin(array $attributes = []): User
    {
        return UserFactory::new()->create($attributes)->assignRole(RoleType::Admin->value);
    }

    public function owner(array $attributes = []): User
    {
        return UserFactory::new()->create($attributes)->assignRole(RoleType::Owner->value);
    }

    public function user(array $attributes = []): User
    {
        return UserFactory::new()->create($attributes)->assignRole(RoleType::User->value);
    }

    public function userWithRole(RoleType $role, array $attributes = []): User
    {
        return UserFactory::new()->create($attributes)->assignRole($role->value);
    }
}
