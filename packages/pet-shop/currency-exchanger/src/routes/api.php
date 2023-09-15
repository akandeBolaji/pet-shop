<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function (): void {
    Route::get('/currency-exchange', 'PetShop\CurrencyExchanger\Controllers\CurrencyExchangerController@convert');
});
