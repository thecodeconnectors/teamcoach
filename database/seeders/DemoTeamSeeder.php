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
        Team::query()->create([
            'name' => 'Team A',
        ]);

        Player::query()->create([
            'name' => 'John',
            'position' => Position::Attack,
        ]);
        Player::query()->create([
            'name' => 'Marc',
            'position' => Position::Attack,
        ]);
        Player::query()->create([
            'name' => 'Suzy',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'name' => 'Paul',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'name' => 'Ann',
            'position' => Position::Mid,
        ]);
        Player::query()->create([
            'name' => 'Martin',
            'position' => Position::Defense,
        ]);
        Player::query()->create([
            'name' => 'Mike',
            'position' => Position::Defense,
        ]);
        Player::query()->create([
            'name' => 'William',
            'position' => Position::Goal,
        ]);
    }
}
