<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\Position;
use App\Models\Player;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItListsPlayers(): void
    {
        PlayerFactory::new()->count(20)->create();

        $this
            ->actingAs($this->user())
            ->get('api/players?per_page=10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) {
                $this->assertCount(10, $data);

                return true;
            });
    }

    public function testItCreatesAPlayer(): void
    {
        $team = TeamFactory::new()->create();

        $payload = [
            'name' => 'Player A',
            'position' => Position::Attack->value,
            'team_id' => $team->id,
        ];

        $this
            ->actingAs($this->user())
            ->post('api/players', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.name', $payload['name']);
            });

        $this->assertDatabaseHas(Player::class, $payload);
    }

    public function testItShowsAPlayer(): void
    {
        $player = PlayerFactory::new()->create();

        $this
            ->actingAs($this->user())
            ->get("api/players/{$player->id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($player) {
                $json->where('data.name', $player->name);
            });
    }

    public function testItUpdatesAPlayer(): void
    {
        $player = PlayerFactory::new()->create();

        $payload = [
            'name' => 'Player A',
            'position' => Position::Defense->value,
        ];

        $this
            ->actingAs($this->user())
            ->patch("api/players/{$player->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.name', $payload['name']);
            });

        $this->assertDatabaseHas(Player::class, $payload);
    }

    public function testItDeletesAPlayer(): void
    {
        $player = PlayerFactory::new()->create();

        $this
            ->actingAs($this->user())
            ->delete("api/players/{$player->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted(Player::class, ['id' => $player->id]);
    }
}
