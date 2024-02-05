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
        $game = GameFactory::new()->create();

        $payload = [
            'date_time' => $dateTime = now()->subMinutes(10)->format('Y-m-d H:i:s'),
        ];

        $this
            ->actingAs($this->user())->post("api/games/{$game->id}/start", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($dateTime) {
                $json->where('data.started_at', $dateTime);
            });
    }

    public function testItFinishesAGame(): void
    {
        $game = GameFactory::new()->create();

        $payload = [
            'date_time' => $dateTime = now()->subMinutes(10)->format('Y-m-d H:i:s'),
        ];

        $this
            ->actingAs($this->user())->post("api/games/{$game->id}/finish", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($dateTime) {
                $json->where('data.finished_at', $dateTime);
            });
    }

    public function testItPausesAGame(): void
    {
        $game = GameFactory::new()->create();

        $dateTime = now()->format('Y-m-d H:i:s');

        $this
            ->actingAs($this->user())->post("api/games/{$game->id}/pause", [
                'date_time' => $dateTime,
            ])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::GameBreak->value,
            'game_id' => $game->id,
            'started_at' => now(),
        ]);
    }

    public function testItResumesAPausedAGame(): void
    {
        $game = GameFactory::new()->create();

        $game->pause($start = now()->subMinutes(10)->format('Y-m-d H:i:s'));

        $this
            ->actingAs($this->user())->post("api/games/{$game->id}/resume", [
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
