<?php

namespace Database\Seeders;

use App\Enums\Position;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class Jo102Seeder extends Seeder
{
    public function run(): void
    {
        Team::query()->create([
            'name' => 'JO10-2',
        ]);

        Player::query()->create([
            'name' => 'Pieter',
            'position' => Position::Attack,
        ]);
        Player::query()->create([
            'name' => 'Levi',
            'position' => Position::Attack,
        ]);
        Player::query()->create([
            'name' => 'Ayman',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'name' => 'Yussuf',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'name' => 'Rico',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'name' => 'Moos',
            'position' => Position::Defense,
        ]);
        Player::query()->create([
            'name' => 'Muhammed',
            'position' => Position::Defense,
        ]);
        Player::query()->create([
            'name' => 'Mert',
            'position' => Position::Goal,
        ]);
    }
}
