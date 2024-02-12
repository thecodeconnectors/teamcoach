<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Filters\Contracts\Filters;
use App\Traits\LimitResultsByAccount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

abstract class AbstractRepository
{
    use LimitResultsByAccount;

    protected ?int $perPage = 10;

    protected ?User $user = null;

    protected Filters $filters;

    public function __construct(Filters $filters)
    {
        $this->filters = $filters;
    }

    abstract public function query(): Builder;

    public static function make(): static
    {
        return app(static::class);
    }

    public function setRequest(Request $request): static
    {
        return $this
            ->setFilters($request->all())
            ->setUser($request->user())
            ->setPerPage($request->get('per_page'));
    }

    public function setFilters(array $filters): static
    {
        $this->filters->setFilters($filters);

        return $this;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        $this->filters->setUser($user);

        return $this;
    }

    public function setPerPage(int|null $perPage = 10): static
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function filters(): Filters
    {
        return $this->filters;
    }

    public function get(): Collection
    {
        return $this->query()->get();
    }

    public function paginate(int|null $perPage = null): Collection|LengthAwarePaginator
    {
        return $this->query()->paginate($perPage ?: $this->perPage);
    }

    public function user(): ?User
    {
        return $this->user;
    }
}
