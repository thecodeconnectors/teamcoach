<?php

namespace App\Traits;

use App\Modules\Users\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;

trait LimitResultsByAccount
{
    public function limitByAccountWhenNeeded(Builder $builder): Builder
    {
        return $builder->when(!$this->user()?->hasRole(RoleType::Admin->value), function (Builder $builder) {
            $builder->where('account_id', $this->user()?->account_id);
        });
    }
}
