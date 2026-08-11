<?php

use App\Http\Controllers\Front\ContentPageController;
use App\Http\Controllers\Front\DocumentDownloadController;
use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en');

Route::get('/{locale}/about', [ContentPageController::class, 'about'])
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->name('front.about');

Route::get('/{locale}/statistics', [ContentPageController::class, 'statistics'])
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->name('front.statistics');

Route::get('/{locale}/document/{trackingNumber}/download', DocumentDownloadController::class)
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->where('trackingNumber', '[A-Za-z0-9-]+')
    ->middleware('throttle:30,1')
    ->name('front.document.download');

Route::get('/{locale}', HomeController::class)
    ->whereIn('locale', ['am', 'en', 'ru'])
    ->middleware('throttle:60,1')
    ->name('front.home');
