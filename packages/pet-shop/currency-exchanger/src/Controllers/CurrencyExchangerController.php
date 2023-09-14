<?php

namespace PetShop\CurrencyExchanger\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use PetShop\CurrencyExchanger\Services\CurrencyExchangerService;
use PetShop\CurrencyExchanger\Http\Requests\CurrencyConversionRequest;

class CurrencyExchangerController extends Controller
{
    public function convert(CurrencyConversionRequest $request, CurrencyExchangerService $currencyExchangerService)
    {
        $amount = $request->input('amount');
        $currencyToExchange = $request->input('currency_to_exchange');

        $exchangeRate = $currencyExchangerService->getExchangeRate($currencyToExchange);

        if (!$exchangeRate) {
            return response()->json(['error' => 'Currency not supported'], 400);
        }

        $convertedAmount = $currencyExchangerService->convertCurrency($amount, $exchangeRate);

        return response()->json([
            'amount' => $amount,
            'currency_to_exchange' => $currencyToExchange,
            'converted_amount' => $convertedAmount
        ]);
    }
}
