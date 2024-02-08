<?php

namespace App\Repositories;

use App\Filters\EventFilter;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;

class EventRepository extends AbstractRepository
{
    public function __construct(EventFilter $filters)
    {
        parent::__construct($filters);
    }

    public function query(): Builder
    {
        return Event::filterBy($this->filters);
    }

    public function store(array $attributes): Event
    {
        $attributes['account_id'] = $this->user->account_id;

        return Event::query()->create($attributes);
    }

}
