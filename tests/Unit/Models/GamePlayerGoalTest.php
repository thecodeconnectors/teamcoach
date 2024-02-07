<?php

namespace Tests\Unit\Models;

use App\Enums\EventType;
use Database\Factories\EventFactory;
use Database\Factories\GameFactory;
use Database\Factories\PlayerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\WithUsers;

class GamePlayerGoalTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider gamePlayTDataProvider
     */
    public function testPlayerGoals(array $events = [], int $expectedGoals = 0): void
    {
        $game = GameFactory::new()->create();
        $player = PlayerFactory::new()->create();

        $game->addPlayer($player);
        $game->start(Carbon::parse(TestCase::$now));

        foreach ($events as $event) {
            EventFactory::new()->for($game)->for($player)->create($event);
        }

        $this->assertEquals($expectedGoals, $player->goalsForGame($game));
    }

    public static function gamePlayTDataProvider(): array
    {
        return [
            'no events, no goals' => [
                'events' => [],
                'expectedGoals' => 0,
            ],
            'one goal' => [
                'events' => [
                    [
                        'type' => EventType::Goal,
                    ],
                ],
                'expectedGoals' => 1,
            ],
            'two goals' => [
                'events' => [
                    [
                        'type' => EventType::Goal,
                    ],
                    [
                        'type' => EventType::Goal,
                    ],
                ],
                'expectedGoals' => 2,
            ],
        ];
    }
}
