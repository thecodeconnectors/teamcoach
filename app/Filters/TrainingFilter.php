<?php

namespace App\Filters;

use App\Repositories\Filters\ArrayFilters;
use App\Repositories\Filters\Contracts\SortableInterface;

class TrainingFilter extends ArrayFilters implements SortableInterface
{
    public function sortableColumns(): array
    {
        return [
            'start_at',
        ];
    }
}
