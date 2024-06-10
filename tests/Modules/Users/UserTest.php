<?php

namespace Modules\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Tests\WithUsers;

class UserTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItRemovesPermissionsWhenPlanLimitsAreExceeded(): void
    {
        Config::set('plans.free.users', 2);

        $user = $this->owner();

        $allPermissions = Permission::query()->orderBy('name')->pluck('name')->toArray();

        $this->assertEquals($allPermissions, $user->getPlanCorrectedPermissions()->sortBy('name')->pluck('name')->toArray());

        Config::set('plans.free.users', 1);
        Config::set('plans.free.teams', 0);

        $this->assertNotContains('user.create', $user->getPlanCorrectedPermissions()->pluck('name')->toArray());
        $this->assertNotContains('team.create', $user->getPlanCorrectedPermissions()->pluck('name')->toArray());
    }
}
