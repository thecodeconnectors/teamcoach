<?php

namespace App\Modules\Users\Filters;

use App\Repositories\Filters\ArrayFilters;
use App\Repositories\Filters\Contracts\SortableInterface;

class UserFilter extends ArrayFilters implements SortableInterface
{
    public function sortableColumns(): array
    {
        return [
            'name',
        ];
    }
}
