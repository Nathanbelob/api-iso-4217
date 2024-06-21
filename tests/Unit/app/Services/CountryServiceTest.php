<?php

namespace Tests\Unit\App\Services;

use Mockery;
use Tests\TestCase;
use App\Models\Country;
use App\Services\CountryService;
use App\Repositories\CountryRepository;

class CountryServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate()
    {
        $model = new Country();
        $model->id = 1;
        $countryRepositoryMock = Mockery::mock(CountryRepository::class);
        $countries = ['Brazil'];
        $countryRepositoryMock->shouldReceive('updateOrCreate')
            ->with('Brazil')
            ->andReturn($model);

        $countryService = new CountryService($countryRepositoryMock);
        $result = $countryService->create($countries);

        $this->assertEquals([1], $result);
    }
}