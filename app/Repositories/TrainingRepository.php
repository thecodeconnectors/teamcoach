<?php

namespace App\Repositories;

use App\Filters\TrainingFilter;
use App\Models\Training;
use Illuminate\Database\Eloquent\Builder;

class TrainingRepository extends AbstractRepository
{
    public function __construct(TrainingFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return $this->limitByAccountWhenNeeded(Training::filterBy($this->filters));
    }

    public function store(array $attributes): Training
    {
        $attributes['account_id'] = $this->user->account_id;

        return Training::query()->create($attributes);
    }

}
