<?php

namespace Feature\Http\Controllers;

use App\Enums\GamePlayerType;
use App\Modules\Users\Enums\RoleType;
use Database\Factories\GameFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class GamePlayControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider roleDataProvider
     */
    public function testSwitchPlayerAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $team = TeamFactory::new()->for($user->account)->create();
        $game = GameFactory::new()->for($user->account)->create();
        $playerA = PlayerFactory::new()->for($team)->create();
        $playerB = PlayerFactory::new()->for($team)->create();

        $otherAccountGame = GameFactory::new()->create();

        $game->addPlayer($playerA);
        $game->addSubstitute($playerB);

        $payload = [
            'player_id' => $playerA->id,
            'substitute_id' => $playerB->id,
        ];

        $response = $this->actingAs($user)->post("api/games/{$game->id}/switch-player", $payload);
        $otherAccountResponse = $this->actingAs($user)->post("api/games/{$otherAccountGame->id}/switch-player", $payload);

        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

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
                'allow' => false,
                'allowOtherAccount' => false,
            ],
            'admin' => [
                'role' => RoleType::Admin,
                'allow' => false,
                'allowOtherAccount' => false,
            ],
        ];
    }

    public function testItSwitchesTwoPlayers(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();
        $playerA = PlayerFactory::new()->for($user->account)->for($team)->create();
        $playerB = PlayerFactory::new()->for($user->account)->for($team)->create();
        $game = GameFactory::new()->for($user->account)->for($team)->create();

        $game->addPlayer($playerA);
        $game->addSubstitute($playerB);

        $payload = [
            'player_id' => $playerA->id,
            'substitute_id' => $playerB->id,
        ];

        $this
            ->actingAs($user)
            ->post("api/games/{$game->id}/switch-player", $payload);

        $this->assertDatabaseHas('game_player', [
            'game_id' => $team->id,
            'player_id' => $playerB->id,
            'type' => GamePlayerType::Playing->value,
        ]);

        $this->assertDatabaseHas('game_player', [
            'game_id' => $team->id,
            'player_id' => $playerA->id,
            'type' => GamePlayerType::Substitute->value,
        ]);
    }
}
