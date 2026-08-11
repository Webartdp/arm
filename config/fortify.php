<?php

use Laravel\Fortify\Features;

return [

    'guard' => 'web',

    'passwords' => 'users',

    'username' => 'email',

    'email' => 'email',

    'lowercase_usernames' => true,

    // Filament is the only administration interface.
    'home' => '/'.env('ADMIN_PATH').'/panel',

    // The secret prefix is also used by the Filament panel path.
    'prefix' => env('ADMIN_PATH'),

    'domain' => null,

    'middleware' => ['web'],

    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
        'passkeys' => 'passkeys',
    ],

    // Fortify's UI routes are disabled in FortifyServiceProvider.
    'views' => false,

    'passkeys' => [
        'relying_party_id' => parse_url(config('app.url'), PHP_URL_HOST),
        'allowed_origins' => [config('app.url')],
        'timeout' => 60000,
    ],

    // Kept for package compatibility; admin authentication is handled by Filament.
    'features' => [
        Features::updatePasswords(),

        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]),
    ],
];
