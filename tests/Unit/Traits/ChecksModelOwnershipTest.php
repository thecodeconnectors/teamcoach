<?php

namespace Tests\Unit\Traits;

use App\Traits\ChecksModelOwnership;
use Database\Factories\GameFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithUsers;

class ChecksModelOwnershipTest extends TestCase
{
    use RefreshDatabase, WithUsers, ChecksModelOwnership;

    public function testGameOwnership(): void
    {
        $user = $this->user();
        $game = GameFactory::new()->for($user->account)->create();
        $otherGame = GameFactory::new()->create();

        $this->assertTrue($this->gameBelongsToAccount($user->account_id));
        $this->assertTrue($this->gameBelongsToAccount($user->account_id, $game->id));
        $this->assertFalse($this->gameBelongsToAccount($user->account_id, $otherGame->id));
    }

    public function testTeamOwnership(): void
    {
        $user = $this->user();
        $team = TeamFactory::new()->for($user->account)->create();
        $otherTeam = TeamFactory::new()->create();

        $this->assertTrue($this->teamBelongsToAccount($user->account_id));
        $this->assertTrue($this->teamBelongsToAccount($user->account_id, $team->id));
        $this->assertFalse($this->teamBelongsToAccount($user->account_id, $otherTeam->id));
    }

    public function testPlayerOwnership(): void
    {
        $user = $this->user();
        $player = PlayerFactory::new()->for($user->account)->create();
        $otherPlayer = PlayerFactory::new()->create();

        $this->assertTrue($this->playerBelongsToAccount($user->account_id));
        $this->assertTrue($this->playerBelongsToAccount($user->account_id, $player->id));
        $this->assertFalse($this->playerBelongsToAccount($user->account_id, $otherPlayer->id));
    }

    public function testPlayerBelongsToGame(): void
    {
        $user = $this->user();
        $team = TeamFactory::new()->for($user->account)->create();
        $player = PlayerFactory::new()->for($user->account)->for($team)->create();
        $otherPlayer = PlayerFactory::new()->for($user->account)->create();

        $game = GameFactory::new()->for($user->account)->create();
        $game->addPlayer($player);

        $this->assertTrue($this->playerBelongsToGame());
        $this->assertTrue($this->playerBelongsToGame($game->id));
        $this->assertTrue($this->playerBelongsToGame(null, $player->id));
        $this->assertTrue($this->playerBelongsToGame($game->id, $player->id));
        $this->assertFalse($this->playerBelongsToGame($game->id, $otherPlayer->id));
    }

}
