<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GamePlayController;
use App\Http\Controllers\GamePlayerTypeController;
use App\Http\Controllers\GamePublishController;
use App\Http\Controllers\GameStopwatchController;
use App\Http\Controllers\PlayerActionEventTypeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingPlayerController;
use App\Modules\Attendance\Http\Controllers\AttendanceStateController;
use App\Modules\Settings\Http\Controllers\SettingsController;
use App\Modules\Users\Http\Controllers\AvatarController;
use App\Modules\Users\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('settings/{key}', [SettingsController::class, 'store'])->where('key', implode('|', array_keys(config('settings'))));

Route::get('avatars', [AvatarController::class, 'index']);
Route::get('positions', [PositionController::class, 'index']);
Route::get('game-player-types', [GamePlayerTypeController::class, 'index']);
Route::get('player-action-event-types', [PlayerActionEventTypeController::class, 'index']);
Route::get('attendance-states', [AttendanceStateController::class, 'index']);

Route::post('games/{game}/publish', [GamePublishController::class, 'publish']);
Route::post('games/{game}/unpublish', [GamePublishController::class, 'unpublish']);

Route::get('games/{game}/play', [GamePlayController::class, 'show']);
Route::post('games/{game}/switch-players', [GamePlayController::class, 'switch']);

Route::post('games/{game}/start', [GameStopwatchController::class, 'start']);
Route::post('games/{game}/finish', [GameStopwatchController::class, 'finish']);
Route::post('games/{game}/pause', [GameStopwatchController::class, 'pause']);
Route::post('games/{game}/resume', [GameStopwatchController::class, 'resume']);

Route::post('training/{training}/players', [TrainingPlayerController::class, 'store']);
Route::patch('training/{training}/players/{player}', [TrainingPlayerController::class, 'update']);

Route::apiResource('users', UserController::class);
Route::apiResource('games', GameController::class);
Route::apiResource('teams', TeamController::class);
Route::apiResource('players', PlayerController::class);
Route::apiResource('training', TrainingController::class);
Route::apiResource('events', EventController::class)->only(['store', 'update', 'destroy']);
