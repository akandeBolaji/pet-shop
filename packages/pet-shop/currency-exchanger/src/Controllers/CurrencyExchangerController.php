<?php

namespace PetShop\CurrencyExchanger\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PetShop\CurrencyExchanger\Services\CurrencyExchangerService;
use PetShop\CurrencyExchanger\Http\Requests\CurrencyConversionRequest;
use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;
use Symfony\Component\HttpFoundation\Response;

class CurrencyExchangerController extends Controller
{
    protected $responseHandler;

    public function __construct(ResponseHandlerContract $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }
 
    public function convert(CurrencyConversionRequest $request, CurrencyExchangerService $currencyExchangerService)
    {
        $amount = $request->input('amount');
        $currencyToExchange = $request->input('currency_to_exchange');

        try {
            $exchangeRate = $currencyExchangerService->getExchangeRate($currencyToExchange);
        } catch (RequestException $e) {
            return $this->responseHandler->jsonResponse(status_code: Response::HTTP_INTERNAL_SERVER_ERROR, error:'Failed to fetch data from the European Central Bank');
        }

        if (!$exchangeRate) {
            return $this->responseHandler->jsonResponse(status_code: Response::HTTP_UNPROCESSABLE_ENTITY, error:'Currency not supported');
        }

        $convertedAmount = $currencyExchangerService->convertCurrency($amount, $exchangeRate);

        return $this->responseHandler->jsonResponse(data:[
            'amount' => $amount,
            'currency_to_exchange' => $currencyToExchange,
            'converted_amount' => $convertedAmount
        ]);
    }
}
