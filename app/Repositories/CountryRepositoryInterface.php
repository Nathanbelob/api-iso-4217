<?php

namespace App\Repositories;

use App\Models\Country;

interface CountryRepositoryInterface
{
    /**
     * Update or create a country
     * @param $country country name
     * @return Country
     */
    public function updateOrCreate(string $country): Country;
}