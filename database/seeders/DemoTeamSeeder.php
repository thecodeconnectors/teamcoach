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
        $team = Team::query()->create([
            'name' => 'Team A',
        ]);

        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'John',
            'position' => Position::Attack,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Marc',
            'position' => Position::Attack,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Suzy',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Paul',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Ann',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Martin',
            'position' => Position::Defense,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'Mike',
            'position' => Position::Defense,
        ]);
        Player::query()->create([
            'team_id' => $team->id,
            'name' => 'William',
            'position' => Position::Goal,
        ]);
    }
}
