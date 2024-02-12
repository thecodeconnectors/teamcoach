<?php

namespace App\Repositories;

use App\Filters\TeamFilter;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;

class TeamRepository extends AbstractRepository
{
    public function __construct(TeamFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return $this
            ->limitByAccountWhenNeeded(Team::filterBy($this->filters))
            ->where('is_opponent', 0);
    }

    public function store(array $attributes): Team
    {
        $attributes['account_id'] = $this->user->account_id;

        return Team::query()->create($attributes);
    }

}
