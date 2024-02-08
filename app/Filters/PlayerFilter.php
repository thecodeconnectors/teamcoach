<?php

namespace App\Filters;

use App\Repositories\Filters\ArrayFilters;
use App\Repositories\Filters\Contracts\SortableInterface;

class PlayerFilter extends ArrayFilters implements SortableInterface
{
    public function sortableColumns(): array
    {
        return [
            'name',
        ];
    }
}
