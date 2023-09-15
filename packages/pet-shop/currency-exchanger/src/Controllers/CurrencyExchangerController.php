<?php

namespace PetShop\CurrencyExchanger\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;
use PetShop\CurrencyExchanger\Services\CurrencyExchangerService;
use PetShop\CurrencyExchanger\Http\Requests\CurrencyConversionRequest;

class CurrencyExchangerController extends Controller
{
    protected $responseHandler;
    protected $currencyExchangerService;

    public function __construct(ResponseHandlerContract $responseHandler, CurrencyExchangerService $currencyExchangerService)
    {
        $this->responseHandler = $responseHandler;
        $this->currencyExchangerService = $currencyExchangerService;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/currency-exchange",
     *      operationId="currencyExchange",
     *      tags={"Currency Exchange"},
     *      summary="Get Qoute for currency exchange",
     *      @OA\Parameter(
     *          name="amount",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *             type="integer",
     *         ),
     *      ),
     *      @OA\Parameter(
     *          name="currency_to_exchange",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *             type="string",
     *         ),
     *      ),
     *      @OA\Response(response=200, description="OK"),
     *      @OA\Response(response=404, description="Page Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * Display a listing of the resource.
     *
     * @throws Exception
     */
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
            'converted_amount' => $convertedAmount,
        ]);
    }
}
