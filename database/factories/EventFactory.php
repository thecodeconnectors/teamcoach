<?php

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\Event;
use App\Models\Game;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        /* @property int $player_id
         * @property int $team_id
         * @property EventType $type
         * @property Player $player
         * @property Game $game
         * @property Carbon $started_at
         * @property Carbon $created_at
         * @property Carbon|null $updated_at
         */
        return [
            'game_id' => fn() => GameFactory::new(),
            'type' => EventType::Assist->value,
            'started_at' => now(),
        ];
    }
}
