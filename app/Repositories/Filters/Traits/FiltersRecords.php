<?php

namespace App\Repositories\Filters\Traits;

use App\Repositories\Filters\Contracts\Filters;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for Eloquent models to filter records based on an array of filters.
 *
 * @method static Builder filterBy(Filters $filters);
 */
trait FiltersRecords
{
    public function scopeFilterBy(Builder $query, Filters $filters): Builder
    {
        return $filters->applyToQuery($query);
    }
}
