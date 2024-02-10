<?php

namespace App\Repositories;

use App\Filters\SettingFilter;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SettingsRepository extends AbstractRepository
{
    public function __construct(SettingFilter $filters)
    {
        parent::__construct($filters);
    }

    public function withDefaults(): array
    {
        $settings = config('settings');

        foreach ($this->get() as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        return $settings;
    }

    public function query(): Builder
    {
        return Setting::query()->where('account_id', $this->user()->account_id);
    }

    public function store(string $key, mixed $value): Setting|Model
    {
        return Setting::query()->updateOrCreate([
            'key' => $key,
            'account_id' => $this->user()->account_id,
        ], [
            'value' => $value,
        ]);
    }
}
