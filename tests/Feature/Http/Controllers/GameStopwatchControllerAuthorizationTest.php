<?php

namespace Feature\Http\Controllers;

use App\Modules\Users\Enums\RoleType;
use Database\Factories\GameFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class GameStopwatchControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider roleDataProvider
     */
    public function testStartGameAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $game = GameFactory::new()->for($user->account)->create();
        $otherAccountGame = GameFactory::new()->create();

        $payload = ['date_time' => TestCase::$now];

        $response = $this->actingAs($user)->post("api/games/{$game->id}/start", $payload);
        $otherAccountResponse = $this->actingAs($user)->post("api/games/{$otherAccountGame->id}/start", $payload);

        $this->assertEquals($allow, $user->can('update', $game));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('update', $otherAccountGame));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testPauseGameAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $game = GameFactory::new()->for($user->account)->create();
        $otherAccountGame = GameFactory::new()->create();

        $payload = ['date_time' => TestCase::$now];

        $response = $this->actingAs($user)->post("api/games/{$game->id}/pause", $payload);
        $otherAccountResponse = $this->actingAs($user)->post("api/games/{$otherAccountGame->id}/pause", $payload);

        $this->assertEquals($allow, $user->can('update', $game));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('update', $otherAccountGame));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testResumeGameAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $game = GameFactory::new()->for($user->account)->create();
        $otherAccountGame = GameFactory::new()->create();

        $payload = ['date_time' => TestCase::$now];

        $response = $this->actingAs($user)->post("api/games/{$game->id}/resume", $payload);
        $otherAccountResponse = $this->actingAs($user)->post("api/games/{$otherAccountGame->id}/resume", $payload);

        $this->assertEquals($allow, $user->can('update', $game));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('update', $otherAccountGame));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testFinishGameAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $game = GameFactory::new()->for($user->account)->create();
        $otherAccountGame = GameFactory::new()->create();

        $payload = ['date_time' => TestCase::$now];

        $response = $this->actingAs($user)->post("api/games/{$game->id}/finish", $payload);
        $otherAccountResponse = $this->actingAs($user)->post("api/games/{$otherAccountGame->id}/finish", $payload);

        $this->assertEquals($allow, $user->can('update', $game));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('update', $otherAccountGame));
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
