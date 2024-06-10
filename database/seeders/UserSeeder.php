<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Modules\Users\Enums\RoleType;
use App\Modules\Users\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'account_id' => Account::query()->create(['plan' => 'free'])->id,
            'name' => 'Martijn',
            'email' => 'info@axyrmedia.nl',
            'password' => bcrypt('marlijn11'),
            'email_verified_at' => now(),
        ])->assignRole(RoleType::Admin->value);
    }
}
