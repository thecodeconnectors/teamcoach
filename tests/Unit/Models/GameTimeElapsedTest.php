<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GameTimeElapsedTest extends TestCase
{

    /**
     * @dataProvider dataProvider
     */
    public function testGameTimeElapsed(int $secondsElapsed, string $expected): void
    {
        $startedAt = Carbon::parse(TestCase::$now)->subSeconds($secondsElapsed);
        $game = new Game(['started_at' => $startedAt]);

        $this->assertEquals($secondsElapsed, $game->seconds_elapsed);
        $this->assertEquals($expected, $game->time_elapsed);
    }

    public static function dataProvider(): array
    {
        return [
            [
                'secondsElapsed' => 0,
                'expected' => '00:00',
            ],
            [
                'secondsElapsed' => 1,
                'expected' => '00:01',
            ],
            [
                'secondsElapsed' => 122,
                'expected' => '02:02',
            ],
            [
                'secondsElapsed' => 3600,
                'expected' => '01:00:00',
            ],
            [
                'secondsElapsed' => 3661,
                'expected' => '01:01:01',
            ],
        ];
    }
}
