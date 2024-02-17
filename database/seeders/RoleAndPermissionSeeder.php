<?php

namespace Database\Seeders;

use App\Modules\Users\Enums\RoleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    private null|Model|Role $userRole;

    private null|Model|Role $ownerRole;

    private null|Model|Role $adminRole;

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createRoles();

        $this->setupUserPermissions();
        $this->setupGamePermissions();
        $this->setupTeamPermissions();
        $this->setupPlayerPermissions();
        $this->setupEventPermissions();
        $this->setupTrainingPermissions();
    }

    private function createRoles(): void
    {
        $this->userRole = Role::query()->firstOrCreate(['name' => RoleType::User->value], ['id' => 1, 'guard_name' => 'web']);
        $this->ownerRole = Role::query()->firstOrCreate(['name' => RoleType::Owner->value], ['id' => 2, 'guard_name' => 'web']);
        $this->adminRole = Role::query()->firstOrCreate(['name' => RoleType::Admin->value], ['id' => 3, 'guard_name' => 'web']);
    }

    private function setupUserPermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'user.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'user.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'user.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'user.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'user.delete']);

        $this->ownerRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }

    private function setupGamePermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'game.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'game.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'game.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'game.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'game.delete']);

        $this->ownerRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }

    private function setupTeamPermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'team.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'team.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'team.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'team.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'team.delete']);

        $this->ownerRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }

    private function setupPlayerPermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'player.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'player.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'player.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'player.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'player.delete']);

        $this->ownerRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }

    private function setupEventPermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'event.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'event.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'event.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'event.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'event.delete']);

        $this->ownerRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }

    private function setupTrainingPermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'training.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'training.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'training.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'training.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'training.delete']);

        $this->ownerRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }
    
}
