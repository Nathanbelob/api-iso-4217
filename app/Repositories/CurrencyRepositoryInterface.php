<?php

namespace App\Repositories;

use App\Models\Currency;

interface CurrencyRepositoryInterface
{
    /**
     * Get currency by code or number
     * @param string $code code or number
     * @return Currency|null
     */
    public function getByCodeOrNumber(string $code): ?Currency;

        /**
     * Insert currency and relation between country and currency
     * @param array $currency currency data
     * @param array $countries countries ids
     * @return void
     */
    public function create(array $currency, array $countries);
}