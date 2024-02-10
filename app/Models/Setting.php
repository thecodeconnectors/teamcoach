<?php

namespace App\Models;

use App\Contract\BelongsToAccount;
use App\Traits\HasAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @property string $key
 * @property mixed $value
 */
class Setting extends Model implements BelongsToAccount
{
    use HasAccount;

    protected $guarded = [];

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    protected static array $deletableSettings = [];

    public static function getDefaults(): array
    {
        return config('settings');
    }

    public static function getDeletableSettings(): array
    {
        return static::$deletableSettings;
    }

    public static function getSettings(string $key = null, $default = null)
    {
        $tenant = static::getTenant();

        $settings = static::cachedSettingsFromDatabase($tenant);

        if ($key) {
            if ($setting = $tenant->{$key}) {
                return $setting;
            }

            if ($setting = Arr::get($settings, $key)) {
                return $setting;
            }

            if (!strpos($key, '.')) {
                $parts = [];
                foreach ($settings as $k => $v) {
                    if (Str::startsWith($k, "$key.")) {
                        $index = str_replace("$key.", '', $k);
                        $parts[$index] = $v;
                    }
                }

                return $parts;
            }

            return $default;
        }

        return $settings;
    }

    private static function cachedSettingsFromDatabase(Account $account): Collection
    {
        return Cache::store('array')->rememberForever($account->id . '.settings', function () use ($account) {
            return static::query()->select('key', 'value')->pluck('value', 'key');
        });
    }
}
