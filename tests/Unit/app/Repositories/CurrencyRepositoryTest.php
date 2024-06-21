<?php

namespace Tests\Unit\Repositories;

use App\Models\Country;
use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

class CurrencyRepositoryTest extends TestCase
{
    private $currencyMock;
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->currencyMock = Mockery::mock(Currency::class);
    }

    public function testCreate()
    {
        $data = [
            'name' => 'Euro',
            'code' => 'EUR',
            'number' => '123',
            'decimal' => 1,
        ];
        $countries = [1];
        $this->currencyMock->shouldReceive('create')->with($data)->andReturn($this->currencyMock);
        $currencyRepository = new CurrencyRepository();
        $result = $currencyRepository->create($data, $countries);
        $this->assertInstanceOf(Currency::class, $result);
    }

    public function testGetByCodeOrNumber()
    {
        $this->currencyMock->shouldReceive('setAttribute')->with('id')->andReturn(1);
        $this->currencyMock->shouldReceive('setAttribute')->with('countries')->andReturn();
        $this->currencyMock->shouldReceive('with')
            ->with('countries')
            ->andReturn($this->currencyMock);

        $this->currencyMock->shouldReceive('where', 'orWhere')
                ->andReturnSelf();

        $this->currencyMock->shouldReceive('first')
                ->andReturn($this->currencyMock);

        $currencyRepository = new CurrencyRepository();

        $result = $currencyRepository->getByCodeOrNumber('EUR');
        $this->assertInstanceOf(Currency::class, $result);
    }
}