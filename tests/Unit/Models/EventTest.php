<?php

namespace Tests\Unit\Models;

use App\Enums\EventType;
use Database\Factories\EventFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\WithUsers;

class EventTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider itCalculatesTheSecondsOfAnEventDataProvider
     */
    public function testItCalculatesTheSecondsOfAnEvent(array $attributes = [], int $expected = 0): void
    {
        $event = EventFactory::new()->create($attributes);

        $this->assertEqualSeconds($expected, $event->seconds);
    }

    public static function itCalculatesTheSecondsOfAnEventDataProvider(): array
    {
        return [
            'only started_at' => [
                'attributes' => [
                    'started_at' => Carbon::parse(TestCase::$now),
                ],
                'expected' => 0,
            ],
            'started_at and finished_at for duration event' => [
                'attributes' => [
                    'type' => EventType::GameBreak,
                    'started_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                    'finished_at' => Carbon::parse(TestCase::$now),
                ],
                'expected' => 10 * 60,
            ],
            'started_at without finished_at for duration event' => [
                'attributes' => [
                    'type' => EventType::GameBreak,
                    'started_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                    'finished_at' => null,
                ],
                'expected' => 10 * 60,
            ],
            'started_at and finished_at, but no duration event' => [
                'attributes' => [
                    'type' => EventType::Assist,
                    'started_at' => Carbon::parse(TestCase::$now)->subMinutes(10),
                    'finished_at' => Carbon::parse(TestCase::$now),
                ],
                'expected' => 0,
            ],
        ];
    }
}
