<?php

namespace Tests\Unit\Models;

use Database\Factories\GameFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithUsers;

class GameTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testGameIsNotStarted(): void
    {
        $game = GameFactory::new()->create();

        $this->assertFalse($game->isStarted());
        $this->assertFalse($game->isPlaying());
    }

    public function testGameIsStarted(): void
    {
        $game = GameFactory::new()->create(['started_at' => now()]);

        $this->assertTrue($game->isStarted());
        $this->assertTrue($game->isPlaying());
    }

    public function testGameIsNotFinished(): void
    {
        $game = GameFactory::new()->create(['started_at' => now()]);

        $this->assertFalse($game->isFinished());
        $this->assertTrue($game->isPlaying());
    }

    public function testGameIsFinished(): void
    {
        $game = GameFactory::new()->create(['finished_at' => now()]);

        $this->assertTrue($game->isFinished());
        $this->assertFalse($game->isPlaying());
    }

    public function testGameIsNotPaused(): void
    {
        $game = GameFactory::new()->create();

        $this->assertFalse($game->isPaused());
    }

    public function testGameIsPaused(): void
    {
        $game = GameFactory::new()->create();

        $game->pause(now());

        $this->assertTrue($game->fresh()->isPaused());
    }

}
