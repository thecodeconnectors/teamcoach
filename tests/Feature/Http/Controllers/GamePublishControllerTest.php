<?php

namespace Feature\Http\Controllers;

use Database\Factories\GameFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class GamePublishControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItPublishesAGame(): void
    {
        $game = GameFactory::new()->create();

        $this
            ->actingAs($this->user())
            ->post("api/games/{$game->id}/publish")
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($game->fresh()->is_public);
    }

    public function testItUnPublishesAGame(): void
    {
        $game = GameFactory::new()->create();

        $game->makePublic();

        $this
            ->actingAs($this->user())
            ->post("api/games/{$game->id}/unpublish")
            ->assertStatus(Response::HTTP_OK);

        $this->assertFalse($game->fresh()->is_public);
    }

    public function testItShowsAGame(): void
    {
        $game = GameFactory::new()->create();

        $game->makePublic();

        $this
            ->get("api/games/public/{$game->url_secret}")
            ->assertStatus(Response::HTTP_OK);
    }

    public function testItDoesNotShowAnUnpublishedGame(): void
    {
        $game = GameFactory::new()->create();

        $game->makePublic();
        $game->makePrivate();

        $this
            ->get("api/games/public/{$game->url_secret}")
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testItShowsAnUnpubishedGameForLoggedInUsers(): void
    {
        $game = GameFactory::new()->create();

        $game->makePublic();

        $this
            ->actingAs($this->user())
            ->get("api/games/public/{$game->url_secret}")
            ->assertStatus(Response::HTTP_OK);
    }
}
