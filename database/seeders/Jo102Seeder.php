<?php

namespace Database\Seeders;

use App\Enums\Position;
use App\Models\Account;
use App\Models\Team;
use Illuminate\Database\Seeder;

class Jo102Seeder extends Seeder
{
    public function run(): void
    {
        $avatars = config('avatars.avatars');

        $account = Account::query()->first();

        /** @var Team $team */
        $team = Team::query()->create([
            'account_id' => $account->id,
            'name' => 'JO10-2',
        ]);

        $team->createPlayer([
            'name' => 'Pieter',
            'position' => Position::Attack,
            'avatar' => $avatars[0],
        ]);

        $team->createPlayer([
            'name' => 'Levi',
            'position' => Position::Attack,
            'avatar' => $avatars[1],
        ]);
        $team->createPlayer([
            'name' => 'Ayman',
            'position' => Position::Mid,
            'avatar' => $avatars[2],
        ]);
        $team->createPlayer([
            'name' => 'Yussuf',
            'position' => Position::Mid,
            'avatar' => $avatars[3],
        ]);
        $team->createPlayer([
            'name' => 'Rico',
            'position' => Position::Mid,
            'avatar' => $avatars[4],
        ]);
        $team->createPlayer([
            'name' => 'Moos',
            'position' => Position::Defense,
            'avatar' => $avatars[5],
        ]);
        $team->createPlayer([
            'name' => 'Muhammed',
            'position' => Position::Defense,
            'avatar' => $avatars[6],
        ]);
        $team->createPlayer([
            'name' => 'Mert',
            'position' => Position::Goal,
            'avatar' => $avatars[7],
        ]);
    }
}
