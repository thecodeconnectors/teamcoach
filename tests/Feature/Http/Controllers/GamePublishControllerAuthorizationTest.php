<?php

namespace Feature\Http\Controllers;

use App\Modules\Users\Enums\RoleType;
use Database\Factories\GameFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class GamePublishControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider roleDataProvider
     */
    public function testPublishGameAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $game = GameFactory::new()->for($user->account)->create();
        $otherAccountGame = GameFactory::new()->create();

        $payload = ['date_time' => TestCase::$now];

        $response = $this->actingAs($user)->post("api/games/{$game->id}/publish", $payload);
        $otherAccountResponse = $this->actingAs($user)->post("api/games/{$otherAccountGame->id}/publish", $payload);

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
