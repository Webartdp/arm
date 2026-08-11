<?php

use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en');

Route::get('/{locale}', HomeController::class)
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->name('front.home');
