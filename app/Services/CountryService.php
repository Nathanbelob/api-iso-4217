<?php

namespace App\Services;

use App\Repositories\CountryRepository;


class CountryService implements CountryServiceInterface
{
    private $countryRepository;
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Insert countries
     * @param array $countries countries ids
     * @return array
     */
    public function create(array $countries): array
    {
        $countryIds = [];
        foreach($countries as $country)
        {
            $countryIds[] = $this->countryRepository->updateOrCreate($country)->id;
        }

        return $countryIds;
    }
}

