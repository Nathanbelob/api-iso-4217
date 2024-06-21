<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use Mockery;
use App\Repositories\CountryRepository;
use App\Models\Country;

class CountryRepositoryTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testUpdateOrCreate()
    {
        $countryMock = Mockery::mock(Country::class);
        $countryMock->shouldReceive("setAttribute")->with('id')->andReturn(1);
        $countryMock->shouldReceive("setAttribute")->with('name')->andReturn('Brasil');
        $countryName = 'Brasil';
        $countryMock->shouldReceive('updateOrCreate')
            ->with(['name' => $countryName], ['name' => $countryName])
            ->andReturn($countryMock);

        $countryRepository = new CountryRepository();

        $result = $countryRepository->updateOrCreate($countryName);

        $this->assertInstanceOf(Country::class, $result);
    }
}