<?php

namespace App\Repositories;

use App\Filters\UserFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends AbstractRepository
{
    public function __construct(UserFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return $this->limitByAccountWhenNeeded(User::filterBy($this->filters));
    }

    public function store(array $attributes): User
    {
        $attributes['account_id'] = $this->user->account_id;

        return User::query()->create($attributes);
    }

}
