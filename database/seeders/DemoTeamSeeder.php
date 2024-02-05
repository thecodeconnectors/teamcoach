<?php

namespace Database\Seeders;

use App\Enums\Position;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class DemoTeamSeeder extends Seeder
{
    public function run(): void
    {
        $avatars = config('avatars.avatars');

        $team = Team::query()->create([
            'name' => 'Team A',
        ]);

        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'John',
            'position' => Position::Attack,
            'avatar' => $avatars[0],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Marc',
            'position' => Position::Attack,
            'avatar' => $avatars[1],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Suzy',
            'position' => Position::Mid,
            'avatar' => $avatars[2],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Paul',
            'position' => Position::Mid,
            'avatar' => $avatars[3],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Ann',
            'position' => Position::Mid,
            'avatar' => $avatars[4],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Martin',
            'position' => Position::Defense,
            'avatar' => $avatars[5],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Mike',
            'position' => Position::Defense,
            'avatar' => $avatars[6],
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'William',
            'position' => Position::Goal,
            'avatar' => $avatars[7],
        ]);
    }
}
