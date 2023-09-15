<?php

namespace PetShop\CurrencyExchanger\Controllers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Routing\Controller;
use PetShop\CurrencyExchanger\Services\CurrencyExchangerService;
use PetShop\CurrencyExchanger\Http\Requests\CurrencyConversionRequest;
use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;
use Symfony\Component\HttpFoundation\Response;

class CurrencyExchangerController extends Controller
{
    protected $responseHandler;
    protected $currencyExchangerService;

    public function __construct(ResponseHandlerContract $responseHandler, CurrencyExchangerService $currencyExchangerService)
    {
        $this->responseHandler = $responseHandler;
        $this->currencyExchangerService = $currencyExchangerService;
    }
 
    public function convert(CurrencyConversionRequest $request)
    {
        $amount = $request->input('amount');
        $currencyToExchange = $request->input('currency_to_exchange');

        try {
            $exchangeRate = $this->currencyExchangerService->getExchangeRate($currencyToExchange);
        } catch (RequestException $e) {
            return $this->responseHandler->jsonResponse(status_code: Response::HTTP_INTERNAL_SERVER_ERROR, error:'Failed to fetch data from the European Central Bank');
        }

        if (!$exchangeRate) {
            return $this->responseHandler->jsonResponse(status_code: Response::HTTP_UNPROCESSABLE_ENTITY, error:'Currency not supported');
        }

        $convertedAmount = $this->currencyExchangerService->convertCurrency($amount, $exchangeRate);

        return $this->responseHandler->jsonResponse(status_code: Response::HTTP_OK, data:[
            'amount' => $amount,
            'currency_to_exchange' => $currencyToExchange,
            'converted_amount' => $convertedAmount
        ]);
    }
}
