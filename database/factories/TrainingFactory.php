<?php

namespace Database\Factories;

use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    protected $model = Training::class;

    public function definition(): array
    {
        return [
            'team_id' => fn () => TeamFactory::new(),
            'account_id' => fn () => AccountFactory::new(),
            'start_at' => $this->faker->dateTime,
        ];
    }
}
