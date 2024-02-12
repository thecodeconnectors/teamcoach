<?php

namespace Feature\Http\Controllers;

use App\Models\User;
use App\Modules\Users\Enums\RoleType;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class UserControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('plans.free.users', 2);
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testCreateUserAuthorization(RoleType $role, bool $allow): void
    {
        $user = $this->userWithRole($role);

        $payload = [
            'name' => 'User A',
            'email' => 'test@test.nl',
        ];

        $response = $this->actingAs($user)->post("api/users", $payload);

        $this->assertEquals($allow, $user->can('create', User::class));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testShowUserAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $acting = $this->userWithRole($role);
        $user = UserFactory::new()->for($acting->account)->create();
        $otherAccountUser = UserFactory::new()->create();

        $response = $this->actingAs($acting)->get("api/users/{$user->id}");
        $otherAccountResponse = $this->actingAs($acting)->get("api/users/{$otherAccountUser->id}");

        $this->assertEquals($allow, $acting->can('view', $user));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $acting->can('view', $otherAccountUser));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testUpdateUserAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $acting = $this->userWithRole($role);
        $user = UserFactory::new()->for($acting->account)->create();
        $otherAccountUser = UserFactory::new()->create();

        $response = $this->actingAs($acting)->patch("api/users/{$user->id}");
        $otherAccountResponse = $this->actingAs($acting)->patch("api/users/{$otherAccountUser->id}");

        $this->assertEquals($allow, $acting->can('update', $user));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $acting->can('update', $otherAccountUser));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testDeleteUserAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $acting = $this->userWithRole($role);
        $user = UserFactory::new()->for($acting->account)->create();
        $otherAccountUser = UserFactory::new()->create();

        $response = $this->actingAs($acting)->delete("api/users/{$user->id}");
        $otherAccountResponse = $this->actingAs($acting)->delete("api/users/{$otherAccountUser->id}");

        $this->assertEquals($allow, $acting->can('delete', $user));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $acting->can('delete', $otherAccountUser));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    public static function roleDataProvider(): array
    {
        return [
            'user' => [
                'role' => RoleType::User,
                'allow' => false,
                'allowOtherAccount' => false,
            ],
            'owner' => [
                'role' => RoleType::Owner,
                'allow' => true,
                'allowOtherAccount' => false,
            ],
            'admin' => [
                'role' => RoleType::Admin,
                'allow' => true,
                'allowOtherAccount' => true,
            ],
        ];
    }

    public function testCreateTeamOutsidePlanAuthorization(): void
    {
        Config::set('plans.free.users', 1);

        $user = $this->userWithRole(RoleType::Owner);

        UserFactory::new()->create();

        $response = $this->actingAs($user)->post('api/users');

        $this->assertFalse($user->can('create', User::class));
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
