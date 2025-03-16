<?php

namespace App\Repositories;

use App\Filters\GameFilter;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GameRepository extends AbstractRepository
{
    public function __construct(GameFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return $this->limitByAccountWhenNeeded(Game::filterBy($this->filters))
            ->orderByDesc('start_at');
    }

    public function store(array $attributes): Game|Model
    {
        $attributes['account_id'] = $this->user->account_id;

        return Game::query()->create($attributes);
    }

    public function storeOpponent(string $name): Team|Model
    {
        return Team::query()->create([
            'name' => $name,
            'is_opponent' => true,
            'account_id' => $this->user->account_id,
        ]);
    }

}
