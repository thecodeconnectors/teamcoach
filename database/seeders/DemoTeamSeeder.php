<?php

namespace Database\Seeders;

use App\Enums\Position;
use App\Models\Account;
use App\Models\Team;
use Illuminate\Database\Seeder;

class DemoTeamSeeder extends Seeder
{
    public function run(): void
    {
        $avatars = config('avatars.avatars');

        $account = Account::query()->first();

        /** @var Team $team */
        $team = Team::query()->create([
            'account_id' => $account->id,
            'name' => 'Team A',
        ]);

        $team->createPlayer([
            'name' => 'John',
            'position' => Position::Attack,
            'avatar' => $avatars[0],
        ]);
        $team->createPlayer([
            'name' => 'Marc',
            'position' => Position::Attack,
            'avatar' => $avatars[1],
        ]);
        $team->createPlayer([
            'name' => 'Suzy',
            'position' => Position::Mid,
            'avatar' => $avatars[2],
        ]);
        $team->createPlayer([
            'name' => 'Paul',
            'position' => Position::Mid,
            'avatar' => $avatars[3],
        ]);
        $team->createPlayer([
            'name' => 'Ann',
            'position' => Position::Mid,
            'avatar' => $avatars[4],
        ]);
        $team->createPlayer([
            'name' => 'Martin',
            'position' => Position::Defense,
            'avatar' => $avatars[5],
        ]);
        $team->createPlayer([
            'name' => 'Mike',
            'position' => Position::Defense,
            'avatar' => $avatars[6],
        ]);
        $team->createPlayer([
            'name' => 'William',
            'position' => Position::Goal,
            'avatar' => $avatars[7],
        ]);
    }
}
