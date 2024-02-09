<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Contract\BelongsToAccount;
use App\Traits\HasAccount;
use App\Traits\HasProfilePicture;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPermissions, HasRoles, SoftDeletes, HasProfilePicture, HasAccount;

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
}
