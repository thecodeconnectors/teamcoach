<?php

namespace App\Contract;

use App\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $account_id
 * @property Account $account
 */
interface BelongsToAccount
{
    public function account(): BelongsTo;
}
