<?php

namespace Tests\Unit\App\Controllers;

use App\Http\Controllers\CurrencyController;
use App\Http\Requests\CurrencyRequest;
use App\Http\Resources\CurrencyCollection;
use App\Services\CurrencyService;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class CurrencyControllerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetCurrencies()
    {
        $validatedData = [
            'code' => 'EUR'
        ];

        $currencies = [
            ['id' => 1, 'name' => 'USD'],
            ['id' => 2, 'name' => 'EUR']
        ];

        $requestMock = Mockery::mock(CurrencyRequest::class);
        $requestMock->shouldReceive('validated')->andReturn($validatedData);

        $serviceMock = Mockery::mock(CurrencyService::class);
        $serviceMock->shouldReceive('getCurrencies')->with($validatedData)->andReturn($currencies);

        $controller = new CurrencyController($serviceMock);
        $response = $controller->getCurrencies($requestMock);

        $this->assertInstanceOf(CurrencyCollection::class, $response);
    }

    public function testGetCurrenciesValidationException()
    {
        $this->expectException(ValidationException::class);

        $requestMock = Mockery::mock(CurrencyRequest::class);
        $requestMock->shouldReceive('validated')->andThrow(
            new \Illuminate\Validation\ValidationException(
                \Illuminate\Support\Facades\Validator::make([], [])
            )
        );

        $serviceMock = Mockery::mock(CurrencyService::class);

        $controller = new CurrencyController($serviceMock);
        $controller->getCurrencies($requestMock);
    }
}