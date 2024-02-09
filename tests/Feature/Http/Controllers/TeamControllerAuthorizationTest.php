<?php

namespace Feature\Http\Controllers;

use App\Models\Team;
use App\Modules\Users\Enums\RoleType;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class TeamControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider roleDataProvider
     */
    public function testCreateTeamAuthorization(RoleType $role, bool $allow): void
    {
        $user = $this->userWithRole($role);

        $payload = [
            'name' => 'Team A',
        ];

        $response = $this->actingAs($user)->post("api/teams", $payload);

        $this->assertEquals($allow, $user->can('create', Team::class));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testUpdateTeamAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $player = TeamFactory::new()->for($user->account)->create();
        $otherAccountTeam = TeamFactory::new()->create();

        $response = $this->actingAs($user)->patch("api/teams/{$player->id}");
        $otherAccountResponse = $this->actingAs($user)->patch("api/teams/{$otherAccountTeam->id}");

        $this->assertEquals($allow, $user->can('update', $player));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('update', $otherAccountTeam));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testShowTeamAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $player = TeamFactory::new()->for($user->account)->create();
        $otherAccountTeam = TeamFactory::new()->create();

        $response = $this->actingAs($user)->get("api/teams/{$player->id}");
        $otherAccountResponse = $this->actingAs($user)->get("api/teams/{$otherAccountTeam->id}");

        $this->assertEquals($allow, $user->can('view', $player));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('view', $otherAccountTeam));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testDeleteTeamAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $player = TeamFactory::new()->for($user->account)->create();
        $otherAccountTeam = TeamFactory::new()->create();

        $response = $this->actingAs($user)->delete("api/teams/{$player->id}");
        $otherAccountResponse = $this->actingAs($user)->delete("api/teams/{$otherAccountTeam->id}");

        $this->assertEquals($allow, $user->can('delete', $player));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('delete', $otherAccountTeam));
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
}
