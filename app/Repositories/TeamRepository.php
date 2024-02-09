<?php

namespace App\Repositories;

use App\Filters\TeamFilter;
use App\Models\Team;
use App\Modules\Users\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;

class TeamRepository extends AbstractRepository
{
    public function __construct(TeamFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return Team::filterBy($this->filters)
            ->when(!$this->user()?->hasRole(RoleType::Admin->value), function (Builder $builder) {
                $builder->where('account_id', $this->user()?->account_id);
            })
            ->where('is_opponent', 0);
    }

    public function store(array $attributes): Team
    {
        $attributes['account_id'] = $this->user->account_id;

        return Team::query()->create($attributes);
    }

}
