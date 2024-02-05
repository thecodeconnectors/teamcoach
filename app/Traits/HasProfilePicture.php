<?php

namespace App\Traits;

/**
 * @property string $avatar
 * @property string $gravatar
 * @property string $profile_picture
 */
trait HasProfilePicture
{
    public function getGravatarAttribute(): string
    {
        $hash = md5(strtolower(trim($this->attributes['email'] ?? '')));

        return "https://www.gravatar.com/avatar/$hash";
    }

    public function getProfilePictureAttribute(): string
    {
        return $this->avatar ?: $this->gravatar;
    }
}
