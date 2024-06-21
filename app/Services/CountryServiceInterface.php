<?php

namespace App\Services;

interface CountryServiceInterface
{
    /**
     * Insert countries
     * @param array $countries countries ids
     * @return array
     */
    public function create(array $countries): array;
}

