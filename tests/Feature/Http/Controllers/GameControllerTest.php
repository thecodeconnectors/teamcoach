<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\GamePlayerType;
use App\Models\Game;
use App\Models\Team;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class GameControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItCreatesAGame(): void
    {
        $team = TeamFactory::new()->create();
        $players = PlayerFactory::new()->count(3)->for($team)->create();

        $payload = [
            'team_id' => $team->id,
            'opponent_name' => 'Opponent',
            'start_at' => $startAt = now()->format('Y-m-d H:i:s'),
            'player_ids' => $players->pluck('id')->toArray(),
        ];

        $this
            ->actingAs($this->user())
            ->post('api/games', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(function (AssertableJson $json) use ($payload, $startAt) {
                $json->where('data.team_id', $payload['team_id']);
                $json->where('data.opponent_id', 2);
                $json->where('data.start_at', $startAt);
            });

        $this->assertDatabaseHas(Game::class, Arr::except($payload, ['player_ids', 'opponent_name']));

        $this->assertDatabaseHas(Team::class, [
            'is_opponent' => 1,
            'name' => 'Opponent',
        ]);

        $this->assertDatabaseHas('game_player', [
            'game_id' => $team->id,
            'player_id' => $players->first()->id,
            'type' => GamePlayerType::Playing->value,
        ]);
    }
}
