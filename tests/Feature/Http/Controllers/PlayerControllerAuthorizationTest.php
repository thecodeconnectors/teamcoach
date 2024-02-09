<?php

namespace Feature\Http\Controllers;

use App\Enums\Position;
use App\Models\Player;
use App\Modules\Users\Enums\RoleType;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class PlayerControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider roleDataProvider
     */
    public function testCreatePlayerAuthorization(RoleType $role, bool $allow): void
    {
        $user = $this->userWithRole($role);
        $team = TeamFactory::new()->for($user->account)->create();

        $payload = [
            'name' => 'Player A',
            'position' => Position::Attack->value,
            'team_id' => $team->id,
        ];

        $response = $this->actingAs($user)->post("api/players", $payload);

        $this->assertEquals($allow, $user->can('create', Player::class));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testCreatePlayerWithOtherTeamAuthorization(RoleType $role): void
    {
        $user = $this->userWithRole($role);
        $team = TeamFactory::new()->create();

        $payload = [
            'name' => 'Player A',
            'position' => Position::Attack->value,
            'team_id' => $team->id,
        ];

        $response = $this->actingAs($user)->post("api/players", $payload);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testShowPlayerAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $player = PlayerFactory::new()->for($user->account)->create();
        $otherAccountPlayer = PlayerFactory::new()->create();

        $response = $this->actingAs($user)->get("api/players/{$player->id}");
        $otherAccountResponse = $this->actingAs($user)->get("api/players/{$otherAccountPlayer->id}");

        $this->assertEquals($allow, $user->can('view', $player));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('view', $otherAccountPlayer));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testUpdatePlayerAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $player = PlayerFactory::new()->for($user->account)->create();
        $otherAccountPlayer = PlayerFactory::new()->create();

        $response = $this->actingAs($user)->patch("api/players/{$player->id}");
        $otherAccountResponse = $this->actingAs($user)->patch("api/players/{$otherAccountPlayer->id}");

        $this->assertEquals($allow, $user->can('update', $player));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('update', $otherAccountPlayer));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testDeletePlayerAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $player = PlayerFactory::new()->for($user->account)->create();
        $otherAccountPlayer = PlayerFactory::new()->create();

        $response = $this->actingAs($user)->delete("api/players/{$player->id}");
        $otherAccountResponse = $this->actingAs($user)->delete("api/players/{$otherAccountPlayer->id}");

        $this->assertEquals($allow, $user->can('delete', $player));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('delete', $otherAccountPlayer));
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
