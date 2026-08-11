<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Authentication for the administration area is handled exclusively by Filament.
        Fortify::ignoreRoutes();
    }

    public function boot(): void
    {
        // Filament provides the login, profile and MFA flows for the admin panel.
    }
}
