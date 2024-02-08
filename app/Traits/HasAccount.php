<?php

namespace App\Traits;

use App\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $account_id
 * @property Account $account
 */
trait HasAccount
{
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

}
