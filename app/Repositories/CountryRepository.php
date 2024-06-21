<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{

    /**
     * Update or create a country
     * @param $country country name
     * @return Country
     */
    public function updateOrCreate(string $country): Country
    {
        return Country::updateOrCreate(['name' => $country],
         ['name' => $country]);
    }
}