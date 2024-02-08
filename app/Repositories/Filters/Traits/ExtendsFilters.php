<?php

namespace App\Repositories\Filters\Traits;

use App\Repositories\Filters\Contracts\SortableInterface;
use App\Repositories\Filters\Enums\SortDirection;
use App\Repositories\Filters\Exceptions\DoesNotImplementSortableInterfaceException;
use Illuminate\Support\Str;

trait ExtendsFilters
{
    protected array $numericParameters = [
        'page',
        'per_page',
    ];

    public function sort(string $sortBy): void
    {
        $this->ensureSortableInterfaceIsImplemented();

        $sortColumn = $this->getSortColumn($sortBy);
        $direction = $this->getSortDirection($sortBy);

        if (in_array($sortColumn, $this->sortableColumns(), true)) {
            $this->query->orderBy($sortColumn, $direction->value);
        }
    }

    protected function ensureSortableInterfaceIsImplemented(): void
    {
        if (!$this instanceof SortableInterface) {
            $class = get_class($this);
            throw new DoesNotImplementSortableInterfaceException(
                "{$class} does not implements \App\Repositories\Filters\SortableInterface"
            );
        }
    }

    public function getSortColumn(string $sortBy): string
    {
        return explode(':', $sortBy)[0];
    }

    public function getSortDirection(string $sortBy): SortDirection
    {
        $direction = SortDirection::tryFrom(array_reverse(explode(':', $sortBy))[0]);

        return $direction ?? SortDirection::Ascending;
    }

    public function dateRangeSearch(string $date, string $field): void
    {
        $from = $date;
        $to = null;

        if (Str::contains($from, ',')) {
            [$from, $to] = explode(',', $from);
        }

        if ($from) {
            $this->query->where($field, '>=', $from);
        }

        if ($to) {
            $this->query->where($field, '<=', $to);
        }
    }

}
