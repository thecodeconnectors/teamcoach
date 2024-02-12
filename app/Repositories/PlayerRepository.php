<?php

namespace App\Repositories;

use App\Filters\PlayerFilter;
use App\Models\Player;
use Illuminate\Database\Eloquent\Builder;

class PlayerRepository extends AbstractRepository
{
    public function __construct(PlayerFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return $this->limitByAccountWhenNeeded(Player::filterBy($this->filters));
    }

    public function store(array $attributes): Player
    {
        $attributes['account_id'] = $this->user->account_id;

        return Player::query()->create($attributes);
    }

}
