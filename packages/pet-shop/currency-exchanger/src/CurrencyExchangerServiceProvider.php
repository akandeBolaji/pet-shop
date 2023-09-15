<?php

namespace PetShop\CurrencyExchanger;

use Illuminate\Support\ServiceProvider;

use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;
use PetShop\CurrencyExchanger\Services\DefaultResponseHandler;

class CurrencyExchangerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/currency-exchanger.php' => config_path('currency-exchanger.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Here we'll bind classes, register services, etc.
        if (!$this->app->bound(ResponseHandlerContract::class)) {
            $this->app->bind(ResponseHandlerContract::class, DefaultResponseHandler::class);
        }
    }
}
