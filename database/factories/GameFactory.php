<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'team_id' => fn () => TeamFactory::new(),
            'opponent_id' => fn () => TeamFactory::new(),
            'start_at' => now(),
        ];
    }
}
