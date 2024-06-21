<?php

namespace App\Services;

interface CurrencyServiceInterface
{
    /**
     * Get Currencies by code
     * @param array $data array with codes
     * @return array
     */
    public function getCurrencies(array $data): array;
}

