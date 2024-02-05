<?php

namespace Tests\Unit\Models;

use App\Enums\EventType;
use Database\Factories\GameFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\WithUsers;

class GamePlayTimeTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider gamePlayTimeDataProvider
     */
    public function testGamePlayTime(array $gameAttributes, array $playTimeEventAttributes = [], $expectedPlayTime = 0): void
    {
        $game = GameFactory::new()->create($gameAttributes);

        foreach ($playTimeEventAttributes as $playTimeEvent) {
            $game->events()->create($playTimeEvent);
        }

        $this->assertEqualSeconds($expectedPlayTime, $game->playTime());
    }

    public static function gamePlayTimeDataProvider(): array
    {
        return [
            'not started yet' => [
                'gameAttributes' => [],
                'playTimeEventAttributes' => [],
                'expectedPlayTime' => 0,
            ],
            'started 10 minutes ago, but not yet finished' => [
                'gameAttributes' => [
                    'started_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                ],
                'playTimeEventAttributes' => [],
                'expectedPlayTime' => 10 * 60,
            ],
            'started 20 minutes ago, and finished 10 minutes ago' => [
                'gameAttributes' => [
                    'started_at' => Carbon::parse(TestCase::$now)->subMinutes(20),
                    'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                ],
                'playTimeEventAttributes' => [],
                'expectedPlayTime' => 10 * 60,
            ],
            'started 10 minutes ago, and is paused since 5 minutes ago' => [
                'gameAttributes' => [
                    'started_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                ],
                'playTimeEventAttributes' => [
                    [
                        'type' => EventType::GameBreak,
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(5),
                    ],
                ],
                'expectedPlayTime' => 5 * 60,
            ],
            'started 30 minutes ago, and had two 5 minute breaks' => [
                'gameAttributes' => [
                    'started_at' => Carbon::parse(TestCase::$now)->subMinutes(30),
                ],
                'playTimeEventAttributes' => [
                    [
                        'type' => EventType::GameBreak,
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(20),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(15),
                    ],
                    [
                        'type' => EventType::GameBreak,
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(5),
                    ],
                ],
                'expectedPlayTime' => 20 * 60,
            ],
            'started 30 minutes ago, and is in second 5 minute break' => [
                'gameAttributes' => [
                    'started_at' => Carbon::parse(TestCase::$now)->subMinutes(30),
                ],
                'playTimeEventAttributes' => [
                    [
                        'type' => EventType::GameBreak,
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(20),
                        'finished_at' => Carbon::parse(TestCase::$now)->subMinutes(15),
                    ],
                    [
                        'type' => EventType::GameBreak,
                        'started_at' => Carbon::parse(TestCase::$now)->subMinutes(5),
                    ],
                ],
                'expectedPlayTime' => 20 * 60,
            ],
        ];
    }
}
