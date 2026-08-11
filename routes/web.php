<?php

use App\Http\Controllers\Front\ContentPageController;
use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en');

Route::get('/{locale}/about', [ContentPageController::class, 'about'])
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->name('front.about');

Route::get('/{locale}/statistics', [ContentPageController::class, 'statistics'])
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->name('front.statistics');

Route::get('/{locale}', HomeController::class)
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->middleware('throttle:60,1')
    ->name('front.home');
