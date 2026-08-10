<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSecurityController;
use App\Http\Middleware\EnsureTwoFactorConfirmed;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;

Route::redirect('/', '/en');

Route::prefix(config('fortify.prefix'))
    ->middleware('auth')
    ->group(function () {

        Route::get(
            '/security',
            [AdminSecurityController::class, 'show']
        )->name('admin.security');

        Route::post(
            '/security/2fa/enable',
            [AdminSecurityController::class, 'enable']
        )
            ->middleware('throttle:5,1')
            ->name('admin.security.2fa.enable');

        Route::post(
            '/security/2fa/confirm',
            [AdminSecurityController::class, 'confirm']
        )
            ->middleware('throttle:6,1')
            ->name('admin.security.2fa.confirm');

        Route::get(
            '/dashboard',
            [AdminController::class, 'dashboard']
        )
            ->middleware(EnsureTwoFactorConfirmed::class)
            ->name('admin.dashboard');
    });


Route::get('/{locale}', HomeController::class)
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->name('front.home');
