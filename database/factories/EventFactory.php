<?php

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'account_id' => fn () => AccountFactory::new(),
            'game_id' => fn () => GameFactory::new(),
            'type' => EventType::Assist->value,
            'started_at' => now(),
        ];
    }
}
