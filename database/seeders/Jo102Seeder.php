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
        $team = Team::query()->create([
            'name' => 'JO10-2',
        ]);

        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Pieter',
            'position' => Position::Attack,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Levi',
            'position' => Position::Attack,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Ayman',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Yussuf',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Rico',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Moos',
            'position' => Position::Defense,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Muhammed',
            'position' => Position::Defense,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Mert',
            'position' => Position::Goal,
        ]);
    }
}
