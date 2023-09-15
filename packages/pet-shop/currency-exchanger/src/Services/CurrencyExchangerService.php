<?php

namespace PetShop\CurrencyExchanger\Services;

use GuzzleHttp\Client;

class CurrencyExchangerService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getExchangeRate($currencyToExchange)
    {
        $response = $this->client->get('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
        $xml = simplexml_load_string($response->getBody());

        $rates = [];
        foreach ($xml->Cube->Cube->Cube as $rate) {
            $rates[(string) $rate['currency']] = (float) $rate['rate'];
        }

        return $rates[$currencyToExchange] ?? null;
    }

    public function convertCurrency($amount, $exchangeRate)
    {
        return $amount * $exchangeRate;
    }
}
