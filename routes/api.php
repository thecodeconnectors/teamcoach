<?php

use App\Http\Controllers\AvatarController;
use App\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GamePlayController;
use App\Http\Controllers\GamePlayerTypeController;
use App\Http\Controllers\GameStopwatchController;
use App\Http\Controllers\PlayerActionEventTypeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('cookie', [CsrfCookieController::class, 'show'])->name('cookie.show');
Route::get('user', [CurrentUserController::class, 'show'])->name('user.show');

Route::get('settings', [SettingsController::class, 'index']);
Route::get('avatars', [AvatarController::class, 'index']);
Route::get('positions', [PositionController::class, 'index']);
Route::get('game-player-types', [GamePlayerTypeController::class, 'index']);
Route::get('player-action-event-types', [PlayerActionEventTypeController::class, 'index']);

Route::get('games/{game}/play', [GamePlayController::class, 'show']);
Route::post('games/{game}/switch-player', [GamePlayController::class, 'switch']);

Route::post('games/{game}/start', [GameStopwatchController::class, 'start']);
Route::post('games/{game}/finish', [GameStopwatchController::class, 'finish']);
Route::post('games/{game}/pause', [GameStopwatchController::class, 'pause']);
Route::post('games/{game}/resume', [GameStopwatchController::class, 'resume']);

Route::apiResource('games', GameController::class);
Route::apiResource('teams', TeamController::class);
Route::apiResource('players', PlayerController::class);
Route::apiResource('events', EventController::class)->only('store');
