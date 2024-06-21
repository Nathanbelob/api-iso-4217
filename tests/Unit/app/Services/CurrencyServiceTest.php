<?php

namespace Tests\Unit\App\Services;

use App\Models\Currency;
use Mockery;
use Tests\TestCase;
use App\Services\CurrencyService;
use App\Repositories\CurrencyRepository;
use App\Services\CountryService;
use App\Services\CrawlingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CurrencyServiceTest extends TestCase
{
    private $currencyRepositoryMock;
    private $countryServiceMock;
    private $crawlingServiceMock;
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp(); 
        $this->currencyRepositoryMock = Mockery::mock(CurrencyRepository::class);
        $this->countryServiceMock = Mockery::mock(CountryService::class);
        $this->crawlingServiceMock = Mockery::mock(CrawlingService::class);
    }

    public function testGetCurrenciesWithoutCacheAndDatabase()
    {
        $data = ['code' => ['USD']];
        $currencyDataUSD = [
            'code' => 'USD',
            'countries' => 'United States'
        ];

        Cache::shouldReceive('get')
            ->with('USD')
            ->andReturn(null);

        $this->currencyRepositoryMock->shouldReceive('getByCodeOrNumber')
            ->with('USD')
            ->andReturn(null);

        $this->crawlingServiceMock->shouldReceive('fetchCurrencyCodes')
            ->with('USD')
            ->andReturn(collect($currencyDataUSD));

        $this->countryServiceMock->shouldReceive('create')
            ->with(['United States'])
            ->andReturn(['United States']);

        $this->currencyRepositoryMock->shouldReceive('create')
            ->with($currencyDataUSD, ['United States']);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) use ($currencyDataUSD) {
                $callback();
                $this->currencyRepositoryMock->shouldReceive('create')
                    ->with($currencyDataUSD, ['United States']);
            });


        Cache::shouldReceive('put')
            ->with('USD', Mockery::type('array'), 60);

        $currencyService = new CurrencyService($this->currencyRepositoryMock, $this->countryServiceMock, $this->crawlingServiceMock);

        $result = $currencyService->getCurrencies($data);

        // Assertions
        $this->assertCount(1, $result);
        $this->assertEquals('USD', $result[0]['code']);
        $this->assertEquals('United States', $result[0]['countries'][0]);
    }

    public function testGetCurrenciesWithCache()
    {
        $data = ['code' => ['USD']];
        $currencyDataUSD = [
            'code' => 'USD',
            'countries' => 'United States'
        ];


        Cache::shouldReceive('get')
            ->with('USD')
            ->andReturn($currencyDataUSD);

        $currencyService = new CurrencyService($this->currencyRepositoryMock, $this->countryServiceMock, $this->crawlingServiceMock);

        $result = $currencyService->getCurrencies($data);

        // Assertions
        $this->assertCount(1, $result);
        $this->assertEquals('USD', $result[0]['code']);
        $this->assertEquals('United States', $result[0]['countries'][0]);
    }

    public function testGetCurrenciesWithoutCacheWithDatabase()
    {
        $data = ['code' => ['USD']];
        $currencyDataUSD = [
            'code' => 'USD',
            'countries' => [[
                'name' => 'United States'
            ]]
        ];

        $model = new Currency($currencyDataUSD);
        $model->countries = [[
            'name' => 'United States'
        ]];

        Cache::shouldReceive('get')
            ->with('USD')
            ->andReturn(null);

            
            $this->currencyRepositoryMock->shouldReceive('getByCodeOrNumber')
            ->andReturn($model);

        Cache::shouldReceive('put')
            ->with('USD', Mockery::type('array'), 60);

        $currencyService = new CurrencyService($this->currencyRepositoryMock, $this->countryServiceMock, $this->crawlingServiceMock);

        $result = $currencyService->getCurrencies($data);

        // Assertions
        $this->assertCount(1, $result);
        $this->assertEquals('USD', $result[0]['code']);
        $this->assertEquals('United States', $result[0]['countries'][0]);
    }
}