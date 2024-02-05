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

    private null|Model|Role $adminRole;

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createRoles();

        $this->setupGamePermissions();
        $this->setupTeamPermissions();
        $this->setupPlayerPermissions();
        $this->setupEventPermissions();
    }

    private function createRoles(): void
    {
        $this->userRole = Role::query()->firstOrCreate(['name' => RoleType::User->value], ['id' => 1, 'guard_name' => 'web']);
        $this->adminRole = Role::query()->firstOrCreate(['name' => RoleType::Admin->value], ['id' => 2, 'guard_name' => 'web']);
    }

    private function setupGamePermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'game.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'game.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'game.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'game.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'game.delete']);

        $this->userRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }
    
    private function setupTeamPermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'team.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'team.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'team.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'team.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'team.delete']);

        $this->userRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }

    private function setupPlayerPermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'player.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'player.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'player.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'player.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'player.delete']);

        $this->userRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }

    private function setupEventPermissions(): void
    {
        $viewAny = Permission::query()->firstOrCreate(['name' => 'event.viewAny']);
        $view = Permission::query()->firstOrCreate(['name' => 'event.view']);
        $create = Permission::query()->firstOrCreate(['name' => 'event.create']);
        $update = Permission::query()->firstOrCreate(['name' => 'event.update']);
        $delete = Permission::query()->firstOrCreate(['name' => 'event.delete']);

        $this->userRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
        $this->adminRole->givePermissionTo($viewAny, $view, $create, $update, $delete);
    }
}
