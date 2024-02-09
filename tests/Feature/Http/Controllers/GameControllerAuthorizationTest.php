<?php

namespace Feature\Http\Controllers;

use App\Models\Game;
use App\Modules\Users\Enums\RoleType;
use Database\Factories\GameFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class GameControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider roleDataProvider
     */
    public function testCreateGameAuthorization(RoleType $role, bool $allow): void
    {
        $user = $this->userWithRole($role);

        $payload = [];

        $response = $this->actingAs($user)->post("api/games", $payload);

        $this->assertEquals($allow, $user->can('create', Game::class));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testShowGameAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $game = GameFactory::new()->for($user->account)->create();
        $otherAccountGame = GameFactory::new()->create();

        $response = $this->actingAs($user)->get("api/games/{$game->id}");
        $otherAccountResponse = $this->actingAs($user)->get("api/games/{$otherAccountGame->id}");

        $this->assertEquals($allow, $user->can('view', $game));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('view', $otherAccountGame));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testUpdateGameAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $game = GameFactory::new()->for($user->account)->create();
        $otherAccountGame = GameFactory::new()->create();

        $response = $this->actingAs($user)->patch("api/games/{$game->id}");
        $otherAccountResponse = $this->actingAs($user)->patch("api/games/{$otherAccountGame->id}");

        $this->assertEquals($allow, $user->can('update', $game));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('update', $otherAccountGame));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testDeleteGameAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $player = GameFactory::new()->for($user->account)->create();
        $otherAccountGame = GameFactory::new()->create();

        $response = $this->actingAs($user)->delete("api/games/{$player->id}");
        $otherAccountResponse = $this->actingAs($user)->delete("api/games/{$otherAccountGame->id}");

        $this->assertEquals($allow, $user->can('delete', $player));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('delete', $otherAccountGame));
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
