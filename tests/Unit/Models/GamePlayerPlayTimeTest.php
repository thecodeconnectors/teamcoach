<?php

namespace Tests\Unit\Models;

use Database\Factories\GameFactory;
use Database\Factories\PlayerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\WithUsers;

class GamePlayerPlayTimeTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider gamePlayTimeDataProvider
     */
    public function testPlayerPlayTime(Carbon $gameStart = null, Carbon $gameFinish = null, array $substituteEvents = [], $expectedPlayTime = 0): void
    {
        $game = GameFactory::new()->create();
        $player = PlayerFactory::new()->create();
        $substitute = PlayerFactory::new()->create();

        $game->addPlayer($player);
        $game->addSubstitute($substitute);

        if ($gameStart) {
            $game->start($gameStart);
        }

        foreach ($substituteEvents as $substituteEvent) {
            $game->substitutePlayer($substitute, $player, $substituteEvent['started_at']);
            if ($substituteEvent['finished_at']) {
                $game->substitutePlayer($player, $substitute, $substituteEvent['finished_at']);
            }
        }

        if ($gameFinish) {
            $game->finish($gameFinish);
        }

        $this->assertEqualSeconds($expectedPlayTime, $player->playTimeForGame($game));
    }

    public static function gamePlayTimeDataProvider(): array
    {
        return [
            'game did not started' => [
                'gameStart' => null,
                'gameFinish' => null,
                'substituteEvents' => [],
                'expectedPlayTime' => 0,
            ],
            'played full game' => [
                'gameStart' => Carbon::parse(TestCase::$now)->subMinutes(60),
                'gameFinish' => Carbon::parse(TestCase::$now),
                'substituteEvents' => [],
                'expectedPlayTime' => 60 * 60,
            ],
            'playing unfinished game' => [
                'gameStart' => Carbon::parse(TestCase::$now)->subMinutes(15),
                'gameFinish' => null,
                'substituteEvents' => [],
                'expectedPlayTime' => 15 * 60,
            ],
            'played with one 10 minute substitute' => [
                'gameStart' => Carbon::parse(TestCase::$now)->subMinutes(60),
                'gameFinish' => Carbon::parse(TestCase::$now),
                'substituteEvents' => [
                    [
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(50),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(40),
                    ],
                ],
                'expectedPlayTime' => (60 * 60) - (10 * 60),
            ],
            'played with two 5 minute substitutes' => [
                'gameStart' => Carbon::parse(TestCase::$now)->subMinutes(60),
                'gameFinish' => Carbon::parse(TestCase::$now),
                'substituteEvents' => [
                    [
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(50),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(45),
                    ],
                    [
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(30),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(25),
                    ],
                ],
                'expectedPlayTime' => (60 * 60) - (10 * 60),
            ],
            'playing unfinished game, with one 10 minute substitute' => [
                'gameStart' => Carbon::parse(TestCase::$now)->subMinutes(30),
                'gameFinish' => null,
                'substituteEvents' => [
                    [
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(20),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                    ],
                ],
                'expectedPlayTime' => (30 * 60) - (10 * 60),
            ],
            'playing unfinished game, with two 5 minute substitutes' => [
                'gameStart' => Carbon::parse(TestCase::$now)->subMinutes(30),
                'gameFinish' => null,
                'substituteEvents' => [
                    [
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(20),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(15),
                    ],
                    [
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(5),
                    ],
                ],
                'expectedPlayTime' => (30 * 60) - (10 * 60),
            ],
        ];
    }
}
