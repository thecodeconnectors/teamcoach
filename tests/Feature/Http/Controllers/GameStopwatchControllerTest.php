<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\EventType;
use App\Models\Event;
use Database\Factories\GameFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class GameStopwatchControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItStartsAGame(): void
    {
        $user = $this->owner();
        $game = GameFactory::new()->for($user->account)->create();

        $payload = [
            'date_time' => $dateTime = now()->subMinutes(10)->format('Y-m-d H:i:s'),
        ];

        $this
            ->actingAs($user)
            ->post("api/games/{$game->id}/start", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($dateTime) {
                $json->where('data.started_at', $dateTime);
            });
    }

    public function testItFinishesAGame(): void
    {
        $user = $this->owner();
        $game = GameFactory::new()->for($user->account)->create();
        $game->start(TestCase::$now);

        $payload = [
            'date_time' => $dateTime = now()->subMinutes(10)->format('Y-m-d H:i:s'),
        ];

        $this
            ->actingAs($user)
            ->post("api/games/{$game->id}/finish", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($dateTime) {
                $json->where('data.finished_at', $dateTime);
            });
    }

    public function testItPausesAGame(): void
    {
        $user = $this->owner();
        $game = GameFactory::new()->for($user->account)->create();
        $game->start(TestCase::$now);

        $this
            ->actingAs($user)
            ->post("api/games/{$game->id}/pause", [
                'date_time' => TestCase::$now,
            ])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::GameBreak->value,
            'game_id' => $game->id,
            'started_at' => TestCase::$now,
        ]);
    }

    public function testItResumesAPausedAGame(): void
    {
        $user = $this->owner();
        $game = GameFactory::new()->for($user->account)->create();

        $game->pause($start = now()->subMinutes(10)->format('Y-m-d H:i:s'));

        $this
            ->actingAs($user)
            ->post("api/games/{$game->id}/resume", [
                'date_time' => $end = now()->format('Y-m-d H:i:s'),
            ])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::GameBreak->value,
            'game_id' => $game->id,
            'started_at' => $start,
            'finished_at' => $end,
        ]);
    }

}
