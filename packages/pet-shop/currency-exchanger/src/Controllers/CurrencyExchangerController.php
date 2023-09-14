<?php

namespace PetShop\CurrencyExchanger\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CurrencyExchangerController extends Controller
{
    public function convert(Request $request)
    {
        // Here we'll later add the logic to fetch the exchange rate and return the converted amount.
        return response()->json(['message' => 'Currency conversion endpoint']);
    }
}
