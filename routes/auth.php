<?php

use App\Http\Controllers\CsrfCookieController;
use App\Modules\Users\Http\Controllers\CurrentUserController;
use App\Modules\Users\Http\Controllers\ResendEmailVerificationEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('forgot-password', fn () => redirect(frontendUrl('auth/forgot-password')));
Route::get('reset-password/{token}', fn ($token, Request $request) => redirect(frontendUrl("auth/reset-password/{$token}?email=" . $request->get('email'))))->name('password.reset');
Route::get('cookie', [CsrfCookieController::class, 'show'])->name('cookie.show');
Route::get('user', [CurrentUserController::class, 'show'])->name('user.show');

Route::post('resend-email-verification-email', [ResendEmailVerificationEmailController::class, 'store']);
