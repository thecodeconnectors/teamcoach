<?php

namespace App\Repositories;

use App\Filters\PlayerFilter;
use App\Models\Player;
use App\Modules\Users\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;

class PlayerRepository extends AbstractRepository
{
    public function __construct(PlayerFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return Player::filterBy($this->filters)
            ->when(!$this->user()?->hasRole(RoleType::Admin->value), function (Builder $builder) {
                $builder->where('account_id', $this->user()?->account_id);
            });
    }

    public function store(array $attributes): Player
    {
        $attributes['account_id'] = $this->user->account_id;

        return Player::query()->create($attributes);
    }

}
