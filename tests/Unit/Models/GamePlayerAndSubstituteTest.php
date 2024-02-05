<?php

namespace Tests\Unit\Models;

use App\Enums\EventType;
use App\Enums\GamePlayerType;
use App\Enums\Position;
use App\Models\Event;
use Database\Factories\GameFactory;
use Database\Factories\PlayerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithUsers;

class GamePlayerAndSubstituteTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider itAddsAPlayerDataProvider
     */
    public function testItAddsAPlayer(?GamePlayerType $type = GamePlayerType::Playing, ?Position $position = null, array $expected = []): void
    {
        $game = GameFactory::new()->create();
        $player = PlayerFactory::new()->create();

        $game->addPlayer($player, TestCase::$now, $position, $type);

        $expected = array_merge([
            'game_id' => $game->id,
            'player_id' => $player->id,
        ], $expected);

        $this->assertDatabaseHas('game_player', $expected);
    }

    public static function itAddsAPlayerDataProvider(): array
    {
        return [
            'Starter on undefined position by default' => [
                'type' => null,
                'position' => null,
                'expected' => [
                    'type' => GamePlayerType::Playing->value,
                    'position' => null,
                ],
            ],
            'Starter on attack position' => [
                'type' => GamePlayerType::Playing,
                'position' => Position::Attack,
                'expected' => [
                    'type' => GamePlayerType::Playing->value,
                    'position' => Position::Attack->value,
                ],
            ],
            'Substitute on undefined position' => [
                'type' => GamePlayerType::Substitute,
                'position' => null,
                'expected' => [
                    'type' => GamePlayerType::Substitute->value,
                    'position' => null,
                ],
            ],
        ];
    }

    public function testItDoesNotAddAPlayerTwice(): void
    {
        $game = GameFactory::new()->create();
        $player = PlayerFactory::new()->create();

        $game->addPlayer($player);
        $game->addPlayer($player);

        $this->assertDatabaseCount('game_player', 1);
    }

    public function testItUpdatesAGamePlayerTypeAndPosition(): void
    {
        $game = GameFactory::new()->create();
        $player = PlayerFactory::new()->create();

        $game->addPlayer($player);
        $game->updatePlayer($player, Position::Attack, GamePlayerType::Substitute);

        $this->assertDatabaseCount('game_player', 1);

        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $player->id,
            'type' => GamePlayerType::Substitute->value,
            'position' => Position::Attack->value,
        ]);
    }

    public function testItRemovesAGamePlayerWithGamePlayerTime(): void
    {
        $game = GameFactory::new()->create();
        $player = PlayerFactory::new()->create();

        $game->start(TestCase::$now);

        $game->addPlayer($player);
        $game->removePlayer($player);

        $this->assertDatabaseCount('game_player', 0);
        $this->assertDatabaseCount('events', 0);
    }

    public function testPlayerIsASubstituteOrPlaying(): void
    {
        $game = GameFactory::new()->create();
        $playing = PlayerFactory::new()->create();
        $substitute = PlayerFactory::new()->create();
        $otherPlayer = PlayerFactory::new()->create();

        $game->addPlayer($playing);
        $game->addSubstitute($substitute);

        $this->assertFalse($game->playerIsSubstitute($playing));
        $this->assertTrue($game->playerIsPlaying($playing));

        $this->assertTrue($game->playerIsSubstitute($substitute));
        $this->assertFalse($game->playerIsPlaying($substitute));

        $this->assertFalse($game->playerIsSubstitute($otherPlayer));
        $this->assertFalse($game->playerIsPlaying($otherPlayer));
    }

    public function testItSubstitutesAPlayerForASubstituteAsFirstParameter(): void
    {
        $game = GameFactory::new()->create();
        $substitute = PlayerFactory::new()->create();
        $playing = PlayerFactory::new()->create();

        $game->addSubstitute($substitute);
        $game->addPlayer($playing);

        $game->substitutePlayer($substitute, $playing, TestCase::$now);

        $this->assertFalse($game->playerIsSubstitute($substitute));
        $this->assertTrue($game->playerIsSubstitute($playing));
    }

    public function testItSubstitutesAPlayerForASubstituteAsSecondParameter(): void
    {
        $game = GameFactory::new()->create();
        $substitute = PlayerFactory::new()->create();
        $playing = PlayerFactory::new()->create();

        $game->addSubstitute($substitute);
        $game->addPlayer($playing);

        $game->substitutePlayer($playing, $substitute, TestCase::$now);

        $this->assertFalse($game->playerIsSubstitute($substitute));
        $this->assertTrue($game->playerIsSubstitute($playing));
    }

    public function testItWontSubstituteASubstitute(): void
    {
        $game = GameFactory::new()->create();
        $playerA = PlayerFactory::new()->create();
        $playerB = PlayerFactory::new()->create();

        $game->addSubstitute($playerA);
        $game->addSubstitute($playerB);

        $game->substitutePlayer($playerB, $playerA, TestCase::$now);

        $this->assertTrue($game->playerIsSubstitute($playerA));
        $this->assertTrue($game->playerIsSubstitute($playerB));
        $this->assertFalse($game->playerIsPlaying($playerA));
        $this->assertFalse($game->playerIsPlaying($playerB));
    }

    public function testItWontSubstituteANonGamePlayer(): void
    {
        $game = GameFactory::new()->create();
        $playerA = PlayerFactory::new()->create();
        $playerB = PlayerFactory::new()->create();

        $game->addPlayer($playerA);

        $game->substitutePlayer($playerA, $playerB, TestCase::$now);

        $this->assertFalse($game->playerIsSubstitute($playerA));
        $this->assertTrue($game->playerIsPlaying($playerA));

        $this->assertFalse($game->playerIsSubstitute($playerB));
        $this->assertFalse($game->playerIsPlaying($playerB));
    }

    public function testItStartsAPlayerTimerWhenAddingAPlayerDuringAGame(): void
    {
        $game = GameFactory::new()->create();
        $substitute = PlayerFactory::new()->create();

        $game->start(TestCase::$now);

        $game->addPlayer($substitute, TestCase::$now);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::PlayTime->value,
            'game_id' => $game->id,
            'player_id' => $substitute->id,
            'team_id' => $substitute->team_id,
            'started_at' => TestCase::$now,
        ]);
    }

    public function testItStopsAPlayerTimerWhenSubstitutingAPlayerDuringAGame(): void
    {
        $game = GameFactory::new()->create();
        $substitute = PlayerFactory::new()->create();

        $game->start(TestCase::$now);

        $game->addPlayer($substitute, TestCase::$now);
        $game->addSubstitute($substitute, TestCase::$now);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::PlayTime->value,
            'game_id' => $game->id,
            'player_id' => $substitute->id,
            'team_id' => $substitute->team_id,
            'started_at' => TestCase::$now,
            'finished_at' => TestCase::$now,
        ]);
    }

    public function testItStartsThePlayerTimersWhenTheGameStarts(): void
    {
        $game = GameFactory::new()->create();
        $playerA = PlayerFactory::new()->create();
        $playerB = PlayerFactory::new()->create();

        $game->addPlayer($playerA);
        $game->addPlayer($playerB);

        $game->start(TestCase::$now);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::PlayTime->value,
            'game_id' => $game->id,
            'player_id' => $playerA->id,
            'started_at' => TestCase::$now,
        ]);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::PlayTime->value,
            'game_id' => $game->id,
            'player_id' => $playerB->id,
            'started_at' => TestCase::$now,
        ]);
    }

    public function testItDoesNotStartThePlayTimersForSubstitutesWhenTheGameStarts(): void
    {
        $game = GameFactory::new()->create();
        $substituteA = PlayerFactory::new()->create();
        $substituteB = PlayerFactory::new()->create();

        $game->addSubstitute($substituteA);
        $game->addSubstitute($substituteB);

        $game->start(TestCase::$now);

        $this->assertDatabaseCount('events', 0);
    }

    public function testItStopsAPlayerTimer(): void
    {
        $game = GameFactory::new()->create();
        $player = PlayerFactory::new()->create();

        $game->start(TestCase::$now);
        $game->addPlayer($player);
        $game->addSubstitute($player);

        $this->assertDatabaseCount(Event::class, 1);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::PlayTime->value,
            'game_id' => $game->id,
            'player_id' => $player->id,
            'team_id' => $player->team_id,
            'started_at' => TestCase::$now,
            'finished_at' => TestCase::$now,
        ]);
    }

    public function testItStopsThePlayerTimersWhenTheGameStops(): void
    {
        $game = GameFactory::new()->create();
        $playerA = PlayerFactory::new()->create();
        $playerB = PlayerFactory::new()->create();

        $game->addPlayer($playerA);
        $game->addPlayer($playerB);

        $game->start(TestCase::$now);
        $game->finish(TestCase::$now);

        $this->assertDatabaseCount(Event::class, 2);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::PlayTime->value,
            'game_id' => $game->id,
            'player_id' => $playerA->id,
            'started_at' => TestCase::$now,
            'finished_at' => TestCase::$now,
        ]);

        $this->assertDatabaseHas(Event::class, [
            'type' => EventType::PlayTime->value,
            'game_id' => $game->id,
            'player_id' => $playerB->id,
            'started_at' => TestCase::$now,
            'finished_at' => TestCase::$now,
        ]);
    }
}
