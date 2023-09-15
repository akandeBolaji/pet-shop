<?php

namespace App\Providers;

use App\Libraries\CurrencyExchanger\ResponseHandler;
use App\Libraries\JWTLibraryClient;
use App\Libraries\LcobucciJWT;
use Illuminate\Support\ServiceProvider;
use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(JWTLibraryClient::class, LcobucciJWT::class);
        $this->app->bind(ResponseHandlerContract::class, ResponseHandler::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
