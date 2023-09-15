<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PetShop\CurrencyExchanger\Services\CurrencyExchangerService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class CurrencyExchangerServiceTest extends TestCase
{
    public function testGetExchangeRate()
    {
        $mock = new MockHandler([
            new Response(200, [], $this->getMockedXmlResponse()),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new CurrencyExchangerService($client);
        $rate = $service->getExchangeRate('USD');

        $this->assertEquals(1.0730, $rate);
    }

    public function testConvertCurrency()
    {
        $mockClient = new Client(); // No need to mock for this test as it doesn't make HTTP requests
        $service = new CurrencyExchangerService($mockClient);
        $convertedAmount = $service->convertCurrency(100, 1.2);

        $this->assertEquals(120, $convertedAmount);
    }

    private function getMockedXmlResponse(): string
    {
        return <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <gesmes:Envelope xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01" xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref">
            <gesmes:subject>Reference rates</gesmes:subject>
            <gesmes:Sender>
                <gesmes:name>European Central Bank</gesmes:name>
            </gesmes:Sender>
            <Cube>
                <Cube time="2022-01-01">
                    <Cube currency="USD" rate="1.0730"/>
                    <!-- ... other currencies ... -->
                </Cube>
            </Cube>
        </gesmes:Envelope>
        XML;
            }
}
