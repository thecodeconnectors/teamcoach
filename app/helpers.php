<?php

use App\Models\Setting;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str;

function frontendUrl($path = null, $parameters = []): UrlGenerator|string
{
    return with(clone url(), static function (UrlGenerator $urlGenerator) use ($path, $parameters) {
        $root = config('app.frontend_url');

        $urlGenerator->forceRootUrl($root);

        if (is_null($path)) {
            return $urlGenerator;
        }

        $secure = Str::startsWith($root, 'https://');

        return $urlGenerator->to($path, $parameters, $secure);
    });
}

function secondsToTime(int $totalSeconds = 0): string
{
    $hours = str_pad(intdiv($totalSeconds, 3600), 2, '0', STR_PAD_LEFT);
    $minutes = str_pad(intdiv($totalSeconds % 3600, 60), 2, '0', STR_PAD_LEFT);
    $seconds = str_pad($totalSeconds % 60, 2, '0', STR_PAD_LEFT);

    return $hours !== '00' ? "{$hours}:{$minutes}:{$seconds}" : "{$minutes}:{$seconds}";
}

function settings(string $key = null, $default = null)
{
    return Setting::getSettings($key, $default);
}
