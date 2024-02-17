<?php

namespace App\Models;

use App\Contract\BelongsToAccount;
use App\Modules\Attendance\Traits\Attendee;
use App\Repositories\Filters\Traits\FiltersRecords;
use App\Traits\HasAccount;
use App\Traits\HasProfilePicture;
use Carbon\Carbon;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property Carbon|null $email_verified_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string $avatar
 * @property string $gravatar
 * @property string $profile_picture
 */
class User extends Authenticatable implements MustVerifyEmailContract, BelongsToAccount
{
    use Attendee;
    use HasRoles;
    use HasAccount;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;
    use FiltersRecords;
    use HasPermissions;
    use MustVerifyEmail;
    use HasProfilePicture;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function owns(BelongsToAccount $model): bool
    {
        return $model->account_id === $this->account_id;
    }

    public function getPlanCorrectedPermissions(): Collection
    {
        $permissions = $this->getAllPermissions();

        $exclude = [];

        if (!$this->account->plan()->canCreateTeams()) {
            $exclude[] = 'team.create';
        }

        if (!$this->account->plan()->canCreateUsers()) {
            $exclude[] = 'user.create';
        }

        return $exclude ? $permissions->whereNotIn('name', $exclude) : $permissions;
    }
}
