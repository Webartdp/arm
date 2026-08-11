<?php

use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en');

Route::get('/{locale}', HomeController::class)
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->middleware('throttle:60,1')
    ->name('front.home');
