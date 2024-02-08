<?php

namespace Feature\Http\Controllers;

use App\Enums\GamePlayerType;
use Database\Factories\GameFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithUsers;

class GamePlayControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItSwitchesTwoPlayers(): void
    {
        $team = TeamFactory::new()->create();
        $playerA = PlayerFactory::new()->for($team)->create();
        $playerB = PlayerFactory::new()->for($team)->create();
        $game = GameFactory::new()->for($team)->create();

        $game->addPlayer($playerA);
        $game->addSubstitute($playerB);

        $payload = [
            'player_id' => $playerA->id,
            'substitute_id' => $playerB->id,
        ];

        $this
            ->actingAs($this->user())
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
