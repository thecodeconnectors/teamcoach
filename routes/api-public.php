<?php

use App\Http\Controllers\GamePublishController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('settings', [SettingsController::class, 'index']);
Route::get('games/public/{url_secret}', [GamePublishController::class, 'show']);
