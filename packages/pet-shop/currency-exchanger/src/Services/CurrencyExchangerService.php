<?php

namespace PetShop\CurrencyExchanger\Services;

use GuzzleHttp\Client;

class CurrencyExchangerService
{
    public function getExchangeRate($currencyToExchange)
    {
        // Fetch the exchange rates
        $client = new Client();
        $response = $client->get('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
        $xml = simplexml_load_string($response->getBody());

        $rates = [];
        foreach ($xml->Cube->Cube->Cube as $rate) {
            $rates[(string)$rate['currency']] = (float)$rate['rate'];
        }

        return $rates[$currencyToExchange] ?? null;
    }

    public function convertCurrency($amount, $exchangeRate)
    {
        return $amount * $exchangeRate;
    }
}

