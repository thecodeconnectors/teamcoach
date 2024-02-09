<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\EventType;
use App\Models\Event;
use Database\Factories\EventFactory;
use Database\Factories\GameFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class EventControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItCreatesAnEvent(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();
        $opponent = TeamFactory::new()->for($user->account)->create();
        $game = GameFactory::new()->for($user->account)->for($team, 'team')->for($opponent, 'opponent')->create();
        $player = PlayerFactory::new()->for($user->account)->for($game->team)->create();

        $game->addPlayer($player);

        $payload = [
            'type' => EventType::Goal->value,
            'player_id' => $player->id,
            'team_id' => $player->team_id,
            'game_id' => $game->id,
        ];

        $this
            ->actingAs($user)
            ->post('api/events', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.type', $payload['type']);
                $json->where('data.player_id', $payload['player_id']);
                $json->where('data.team_id', $payload['team_id']);
                $json->where('data.started_at', now()->format('Y-m-d H:i:s'));
            });

        $this->assertDatabaseHas(Event::class, $payload);
    }

    public function testItUpdatesAnEvent(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();
        $opponent = TeamFactory::new()->for($user->account)->create();
        $game = GameFactory::new()->for($user->account)->for($team, 'team')->for($opponent, 'opponent')->create();
        $player = PlayerFactory::new()->for($user->account)->for($game->team)->create();

        $game->addPlayer($player);

        $event = EventFactory::new()->for($user->account)->create([
            'type' => EventType::Goal->value,
            'player_id' => $player->id,
            'team_id' => $player->team_id,
            'account_id' => $player->team_id,
            'game_id' => $game->id,
        ]);

        $payload = [
            'game_id' => $game->id,
            'type' => EventType::YellowCard->value,
            'started_at' => now()->subMinutes(10),
        ];

        $this
            ->actingAs($user)
            ->patch("api/events/{$event->id}", $payload)
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas(Event::class, [
            'player_id' => $player->id,
            'team_id' => $player->team_id,
            'account_id' => $player->team_id,
            'game_id' => $game->id,
            'type' => EventType::YellowCard->value,
            'started_at' => now()->subMinutes(10),
        ]);
    }

    public function testItDeletesAnEvent(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();
        $opponent = TeamFactory::new()->for($user->account)->create();
        $game = GameFactory::new()->for($user->account)->for($team, 'team')->for($opponent, 'opponent')->create();
        $player = PlayerFactory::new()->for($user->account)->for($game->team)->create();

        $game->addPlayer($player);

        $event = Event::query()->create([
            'type' => EventType::Goal->value,
            'player_id' => $player->id,
            'team_id' => $player->team_id,
            'account_id' => $player->team_id,
            'game_id' => $game->id,
        ]);

        $this
            ->actingAs($user)
            ->delete("api/events/{$event->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseCount(Event::class, 0);
    }

}
