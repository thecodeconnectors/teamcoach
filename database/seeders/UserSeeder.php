<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use App\Modules\Users\Enums\RoleType;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'account_id' => Account::query()->create(),
            'name' => 'Martijn',
            'email' => 'info@axyrmedia.nl',
            'password' => bcrypt('marlijn11'),
            'email_verified_at' => now(),
        ])->assignRole(RoleType::Admin->value);
    }
}
