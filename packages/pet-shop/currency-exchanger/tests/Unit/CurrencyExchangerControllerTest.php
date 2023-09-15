<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;
use PetShop\CurrencyExchanger\Controllers\CurrencyExchangerController;
use PetShop\CurrencyExchanger\Services\CurrencyExchangerService;
use PetShop\CurrencyExchanger\Http\Requests\CurrencyConversionRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;

class CurrencyExchangerControllerTest extends TestCase
{
    public function testConvert()
    {
        // Mocking the CurrencyExchangerService
        $serviceMock = $this->createMock(CurrencyExchangerService::class);
        $serviceMock->method('getExchangeRate')->willReturn(1.2);
        $serviceMock->method('convertCurrency')->willReturn(120);

        // Mocking the ResponseHandlerContract
        $responseHandlerMock = $this->createMock(ResponseHandlerContract::class);
        $responseHandlerMock->expects($this->once())
            ->method('jsonResponse')
            ->willReturn(new JsonResponse(['data' => ['amount' => 100, 'currency_to_exchange' => 'USD', 'converted_amount' => 120]], 200));

        // Instantiating the controller with the mocked dependencies
        $controller = new CurrencyExchangerController($responseHandlerMock, $serviceMock);
        $request = new CurrencyConversionRequest(['amount' => 100, 'currency_to_exchange' => 'USD']);

        // Calling the convert method
        $response = $controller->convert($request);

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['data' => ['amount' => 100, 'currency_to_exchange' => 'USD', 'converted_amount' => 120]], $response->getData(true));
    }


}