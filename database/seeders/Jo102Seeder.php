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
        $avatars = config('avatars.avatars');

        $team = Team::query()->create([
            'name' => 'JO10-2',
        ]);

        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Pieter',
            'position' => Position::Attack,
            'avatar' => $avatars[0],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Levi',
            'position' => Position::Attack,
            'avatar' => $avatars[1],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Ayman',
            'position' => Position::Mid,
            'avatar' => $avatars[2],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Yussuf',
            'position' => Position::Mid,
            'avatar' => $avatars[3],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Rico',
            'position' => Position::Mid,
            'avatar' => $avatars[4],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Moos',
            'position' => Position::Defense,
            'avatar' => $avatars[5],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Muhammed',
            'position' => Position::Defense,
            'avatar' => $avatars[6],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Mert',
            'position' => Position::Goal,
            'avatar' => $avatars[7],
        ]);
    }
}
