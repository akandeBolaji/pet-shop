<?php

namespace App\Providers;

use App\Libraries\JWTLibraryClient;
use App\Libraries\LcobucciJWT;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(JWTLibraryClient::class, LcobucciJWT::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
