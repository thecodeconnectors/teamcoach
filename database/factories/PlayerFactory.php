<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'account_id' => fn () => AccountFactory::new(),
            'team_id' => fn () => TeamFactory::new(),
            'name' => $this->faker->name,
        ];
    }
}
