<?php

namespace App\Models;

use App\Enums\EventType;
use App\Modules\Users\Models\User;
use Illuminate\Support\Arr;

readonly class Plan
{
    public function __construct(protected string $plan) { }

    public function plan(?string $key = null): mixed
    {
        return config($key ? "plans.{$this->plan}.{$key}" : "plans.{$this->plan}");
    }

    public function playerActionEventTypes(): array
    {
        return Arr::only(EventType::playerActionEventTypes(), $this->plan('event_types'));
    }

    public function canCreateTeams(): bool
    {
        return Team::query()->count() < $this->plan('teams');
    }

    public function canCreateUsers(): bool
    {
        return User::query()->count() < $this->plan('users');
    }
}
