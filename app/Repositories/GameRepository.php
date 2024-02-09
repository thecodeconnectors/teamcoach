<?php

namespace App\Repositories;

use App\Filters\GameFilter;
use App\Models\Game;
use App\Models\Team;
use App\Modules\Users\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;

class GameRepository extends AbstractRepository
{
    public function __construct(GameFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return Game::filterBy($this->filters)
            ->when(!$this->user()?->hasRole(RoleType::Admin->value), function (Builder $builder) {
                $builder->where('account_id', $this->user()?->account_id);
            });
    }

    public function store(array $attributes): Game
    {
        $attributes['account_id'] = $this->user->account_id;

        return Game::query()->create($attributes);
    }

    public function storeOpponent(string $name): Team
    {
        return Team::query()->create([
            'name' => $name,
            'is_opponent' => true,
            'account_id' => $this->user->account_id,
        ]);
    }

}
